<?php

namespace frontend\controllers\api;

use common\models\Answer;
use common\models\Comment;
use Yii;
use frontend\models\Agree;
use frontend\models\Question;
use yii\filters\AccessControl;
use frontend\models\Search\AgreeSearch;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;


/**
 * 接口类
 */
class AgreeController extends CurdController
{
    public $enableCsrfValidation = false;//关闭csrf验证
    public $orderField=[
        'id'  , 'userid' , 'commentid' ,'created_at',
        //'videoPath', 'content', 'status'
    ];
    public $searchField=[
        'userid' , 'commentid' , 'touserid',
        //'videoPath', '', 'status'
    ];
    public $modelName="Agree";
    public $searchModelName="AgreeSearch";
    public $namespace="\\frontend\\models\\";
    public $action=['list',"view","create","delete"];//允许的操作
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
//            'access' => [
//                'class' => AccessControl::className(),
//                'rules' => [
//                    [
//                        'actions' => ['list','view'],
//                        'allow' => true,
//                    ],
//                ],
//            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }
    public function  init(){
        parent::init();
    }
    public function actionCreate()
    {
        $agree = $this->rawBody['params'];
        $agreeinfo = Agree::find()->where(['userid'=>$agree['userid'],'commentid'=>$agree['commentid'],'touserid'=>$agree['touserid']])->one();
        if($agreeinfo){
            $this->setReturn("0002", "failed", '', "已经点赞过此条信息！");
        }
        $xid = explode('_',$agree['commentid']);
        $t_pre = $xid[0];
        $id = $xid[1];
        if($t_pre=="q"){
            $question = new Question();
            $question = $question::findOne($id);
            $question->agree = $question['agree']+1;
            $lastid = $question->save();
            if($lastid)
            {
                $this->setReturn("0000", "success", '', "success！");
            }
        }elseif($t_pre=="a"){
            $answer = new Answer();
            $answer = $answer::findOne($id);
            $answer->agree = $answer['agree']+1;
            $lastid = $answer->save();
            if($lastid)
            {
                $this->setReturn("0000", "success", '', "success！");
            }
        }elseif($t_pre=="c"){
            $comment = new Comment();
            $comment = $comment::findOne($id);
            $comment->agree = $comment['agree']+1;
            $lastid = $comment->save();
            if($lastid)
            {
                $this->setReturn("0000", "success", '', "success！");
            }
        }
        return parent::actionCreate();
    }

}
