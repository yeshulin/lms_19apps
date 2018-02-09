<?php

namespace common\models;

use yii\behaviors\TimestampBehavior;
use Yii;
use yii\db\ActiveRecord;
/**
 * This is the model class for table "msa_question".
 *
 * @property int $qid 问题ID
 * @property string $qsign 问题标识
 * @property string $question 问题标题
 * @property string $content 问题内容
 * @property int $userid 用户ID
 * @property string $ip IP地址
 * @property int $status 问答状态0表示正常，-1表示屏蔽
 * @property int $listorder 排序
 * @property int $updatetime 更新时间
 * @property int $addtime 添加时间
 * @property int $agree 顶的次数
 */
class Question extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'msa_question';
    }

    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['addtime', 'updatetime'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updatetime'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['content'], 'string'],
            [['userid', 'status', 'listorder', 'updatetime', 'addtime', 'agree'], 'integer'],
            [['qsign'], 'string', 'max' => 100],
            [['question'], 'string', 'max' => 140],
            [['ip'], 'string', 'max' => 20],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'qid' => 'Qid',
            'qsign' => 'Qsign',
            'question' => 'Question',
            'content' => 'Content',
            'userid' => 'Userid',
            'ip' => 'Ip',
            'status' => 'Status',
            'listorder' => 'Listorder',
            'updatetime' => 'Updatetime',
            'addtime' => 'Addtime',
            'agree' => 'Agree',
        ];
    }
}
