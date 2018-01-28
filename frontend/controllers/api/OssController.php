<?php
/**
 * Created by PhpStorm.
 * User: yunjiao
 * Date: 2017/10/23
 * Time: 20:21
 */
namespace frontend\controllers\api;
use Yii;
use yii\httpclient\Client;
use DateTime;


/**
 * 用户接口类，用于用户的更新，查询，添加
 */
class OssController extends ApiController
{
    private $accessid;
    private $host;
    private $key;
    private $callbackUrl;
    public $expire;
    private $dir;

    public function init()
    {
        $this->accessid = yii::$app->params['oss']['accessid'];
        $this->host = yii::$app->params['oss']['host'];
        $this->key = yii::$app->params['oss']['key'];
        $this->callbackUrl = yii::$app->params['oss']['callbakUrl'];
        $this->expire = yii::$app->params['oss']['expire'];
        $this->dir = yii::$app->params['oss']['dir'];
        $this->layout="empty";

    }

    /*
     * oss sign 认证请求
     * */
    public function actionGetSign(){


        $callback_param = array('callbackUrl'=>$this->callbackUrl,
            'callbackBody'=>'filename=${object}&size=${size}&mimeType=${mimeType}&height=${imageInfo.height}&width=${imageInfo.width}',
            'callbackBodyType'=>"application/x-www-form-urlencoded");
        $callback_string = json_encode($callback_param);

        $base64_callback_body = base64_encode($callback_string);
        $now = time();
        $expire = $this->expire; //设置该policy超时时间是10s. 即这个policy过了这个有效时间，将不能访问
        $end = $now + $expire;
        $expiration = $this->gmt_iso8601($end);

        //最大文件大小.用户可以自己设置
        $condition = array(0=>'content-length-range', 1=>0, 2=>1048576000);
        $conditions[] = $condition;

        //表示用户上传的数据,必须是以$dir开始, 不然上传会失败,这一步不是必须项,只是为了安全起见,防止用户通过policy上传到别人的目录
        $start = array(0=>'starts-with', 1=>'$key', 2=>$this->dir);
        $conditions[] = $start;


        $arr = array('expiration'=>$expiration,'conditions'=>$conditions);
        //echo json_encode($arr);
        //return;
        $policy = json_encode($arr);
        $base64_policy = base64_encode($policy);
        $string_to_sign = $base64_policy;
        $signature = base64_encode(hash_hmac('sha1', $string_to_sign, $this->key, true));

        $response = array();
        $response['accessid'] = $this->accessid;
        $response['host'] = $this->host;
        $response['policy'] = $base64_policy;
        $response['signature'] = $signature;
        $response['expire'] = $end;
        $response['callback'] = $base64_callback_body;
        //这个参数是设置用户上传指定的前缀
        $response['dir'] = $this->dir;
        echo json_encode($response);
    }

