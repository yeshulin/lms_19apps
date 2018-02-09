<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
/**
 * This is the model class for table "msa_answer".
 *
 * @property int $aid
 * @property int $qid
 * @property string $content
 * @property int $userid
 * @property string $ip
 * @property int $status
 * @property int $updatetime
 * @property int $addtime
 * @property int $agree 顶的次数
 */
class Answer extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'msa_answer';
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
            [['qid', 'userid', 'status', 'updatetime', 'addtime', 'agree'], 'integer'],
            [['content'], 'string'],
            [['ip'], 'string', 'max' => 20],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'aid' => 'Aid',
            'qid' => 'Qid',
            'content' => 'Content',
            'userid' => 'Userid',
            'ip' => 'Ip',
            'status' => 'Status',
            'updatetime' => 'Updatetime',
            'addtime' => 'Addtime',
            'agree' => 'Agree',
        ];
    }
}
