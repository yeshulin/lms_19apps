<?php

namespace frontend\controllers\api;

use Yii;
use frontend\models\Agree;
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
        $agree = $this->rawBody;
        
        return parent::actionCreate();
    }

}
