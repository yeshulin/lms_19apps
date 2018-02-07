<?php
/**
 * Created by PhpStorm.
 * User: IUOD
 * Date: 2016/7/30
 * Time: 12:14
 */

namespace frontend\controllers\api;

use frontend\models\Myfavorite;
use Yii;
use yii\filters\VerbFilter;
use frontend\models\Course;
use common\models\CourseBars;
use common\models\CourseKnows;
use common\models\CourseSections;
use frontend\models\Mycourse;
use frontend\models\Myxuecourse;
use yii\httpclient\Client;

class CourseController extends ApiController
{
    public $request;
    public $api = ['list', 'play'];
    public $allowHash = false;

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
        if ($this->isGuest())
        {
            return self::setReturn('0003', 'failed', '', '未登录，请先执行登陆操作！');
        }
        if (Yii::$app->request->isPost) {
            $this->request = Yii::$app->request->post();
        } elseif (Yii::$app->request->isGet) {
            $this->request = Yii::$app->request->get();
        } else {
            return self::setReturn('0003', 'failed', '', '请求方式非法！');
        }
    }

    public function actionIndex()
    {
        $params = $this->request;
        $type = isset($params['type']) ? strtolower((string)$params['type']) : 'list';
        if (!in_array($type, $this->api)) {
            return self::setReturn('0003', 'failed', '', '未找到请求的方法');
        }
        unset($params['type']);
        $action = '_'.$type;
        return $this->$action($params);
    }

    protected function _list($params)
    {
        $courseModel = new Mycourse();
        $courseData = $courseModel->search($params);
        return self::setReturn('0000', 'success',$courseData);
    }

    /**
     * 课程视频播放
     * @param $params
     * @return mixed
     */
    protected function _play($params)
    {
        $id = $params['id'] ? intval($params['id']) : null;
        if (is_null($id))
        {
            return self::setReturn('0002', 'failed', '', '缺少知识点ID');
        }
        $info = CourseKnows::findOne($id);
        if ($info == null)
        {
            return self::setReturn('0002', 'failed', '','没有找到当前知识点');
        }
        elseif ($info->type != CourseKnows::TYPE_VIDEO || $info->items == null)
        {
            return self::setReturn('0002', 'failed', '','该知识点下没有视频');
        }

        if (($BarsModel = CourseBars::findOne($info->barsid)) !== null)
        {
            if (($SectionModel = CourseSections::findOne($BarsModel->sectionid)) !== null)
            {
                $retuanPlayStatus = [
                    201 => '未购买该课程',
                    202 => '课程正在重审核',
                    203 => '课程已删除',
                    204 => '未找到课程',
                    205 => '未到播放权限开始时间',
                    206 => '播放权限已结束',
                ];
                $code = Course::playAuth($SectionModel->courseid);
                if ($code == 200)
                {
                    $Myxuecourse = new Myxuecourse();
                    $myCourseModel = $Myxuecourse::find()->where([
                        'courseid'=>$SectionModel->courseid,
                        'sectionid'=>$SectionModel->sectionid,
                        'barsid'=>$BarsModel->barsid,
                        'knowsid'=>$info->knowsid,
                    ])->one();
                    if ($myCourseModel == null)
                    {
                        $Myxuecourse->isNewRecord = true;
                        $Myxuecourse->courseid = $SectionModel->courseid;
                        $Myxuecourse->sectionid = $SectionModel->sectionid;
                        $Myxuecourse->barsid = $BarsModel->barsid;
                        $Myxuecourse->knowsid = $info->knowsid;
                        $Myxuecourse->save();
                        $CourseModel = Course::findOne($SectionModel->courseid);
                        $CourseModel->learnnumber = ($CourseModel->learnnumber)+1;
                        $CourseModel->_save();
                    }
                    $client = new Client();
                    $parArr = [
                        'fileurls'=>$info->items->vmsid,
                    ];
                    $url = yii::$app->params['api']['college']."/api/oss/querymedia";
                    $response = $client->createRequest()
                        ->setMethod('GET')
                        ->setUrl($url)
                        ->setData($parArr)
                        ->send();
                    if($response->isOk){
                        return self::setReturn('0000', 'success', [
                            'courseid'=>$SectionModel->courseid,"play"=>$response->data

                        ]);
                    }
//                    $Vms = new \common\components\Vms();
//                    $Play = $Vms->vmsVideoPlay($info->items->vmsid);

                }
                else {
                    return self::setReturn('0002', 'failed', '', $retuanPlayStatus[$code]);
                }
            }
            else {
                return self::setReturn('0002', 'failed', '','该知识点下不属于任何章节');
            }
        }
        else {
            return self::setReturn('0002', 'failed', '','该知识点下不属于任何小节');
        }
    }
    public function _myfavorite($params){
        $myfavoriteModel = new Myfavorite();
        $Data = $myfavoriteModel->search($params);
        return self::setReturn('0000', 'success',$Data);
    }

}