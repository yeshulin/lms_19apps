<?php
/**
 * Created by PhpStorm.
 * User: IUOD
 * Date: 2016/8/16
 * Time: 18:43
 */

namespace frontend\modules\site\controllers;

use common\helpers\SEO;
use frontend\controllers\WebController;
use Yii;
use yii\helpers\Url;
use yii\httpclient\Client;
use frontend\models\ContentCatlog;
use frontend\models\Content;

class NewsController extends WebController
{
    private $newsurl;

    public function init()
    {
        parent::init();
        $baseApiUrl = Yii::$app->request->hostInfo;
        $this->newsurl = $baseApiUrl . Url::to(['/api/news']);
    }

    public function actionIndex()
    {
        $this->layout = "//main_home";
        return $this->render("//news/index");
    }

    public function actionList(){
        $catid = yii::$app->request->get('catid');
        $model = ContentCatlog::findOne($catid);
        $this->layout = "//main_home";
        return $this->render("//news/list",['model'=>$model]);
    }

    public function actionView()
    {
//        $catid = yii::$app->request->get('catid');
//        $catlog = ContentCatlog::findOne($catid);
//        var_dump($catlog);exit;
        $id = yii::$app->request->get('id');
        $model = Content::findOne($id);
        $this->layout = "//main_home";
        return $this->render("//news/view",['model'=>$model]);
    }
}
