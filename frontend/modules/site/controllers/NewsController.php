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
        $data = [];
        return $this->render("//news/index", ['list' => $data]);
    }

    public function actionView()
    {
        $type = Yii::$app->request->get('type');
        $allow = ['class', 'school', 'platform'];
        if (!in_array($type, $allow)) {
            echo "错误的类型";
            return;
        }

        $client = new Client([
            'transport' => 'yii\httpclient\CurlTransport',
        ]);
        $id = Yii::$app->request->get('id');
        $parArr = [
            'method' => 'live',
            'type' => 'list',
        ];
        $response = $client->createRequest()
            ->setMethod('post')
            ->setUrl($this->goodurl)
            ->setData($parArr)
            ->send();
        if ($response->isOk) {
            if ($response->data['code'] == "0000") {
                $data = $response->data['data'];
                foreach ($data['data'] as $key => $value) {
                    if($value['live']['type'] == $type){
                        $request[] = $value;
                    }
                }
            } else {
                echo $response->data['error'];
            }
        }

        //var_dump($request);

        $this->layout = "//main_home";
        return $this->render("//live/view_" . $type, ['list' => $request]);
    }
}
