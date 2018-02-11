<?php

namespace common\models;

use yii\behaviors\TimestampBehavior;
use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "msa_comment".
 *
 * @property int $cid
 * @property int $qid
 * @property int $aid
 * @property string $content
 * @property int $userid
 * @property string $ip
 * @property int $status
 * @property int $updatetime
 * @property int $addtime
 */
class Comment extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%comment}}';
    }


    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
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
            [['qid', 'aid', 'userid', 'status', 'updated_at', 'created_at'], 'integer'],
            [['content'], 'string', 'max' => 255],
            [['ip'], 'string', 'max' => 20],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'cid' => 'Cid',
            'qid' => 'Qid',
            'aid' => 'Aid',
            'content' => 'Content',
            'userid' => 'Userid',
            'ip' => 'Ip',
            'status' => 'Status',
            'updatetime' => 'updated_at',
            'addtime' => 'created_at',
            'agree'=>'agree'
        ];
    }
}