    /*
     * oss 请求回调
     * */
    public function actionCallBack(){
        // 1.获取OSS的签名header和公钥url header
        $authorizationBase64 = "";
        $pubKeyUrlBase64 = "";
        /*
         * 注意：如果要使用HTTP_AUTHORIZATION头，你需要先在apache或者nginx中设置rewrite，以apache为例，修改
         * 配置文件/etc/httpd/conf/httpd.conf(以你的apache安装路径为准)，在DirectoryIndex index.php这行下面增加以下两行
            RewriteEngine On
            RewriteRule .* - [env=HTTP_AUTHORIZATION:%{HTTP:Authorization},last]
         * */
        if (isset($_SERVER['HTTP_AUTHORIZATION']))
        {
            $authorizationBase64 = $_SERVER['HTTP_AUTHORIZATION'];
        }
        if (isset($_SERVER['HTTP_X_OSS_PUB_KEY_URL']))
        {
            $pubKeyUrlBase64 = $_SERVER['HTTP_X_OSS_PUB_KEY_URL'];
        }

        if ($authorizationBase64 == '' || $pubKeyUrlBase64 == '')
        {
            header("http/1.1 403 Forbidden");
            exit();
        }

    // 2.获取OSS的签名
        $authorization = base64_decode($authorizationBase64);

    // 3.获取公钥
        $pubKeyUrl = base64_decode($pubKeyUrlBase64);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $pubKeyUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        $pubKey = curl_exec($ch);
        if ($pubKey == "")
        {
            //header("http/1.1 403 Forbidden");
            exit();
        }

        // 4.获取回调body
        $body = file_get_contents('php://input');

    // 5.拼接待签名字符串
        $authStr = '';
        $path = $_SERVER['REQUEST_URI'];
        $pos = strpos($path, '?');
        if ($pos === false)
        {
            $authStr = urldecode($path)."\n".$body;
        }
        else
        {
            $authStr = urldecode(substr($path, 0, $pos)).substr($path, $pos, strlen($path) - $pos)."\n".$body;
        }

    // 6.验证签名
        $ok = openssl_verify($authStr, $authorization, $pubKey, OPENSSL_ALGO_MD5);
        if ($ok == 1)
        {
            header("Content-Type: application/json");
            $data = array("Status"=>"Ok");

            echo json_encode($data);
        }
        else
        {
            //header("http/1.1 403 Forbidden");
            exit();
        }

    }
    /*
     * 获取
     * */
    public function actionQuerymedia(){
        $url = "http://mts.cn-shanghai.aliyuncs.com/";
        $fileurls = yii::$app->request->get("fileurls");
        $SignatureNonce = $this->generate_str();
        $Timestamp = $this->gmt_iso8601(time());
        $now = time();
        $expire = $this->expire; //设置该policy超时时间是10s. 即这个policy过了这个有效时间，将不能访问
        $end = $now + $expire;
        $expiration = $this->gmt_iso8601($end);

        //最大文件大小.用户可以自己设置
        $condition = array(0=>'content-length-range', 1=>0, 2=>1048576000);
        $conditions[] = $condition;

        //表示用户上传的数据,必须是以$dir开始, 不然上传会失败,这一步不是必须项,只是为了安全起见,防止用户通过policy上传到别人的目录
        $start = array(0=>'starts-with', 1=>'$key', 2=>$this->dir);
        $conditions[] = $start;


        $arr = array('expiration'=>$expiration,'conditions'=>$conditions);
        //echo json_encode($arr);
        //return;
        $policy = json_encode($arr);
        $base64_policy = base64_encode($policy);
        $string_to_sign = $base64_policy;
        echo $string_to_sign;
        echo "<br>";
        echo $this->key;
        exit;
        $signature = base64_encode(hash_hmac('sha1', $string_to_sign, $this->key, true));
        $params = array(
            "Format"=>"json",
            "Version"=>"2014-06-18",
            "SignatureMethod"=>"Hmac-SHA1",
            "SignatureNonce"=>$SignatureNonce,
            "SignatureVersion"=>"1.0",
            "Signature"=>$signature,
            "AccessKeyId"=>$this->accessid,
           // "Timestamp"=>$Timestamp,
            "Action"=>"QueryMediaListByURL",
            "FileURLs"=>$fileurls,
            "IncludePlayList"=>"true",
            "IncludeMediaInfo"=>"true"
        );
    
        var_dump(http_build_query($params));
        var_dump($url);exit;
        $client = new Client();
        $response = $client->createRequest()
            ->setMethod("GET")
            ->setData($params)
            ->setUrl($url)
            ->send();
         if ($response->isOk) {
               echo json_encode($response->data);
        }else{
            echo 'none!';
        }      

           
    }
    function gmt_iso8601($time) {
        $dtStr = date("c", $time);
        $mydatetime = new \DateTime($dtStr);
        $expiration = $mydatetime->format(DateTime::ISO8601);
        $pos = strpos($expiration, '+');
        $expiration = substr($expiration, 0, $pos);
        return $expiration."Z";
    }
    function generate_str( $length = 20 ) { 
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()-_ []{}<>~`+=,.;:/?|"; 
        $str = ""; 
        for ( $i = 0; $i < $length; $i++ ) 
        { 
            $str .= $chars[ mt_rand(0, strlen($chars) - 1) ]; 
        } 
        return $str; 
    } 



}