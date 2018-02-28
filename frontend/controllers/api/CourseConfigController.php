<?php

namespace frontend\controllers\api;

use Yii;
use common\models\CourseConfig;

use yii\filters\VerbFilter;


/**
 * 接口类
 */
class CourseConfigController extends ApiController
{
    public $enableCsrfValidation = false;//关闭csrf验证
    public $orderField=[
        'id'  , 'catid' , 'order'  , 'inputtime', 'updatetime',
        //'videoPath', 'content', 'status'
    ];
    public $searchField=[
        'title'  , 'catid' , 'keywords'  , 'description', 'username','content'
        //'videoPath', 'content', 'status'
    ];
    public $modelName="CourseConfig";
    public $namespace="\\common\\models\\";
    public $action=['list'];//允许的操作
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

    public function actionList()
    {
        $returnData = CourseConfig::getConfigData();
        $this->setReturn('', '', $returnData[1]);
    }
}
