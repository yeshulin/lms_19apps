<?php
/**
 * Created by PhpStorm.
 * User: yunjiao
 * Date: 2017/10/23
 * Time: 20:21
 */
namespace frontend\controllers\api;
use Yii;
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
        yii::info(yii::$app->request->getRawBody());
    }

    function gmt_iso8601($time) {
        $dtStr = date("c", $time);
        $mydatetime = new \DateTime($dtStr);
        $expiration = $mydatetime->format(DateTime::ISO8601);
        $pos = strpos($expiration, '+');
        $expiration = substr($expiration, 0, $pos);
        return $expiration."Z";
    }



}