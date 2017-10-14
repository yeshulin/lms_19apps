<?php

namespace frontend\controllers\api;

use Yii;
use backend\models\Nav;
use yii\filters\AccessControl;
use backend\models\search\NavSearch;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;


/**
 * 接口类
 */
class NavController extends CurdController
{
    public $enableCsrfValidation = false;//关闭csrf验证
    public $orderField=[
        'id'  , 'name' , 'order' ,
    ];
    public $searchField=[
        'id'  , 'name' , 'order','parent','url'
    ];
    public $modelName="Nav";
    public $searchModelName="NavSearch";
    public $namespace="\\backend\\models\\";
    public $action=['list',"view"];//允许的操作
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
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

}
