<?php

namespace backend\controllers;



use Yii;
use yii\filters\VerbFilter;
use yii\httpclient\Client;
use yii\web\Controller;


/**
 * OrderController implements the CRUD actions for Order model.
 */
class OssController extends Controller
{
    private $ossurl;
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

    public function init(){
        $this->ossurl = yii::$app->params["api"]["college"]."/api/oss/get-sign";
        parent::init();
    }

    /**
     * Lists all Order models.
     * @return mixed
     */
    public function actionIndex()
    {

    }

    /**
     * Displays a single Order model.
     * @param string $id
     * @return mixed
     */
    public function actionUpload()
    {
        $client = new Client();
        $response = $client->createRequest()
                 ->setMethod('GET')
                 ->setUrl($this->ossurl)
                 ->send();
        if($response->isOk){
            return $this->render('upload',['ossdata'=>$response->data]);
        }

    }

}
