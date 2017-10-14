<?php

namespace frontend\controllers\api;

use Yii;
use frontend\models\ContentCatlog;
use yii\filters\AccessControl;
use frontend\models\Search\ContentCatlogSearch;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;


/**
 * 接口类
 */
class ContentCatlogController extends CurdController
{
    public $enableCsrfValidation = false;//关闭csrf验证
    public $orderField=[
        'catid' , 'pid'
        //'videoPath', 'content', 'status'
    ];
    public $searchField=[
        'catid'  , 'catname' , 'pid'
        //'videoPath', 'content', 'status'
    ];
    public $modelName="ContentCatlog";
    public $searchModelName="ContentCatlogSearch";
    public $namespace="\\frontend\\models\\";
    public $action=['list',"view"];//允许的操作
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
    /**
     * Finds the Content model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Content the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
//    protected function findContent($id)
//    {
//        return $this->findModel($id,true,"Content","\\backend\\models");
//    }
}
