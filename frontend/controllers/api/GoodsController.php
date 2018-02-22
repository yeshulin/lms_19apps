<?php
/**
 * Created by PhpStorm.
 * User: IUOD
 * Date: 2016/7/26
 * Time: 12:58
 */

namespace frontend\controllers\api;

use common\models\Course;
use common\models\Lab;
use common\models\Live;
use common\models\Practical;
use frontend\models\Goods;
use Yii;
use yii\filters\VerbFilter;

class GoodsController extends ApiController
{
    public $request;
    public $api = [
        'course'=>Goods::TYPE_COURSE,
        'live'=>Goods::TYPE_LIVE,
        'lab'=>Goods::TYPE_LAB,
        'practical'=>Goods::TYPE_PRACTICAL,
        'vip' => Goods::TYPE_ZYRZ,
        'all'=>-1,
    ];

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
        if (Yii::$app->request->isPost)
        {
            $this->request = Yii::$app->request->post();
        }
        elseif (Yii::$app->request->isGet)
        {
            $this->request = Yii::$app->request->get();
        }
        else {
            return self::setReturn('0003', 'failed', '', '请求方式非法！');
        }
    }

    public function actionIndex()
    {
        $params = $this->request;

        $type = isset($params['type']) ? strtolower((string) $params['type']) : 'list';
        if (!in_array($type, ['list', 'view'])) {
            return self::setReturn('0003', 'failed', '', '未找到请求的方法');
        }
        $action = '_'.$type;
        return $this->$action($params);
    }


    /**
     * 获取商品列表
     * @param $params
     * @return mixed
     */
    protected function _list($params)
    {
        $method = strtolower((string) $params['method']);
        if (empty($params['method']) || !in_array($method, array_keys($this->api))) {
            return self::setReturn('0003', 'failed', '', '非法请求数据');
        }
        unset($params['type'], $params['method']);
        if ($method !== 'all')
        {
            $params['type'] = $this->api[$method];
        }
        $goodsModel = new Goods();
        $goodsData = $goodsModel->search($params);
        return self::setReturn('0000', 'success',$goodsData);
    }

    /**
     * 获取单个商品的全部信息
     * @param $params
     * @return mixed
     */
    protected function _view($params)
    {
        $id = $params['id'] ? intval($params['id']) : null;
        if (is_null($id))
        {
            return self::setReturn('0002', 'failed', '', '缺少商品ID');
        }
        else if (($findModel = Goods::findOne($id)) !== null)
        {
            if ($findModel->status == Goods::STATUS_DEFAULT)
            {
                return self::setReturn('0000', 'success',$this->getGoodsData($findModel));
            }
            return self::setReturn('0002', 'failed', '','商品已下架');
        }
        else {
            return self::setReturn('0002', 'failed', '','没有找到商品数据');
        }
    }

    /**
     * 获取商品的关联信息
     * @param $findModel
     * @return mixed
     */
    protected function getGoodsData($findModel)
    {
        $goodsTables = Goods::tableName();
        $query = Goods::find()->where([$goodsTables.'.goods_id'=>$findModel->goods_id]);
        $query->joinWith(['goodsAttr']);
        $query = Goods::leftJoinByQueryType($query, $findModel->type);

        return $query->asArray(true)->one();
    }

}