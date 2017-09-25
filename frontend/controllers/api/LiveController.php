<?php
namespace frontend\controllers\api;


use Yii;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use yii\httpclient\Client;
use common\models\MyLive;


class LiveController extends ApiController
{
    public $response;
    public $liveurl;
    public $secret_key;
    public $appname = "pgc";
    public $deviceId = "college";

    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'view' => ['POST'],
                    'Roomlist' => ['POST'],
                ],
            ],
        ];
    }

    public function init()
    {
        $this->allowHash=false;//是否开启验证
        parent::init();
        $this->liveurl = yii::$app->params['api']['live'];
        $this->secret_key = yii::$app->params['apikey']['livekey'];
    }
    public function afterAction()
    {
        $this->response->data = $this->setReturn();
    }
    public function checkKey($key){
        if($key!=$this->secret_key){
            $this->setReturn('99997','key值验证错误！',null);
        }
    }
    public function get_token($date,$method){
        $tokenstr = "time=".$date."&app_name=".$this->appname."&method=".$method."&secret_key=".$this->secret_key;
        return md5($tokenstr);
    }
    public function actionLogin(){
        $data = json_decode(Yii::$app->request->rawBody,true);
        $this->checkKey($data['secret_key']);//验证key值
        $date = time();
        $token = $this->get_token($date,"login");
        $url = $this->liveurl."&method=login&token=".$token."&time=".$date;
        $client = new Client([
            'transport' => 'yii\httpclient\CurlTransport'
        ]);

        $parArr = [
            'username'=>$data['username'],
            'password'=>$data['password'],
            'deviceId'=>$this->deviceId,
            'sessionId'=>'0',
        ];
        $parstr = [
            "params"=>json_encode($parArr)
        ];
        $response = $client->createRequest()
            ->setMethod('post')
            ->setUrl( $url)
            ->setData($parstr)
            ->send();
        if ($response->isOk) {
                $this->setReturn($response->data['code'],$response->data['message'],$response->data['params']);
        }
    }
    public function actionMyroomlist(){
        $data = json_decode(Yii::$app->request->rawBody,true);
        $this->checkKey($data['secret_key']);//验证key值
        $date = time();
        $token = $this->get_token($date,"getMyRoomList");
        $url = $this->liveurl."&method=getMyRoomList&token=".$token."&time=".$date;
        $client = new Client([
            'transport' => 'yii\httpclient\CurlTransport'
        ]);

        $parArr = [
            'login_name'=>$data['login_name'],
            'groupCode'=>$data['groupCode'],
            'type'=>"0",
            'page'=>$data['page'],
            'pageSize'=>$data['pageSize'],
            'sessionId'=>$data['sessionId'],
            'deviceId'=>$this->deviceId,
        ];
        $parstr = [
            "params"=>json_encode($parArr)
        ];
        $response = $client->createRequest()
            ->setMethod('post')
            ->setUrl( $url)
            ->setData($parstr)
            ->send();
        if ($response->isOk) {
            $this->setReturn($response->data['code'],$response->data['message'],$response->data['params']);
        }
    }
    public function actionRoomlist(){
        $data = json_decode(Yii::$app->request->rawBody, true);
        $this->checkKey($data['secret_key']);//验证key值
        $date = time();
        $token = $this->get_token($date,"liveRoomList");
        $url = $this->liveurl."&method=liveRoomList&token=".$token."&time=".$date;
        $client = new Client([
            'transport' => 'yii\httpclient\CurlTransport'
        ]);
        $parArr = [
            'groupCode'=>$data['groupCode'],
            'roomType'=>'0',
            'pageSize'=>$data['pageSize'],
            'page'=>$data['page'],
            'sessionId'=>$data['sessionId'],
            'deviceId'=>$this->deviceId
        ];
        $parstr = [
            "params"=>json_encode($parArr)
        ];
        $response = $client->createRequest()
            ->setMethod('post')
            ->setUrl( $url)
            ->setData($parstr)
            ->send();
        if ($response->isOk) {
            $this->setReturn($response->data['code'],$response->data['message'],$response->data['params']);
        }

    }

    public function actionRoomview()
    {
        $data = json_decode(Yii::$app->request->rawBody, true);
        $this->checkKey($data['secret_key']);//验证key值
        if (empty($data['roomId'])) {
            $this->setReturn("0003", "failed", "", "roomId参数错误");

        }
        $date = time();
        $token = $this->get_token($date,"liveRoomList");
        $url = $this->liveurl."&method=liveRoomView&token=".$token."&time=".$date;
        $client = new Client([
            'transport' => 'yii\httpclient\CurlTransport'
        ]);
        $parArr = [
            'roomId'=>$data["roomId"],
            'useType'=>'1',
            'sessionId'=>$data['sessionId'],
            'deviceId'=>$this->deviceId
        ];
        $parstr = [
            "params"=>json_encode($parArr)
        ];
        $response = $client->createRequest()
            ->setMethod('post')
            ->setUrl( $url)
            ->setData($parstr)
            ->send();
        if ($response->isOk) {
            if($response->data['code']=='10000'){
                $re_data = $response->data['params'];
                $re_data['playurl'] = Url::to(["site/live/play",'roomid'=>$re_data['roomId']],true);
                $this->setReturn($response->data['code'],$response->data['message'],$re_data);
            }else{
                $this->setReturn($response->data['code'],$response->data['message'],$response->data['params']);
            }
        }
    }
    public function actionRoomshow()//内部网站使用不需要验证key
    {

        $data = json_decode(Yii::$app->request->rawBody, true);

       // $this->checkKey($data['secret_key']);//验证key值
        if (empty($data['roomId'])) {
            $this->setReturn("0003", "failed", "", "roomId参数错误");

        }
        $date = time();
        $token = $this->get_token($date,"liveRoomList");
        $url = $this->liveurl."&method=liveRoomView&token=".$token."&time=".$date;
        $client = new Client([
            'transport' => 'yii\httpclient\CurlTransport'
        ]);
        $parArr = [
            'roomId'=>$data["roomId"],
            'useType'=>'1',
            'sessionId'=>$data['sessionId'],
            'deviceId'=>$this->deviceId
        ];
        $parstr = [
            "params"=>json_encode($parArr)
        ];
        $response = $client->createRequest()
            ->setMethod('post')
            ->setUrl( $url)
            ->setData($parstr)
            ->send();
        if ($response->isOk) {
            if($response->data['code']=='10000'){
                $re_data = $response->data['params'];
                $re_data['playurl'] = Url::to(["site/live/play",'roomid'=>$re_data['roomId']],true);
                $this->setReturn($response->data['code'],$response->data['message'],$re_data);
            }else{
                $this->setReturn($response->data['code'],$response->data['message'],$response->data['params']);
            }
        }
    }
    public function actionMylive(){
        $mylive = new MyLive();
        $liveinfo = $mylive::find()
            ->where("userid = ".Yii::$app->user->id." and status >= 1")
            ->asArray()
            ->all();
        foreach($liveinfo as $key=>$val){

            $liveinfo[$key]['tongdao'] = $this->asLive($val['tongdao']);
            $liveinfo[$key]['liuliang'] =$this->asLive($val['liuliang']);
            $liveinfo[$key]['qiehuan'] = $this->asLive($val['qiehuan']);
            $liveinfo[$key]['menhu'] = $this->asLive($val['menhu']);
            $liveinfo[$key]['playurl'] = URL::to(["/site/live/play","roomid"=>$val['roomid']]);
        }
        $this->setReturn("0000","success",$liveinfo);
    }
    public function asLive($value)
    {
    //        $arr = [
    //            "attrs" => [
    //                0 => [
    //                    "name" => "aa",
    //                    "num" => 10
    //                ]
    //            ],
    //            "goods_name" => "keyi"
    //        ];
    //        $value = serialize($arr);
        $good = unserialize($value);
        $str = "";
        if (empty($good['attrs'])) {
            $str .=  $good['goods_name'];
        } else {
            $str .= $good['goods_name'];
            $str .= ":";
            foreach ($good['attrs'] as $k => $vo) {
                $str .= "&nbsp;&nbsp;";
                if($vo['inputtype']=='text'){
                    $str .= "[". $vo['num'] . $vo['name'] ."]";
                }else{
                    $str .= "[" . $vo['name'] . "]";
                }
            }
        }
        $str.="<br>";
        return $str;
    }
    public function actionMyview(){
        $data = $this->rawBody;
        //$this->setReturn("0000","success",$data['live_id']);
        $mylive = new MyLive();
        $liveinfo = $mylive::find()
            ->where(["live_id" =>$data['live_id']])
            ->asArray()
            ->one();
        $liveinfo['tongdao'] = $this->asLive($liveinfo['tongdao']);
        $liveinfo['liuliang'] =$this->asLive($liveinfo['liuliang']);
        $liveinfo['qiehuan'] = $this->asLive($liveinfo['qiehuan']);
        $liveinfo['menhu'] = $this->asLive($liveinfo['menhu']);

        $liveinfo['playurl'] = URL::to(["/site/live/play","roomid"=>$liveinfo['roomid']]);
        /*
         * 请求直播详情
         * */
        $date = time();
        $token = $this->get_token($date,"liveRoomList");
        $url = $this->liveurl."&method=liveRoomView&token=".$token."&time=".$date;
        $client = new Client([
            'transport' => 'yii\httpclient\CurlTransport'
        ]);
        $parArr = [
            'roomId'=>$liveinfo['roomid'],
            'useType'=>'1',
            'sessionId'=>0,
            'deviceId'=>$this->deviceId
        ];
        $parstr = [
            "params"=>json_encode($parArr)
        ];
        $response = $client->createRequest()
            ->setMethod('post')
            ->setUrl( $url)
            ->setData($parstr)
            ->send();
        if ($response->isOk) {
            if($response->data['code']=='10000'){
                $re_data = $response->data['params'];
                $re_data['playurl'] = Url::to(["site/live/play",'roomid'=>$re_data['roomId']],true);
                //$this->setReturn($response->data['code'],$response->data['message'],$re_data);
                $liveinfo['roominfo'] = $re_data;
            }else{
                $this->setReturn($response->data['code'],$response->data['message'],$response->data['params']);
            }
        }
        $this->setReturn("0000","success",$liveinfo);
    }

}
