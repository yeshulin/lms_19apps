<?php
namespace frontend\controllers\api;

use common\models\MyLive;
use common\models\UseLab;
use common\models\UsePractical;
use Yii;
use yii\filters\VerbFilter;
use common\models\Order;
use common\models\Mycourse;
use common\models\Course;
use common\models\Goods;
use common\models\Lab;
use common\models\Practical;
use common\models\Live;
use common\models\MyLab;
use common\models\MyPractical;
use common\models\Activationlog;
use backend\models\Activation;
use yii\httpclient\Client;

class GrantController extends ApiController
{
    public $wanjieurl;
    public $desktopurl;
    public $client;
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'index' => ['POST', 'GET'],
                ],
            ],
        ];
    }

    public function init()
    {
        parent::init(); // TODO: Change the autogenerated stub
        $this->wanjieurl = yii::$app->params["api"]["vboss"]."/hqy/addWangjieFromCollege";
        $this->desktopurl = yii::$app->params["api"]["vboss"]."/hqy/addDesktopFromCollege";
        $this->client = new Client([
            'transport' => 'yii\httpclient\CurlTransport'
        ]);
    }

    public function actionSet(){
        $data = json_decode(Yii::$app->request->rawBody, true);
        if(empty($data['trade_sn'])){
            $this->setReturn("0003", "failed", "", "trade_sn参数错误");
        }
        $orderinfo = Order::arrayOne(['trade_sn' => $data['trade_sn']]);//获取商品详情
        //$this->setReturn("0000","success",$orderinfo);
        if($orderinfo['goods'][0]['goods_info']['type']==4){
            //表示直播产品
            $this->_setLive($orderinfo);
        }else{
           // $this->setReturn("33324","success",$orderinfo);
            foreach($orderinfo['goods'] as $key=>$val){
               //$this->setReturn("33324","success",$val['goods_info']['type']);
                if($val['goods_info']['type']==1){//表示在线课程产品
                    $this->_setCourse($orderinfo,$val);
                }elseif($val['goods_info']['type']==2){//表示实验室类型
                    $this->_setLab($orderinfo,$val);
                }elseif($val['goods_info']['type']==3){//表示实训平台类型
                    $this->_setPractical($orderinfo,$val);
                }
            }
        }
    }
    /*
     *课程授权接口
     * */
    public function _setCourse($orderinfo,$val){
        $myCourse = new Mycourse();
        $Course = new Course();
        $courseInfo = $Course::find()
            ->where(['course_id' => $val['goods_info']['association_id']])
            ->asArray()
            ->one();
        //$this->setReturn("0000","success",$courseInfo);
        $myCourse->courseid = $courseInfo['course_id'];
        $myCourse->userid = $orderinfo['userid'];
        $myCourse->auth_end_at = time()+$courseInfo['auth_count_time'];
        $isGrant = $myCourse->save();
        if($isGrant){
            $this->setReturn("0000","success",$isGrant);
        }else{
            $this->setReturn("0001","fail");
        }

    }
    /***
     *实验室授权接口
     *     */
    public function _setLab($orderinfo,$val){
        $lab = new Lab();
        //$this->setReturn("333434","success",$val);
        $labinfo = $lab::find()->where(['labid'=>$val['goods_info']['association_id']])->one();//查询实验室
        if($labinfo["type"]=="desktop"){//桌面授权
            $mylab = new MyLab();
            $mylab->lab_name = $labinfo['lab_name'];
            $mylab->userid = $orderinfo['userid'];
            $mylab->status = 1;
            $mylab->totals = $val['num'];
            $mylab->goods_id = $val['goods_info']['goods_id'];
            $mylab->created_at = time();
            if($result = $mylab->save()){
                $this->setReturn("0000","success",$result);
            }else{
                $this->setReturn("0001","fail","添加服务失败");
            }
           /* $parArr = [
                'trade_sn'=>0,
                'loginname'=>$orderinfo['username'],
                'classid'=>0,
                'order_source'=>'college',
                'begin_time'=>time(),
                'end_time'=>time()+123000,
                'can_use_second'=>123200,
                'order_type'=>11,
                'lab_code'=>$val['lab_code']
            ];
            $response = $this->client->createRequest()
                ->setMethod('json')
                ->setUrl(  $this->desktopurl)
                ->setData($parArr)
                ->send();
            if($response->isOk){
                if($response->data['code']==0){
                    //生成授权码

                }
            }*/
        }elseif($labinfo["type"]=="wangjie"){//网界授权
          /* $attribute = array();
           if($val['attrs_info']){
               foreach($val['attrs_info'] as $k=>$v){
                   if($val['attrtype']=="time"){
                       $attribute[$k]['duration_days'] = $v['key'];
                   }elseif($val['attrtype']=="module"){
                       $attribute[$k]['attr_code'] = $v['key'];
                       $attribute[$k]['attr_name'] = $v['name'];
                   }
               }
           }
            $parArr = [
                'trade_sn'=>0,
                'loginname'=>$orderinfo['username'],
                'user_name'=>$orderinfo['username'],
                'lab_code'=>$val['lab_code'],
                'attribute'=>$attribute
            ];
            $response = $this->client->createRequest()
                ->setMethod('json')
                ->setUrl(  $this->desktopurl)
                ->setData($parArr)
                ->send();
            if($response->isOk){
                if($response->data['code']==0){
                    //生成授权码
                }
            }
          */
            $mylab = new MyLab();
            $mylab->lab_name = $labinfo['lab_name'];
            $mylab->userid = $orderinfo['userid'];
            $mylab->status = 1;
            $mylab->totals = $val['num'];
            $mylab->goods_id = $val['goods_info']['goods_id'];
            $mylab->created_at = time();
            if($result = $mylab->save()){
                $this->setReturn("0000","success",$result);
            }else{
                $this->setReturn("0001","fail","添加服务失败");
            }
        }


    }
    /*
     * 实训授权接口
     * */
    public function _setPractical($orderinfo,$val){
        $practical = new Practical();
        $practicalinfo = $practical::find()->where(['practicalid'=>$val['goods_info']['association_id']])->one();//查询实验室
        //$this->setReturn("0000","success",$practicalinfo);
        if($practicalinfo["type"]=="desktop"){//桌面授权
            $mypractical = new MyPractical();
            $mypractical->practical_name = $practicalinfo['practical_name'];
            $mypractical->userid = $orderinfo['userid'];
            $mypractical->status = 1;
            $mypractical->totals = $val['num'];
            $mypractical->goods_id = $val['goods_info']['goods_id'];
            $mypractical->created_at = time();
            if($result = $mypractical->save()){
                $this->setReturn("0000","success",$result);
            }else{
                $this->setReturn("0001","fail","添加服务失败");
            }
           /* $parArr = [
                'trade_sn'=>0,
                'loginname'=>$orderinfo['username'],
                'classid'=>0,
                'order_source'=>'college',
                'begin_time'=>time(),
                'end_time'=>time()+123000,
                'can_use_second'=>123200,
                'order_type'=>11,
                'lab_code'=>$val['lab_code']
            ];
            $response = $this->client->createRequest()
                ->setMethod('json')
                ->setUrl(  $this->desktopurl)
                ->setData($parArr)
                ->send();
            if($response->isOk){
                if($response->data['code']==0){
                    //生成授权码

                }
            }*/
        }elseif($practicalinfo["type"]=="wangjie"){//网界授权
           /* $attribute = array();
            if($val['attrs_info']){
                foreach($val['attrs_info'] as $k=>$v){
                    if($val['attrtype']=="time"){
                        $attribute[$k]['duration_days'] = $v['key'];
                    }elseif($val['attrtype']=="module"){
                        $attribute[$k]['attr_code'] = $v['key'];
                        $attribute[$k]['attr_name'] = $v['name'];
                    }
                }
            }
            $parArr = [
                'trade_sn'=>0,
                'loginname'=>$orderinfo['username'],
                'user_name'=>$orderinfo['username'],
                'lab_code'=>$val['lab_code'],
                'attribute'=>$attribute
            ];
            $response = $this->client->createRequest()
                ->setMethod('json')
                ->setUrl(  $this->desktopurl)
                ->setData($parArr)
                ->send();
            if($response->isOk){
                if($response->data['code']==0){
                    //生成授权码
                }
            }*/
            $mypractical = new MyPractical();
            $mypractical->practical_name = $practicalinfo['practical_name'];
            $mypractical->userid = $orderinfo['userid'];
            $mypractical->status = 1;
            $mypractical->totals = $val['num'];
            $mypractical->goods_id = $val['goods_info']['goods_id'];
            $mypractical->created_at = time();
            if($result = $mypractical->save()){
                $this->setReturn("0000","success",$result);
            }else{
                $this->setReturn("0001","fail","添加服务失败");
            }
        }

    }
    /*
     * 直播授权接口
     * **/
    public function _setLive($orderinfo){
        $live = new Live();
        $liveArr = array();
        $remarks = array("class"=>"直播教室","school"=>"直播校园","platform"=>"直播区域");
        $mylive = new MyLive();
        $mylive->goods_id = 0;
        $mylive->live_name = $remarks[$orderinfo['remarks']];
        $mylive->userid = $orderinfo['userid'];
        foreach($orderinfo['goods'] as $key=>$val){
            //设置用户购买信息
            $liveinfo = $live::find()
                ->where(['liveid'=>$val['goods_info']['association_id']])
                ->one();
            if($liveinfo['type']=='tongdao'){
                $mylive->tongdao = serialize($val['goods_info']);
            }elseif($liveinfo['type']=='liuliang'){
                $mylive->liuliang = serialize($val['goods_info']);
            }elseif($liveinfo['type']=='qiehuan'){
                $mylive->qiehuan = serialize($val['goods_info']);
            }elseif($liveinfo['type']=="menhu"){
                $mylive->menhu = serialize($val['goods_info']);
            }
        }
        $mylive->totals = 1;//默认一次订单只能购买一个场景
        $mylive->created_at = time();
        $mylive->status = 1;//1表示开通中，2表示使用中，0表示过期或者删除
        $result = $mylive->save();
        if($result){
            $this->setReturn("0000","success","添加服务成功!");
        }else{
            $this->setReturn("0001","fail","添加服务失败!");
        }
    }
    /*
     * 激活码授权接口
     * */
    public function actionAct(){
        $data = json_decode(Yii::$app->request->rawBody, true);
        $act_code = $data['act_code'];
        if(empty($act_code)){
            $this->setReturn("0003","fail","","act_code参数错误!");
        }
        $activation = new Activation();
        $activationlog = new Activationlog();
        $actinfo = $activation::find()
            ->where(['activ_code'=>$act_code])
            ->asArray()
            ->one();

        if($actinfo['activ_code']==""){
            $this->setReturn("0003","fail","","激活码不存在!");
        }
        $activationlog->userid = $this->_user->id;
        $activationlog->activ_code = $act_code;
        $isActlog = $activationlog->checkUnique();
        if(!$isActlog){
            $this->setReturn("0004","fail","",$activationlog->getErrors());
        }
        $ptype = explode("_",$actinfo['p_type']);
        $codetype = $ptype[0];//激活码商品类型
        $codelab = $ptype[1];//激活码对应的
        if($codetype==1){//视频课程授权
            $isAct = $this->_actCourse($act_code,$actinfo["product_id"],$codelab);
        }elseif($codetype==2){
            $isAct = $this->_actLab($act_code,$actinfo["product_id"],$codelab);
        }elseif($codetype==3){
            $isAct = $this->_actPractical($act_code,$actinfo["product_id"],$codelab);
        }
        if($isAct){
           //**
            $activationlog->userid = $this->_user->id;
            $activationlog->activ_code = $act_code;
            $activationlog->created_at = time();
            $activationlog->save();
            //添加激活列表信息
            $this->setReturn("0000","success","激活成功！");
        }
    }
    /*
     *
     * */
    /*
    *课程激活接口
    * */
    public function _actCourse($act_code,$goods_id,$codelab){
        $myCourse = new Mycourse();
        $Course = new Course();
        $Goods = new Goods();
        $goodsinfo = $Goods::find()
                ->where(['goods_id' => $goods_id])
                ->asArray()
                ->one();
        $courseInfo = $Course::find()
            ->where(['course_id' => $goodsinfo['association_id']])
            ->asArray()
            ->one();
        $this->setReturn("0000","success",$this->_user->id);
        $myCourse->courseid = $courseInfo['course_id'];
        $myCourse->userid = $this->_user->id;
        $myCourse->auth_end_at = time()+$courseInfo['auth_count_time'];
        $isGrant = $myCourse->save();
        if($isGrant){
            return true;
        }else{
            $this->setReturn("0001","fail");
        }

    }
    /*
     * 实验室激活接口
     * */
    public function _actLab($act_code,$goods_id,$codelab){
        $mylab = new MyLab();
        $uselab = new UseLab();
        $labinfo = $mylab::find()
            ->where(["lab_id"=>$codelab])
            ->asArray()
            ->one();
        //var_dump($labinfo);
        $uselab->lab_code = $labinfo['lab_code'];
        $uselab->lab_id = $labinfo['lab_id'];
        $uselab->userid = $this->_user->id;
        $uselab->username = $this->_user->username;
        $uselab->created_at = time();
        $uselab->status = 1;
        $isGrant = $uselab->save();
        if($isGrant){
            return true;
        }else{
            $this->setReturn("0001","fail");
        }
    }
    /*
    * 实验室激活接口
    * */
    public function _actPractical($act_code,$goods_id,$codelab){
        $mypractical = new MyPractical();
        $usepractical = new UsePractical();
        $prainfo = $mypractical::find()
            ->where(["practical_id"=>$codelab])
            ->asArray()
            ->one();
        //var_dump($labinfo);
        $usepractical->practical_code = $prainfo['practical_code'];
        $usepractical->practical_id = $prainfo['practical_id'];
        $usepractical->userid = $this->_user->id;
        $usepractical->username = $this->_user->username;
        $usepractical->created_at = time();
        $usepractical->status = 1;
        $isGrant = $usepractical->save();
        if($isGrant){
            return true;
        }else{
            $this->setReturn("0001","fail");
        }
    }

}
