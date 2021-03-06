<?php
/**
 * Created by PhpStorm.
 * User: BOB
 * Date: 2016/7/19
 * Time: 7:50
 */

namespace backend\controllers;


use backend\models\AuthItem;
use backend\models\Route;
use Yii;
use yii\filters\VerbFilter;
use yii\web\Response;
use yii\web\NotFoundHttpException;

class PermissionController extends AdminController
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'create' => ['post'],
                    'assign' => ['post'],
                    'remove' => ['post'],
                    'refresh' => ['post'],

                ],
            ],
        ];
    }

    public function beforeAction($event)
    {
        parent::beforeAction($event); // TODO: Change the autogenerated stub
        Yii::$app->response->format = Response::FORMAT_JSON;
        return true;
    }


    /**
     * Lists all AuthItem models.
     * @return mixed
     */
    public function actionIndex()
    {
        $route = new Route();
        $Routes = $route->getRoutes();
        Yii::$app->response->format = Response::FORMAT_HTML;

        return $this->render('index', [
            'Routes'=>$Routes,
        ]);
    }

    public function actionCreate(){
        $routes = Yii::$app->getRequest()->post('route', '');
        $routes = trim($routes, '::');
        $authManager = Yii::$app->authManager;
        $routeBool = true;
        if (strpos($routes, '::')){
            $routes = explode('::', $routes);
            $route = '';
            foreach ($routes as $v) :
                if (!preg_match("/^[\w-]+$/", $v)) {
                    $routeBool = false;
                    break;
                }
                $route = $route ? $route.'::'.$v : $v;
            endforeach;
        }
        else if (!empty($routes)) {
            $route = $routes.'::*';
        }
        if($routeBool && ($preModel = $authManager->getPermission($route)) === null){
            $preModel = $authManager->createPermission($route);
            $preModel->description = '创建了 ' . $route. ' 权限';
            $authManager->add($preModel);
        }
        $model = new Route();
        return $model->getRoutes();
    }

    public function actionAssign(){
        $routes = Yii::$app->getRequest()->post('routes', []);

        $routes = explode('||', $routes);
        $authManager = Yii::$app->authManager;
        foreach ($routes as $v)
        {
            if(($preModel = $authManager->getPermission($v)) === null){
                $preModel = $authManager->createPermission($v);
                $preModel->description = '创建了 ' . $v. ' 权限';
                $authManager->add($preModel);
            }

        }

        $route = new Route();
        return $route->getRoutes();
    }

    public function actionRemove(){
        $routes = Yii::$app->getRequest()->post('routes', []);
        $routes = explode('||', $routes);
        $authManager = Yii::$app->authManager;
        foreach ($routes as $v)
        {
            if(($preModel = $authManager->getPermission($v)) !== null){
                $authManager->remove($preModel);
            }

        }

        $route = new Route();
        return $route->getRoutes();
    }

    public function actionRefresh(){
        $route = new Route();
        return $route->getRoutes();
    }
}