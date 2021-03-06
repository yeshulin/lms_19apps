<?php

namespace frontend\models;

use Yii;
use yii\behaviors\TimestampBehavior;
/**
 * This is the model class for table "{{%content}}".
 *
 * @property string $id
 * @property integer $catid
 * @property string $title
 * @property string $thumb
 * @property string $keywords
 * @property string $description
 * @property string $url
 * @property integer $order
 * @property integer $status
 * @property string $username
 * @property string $inputtime
 * @property string $updatetime
 * @property integer $videoPath
 * @property integer $content
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
            TimestampBehavior::className(),
        ];
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'qid','aid', 'userid', 'created_at', 'updated_at', 'status'], 'integer'],
            [['qid','aid','content','userid'], 'required'],
            [['content'], 'string'],
            [['content'], 'string', 'max' => 300],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '评论ID',
            'qid' => '问题ID',
            'aid' => '答案ID',
            'content' => '内容',
            'userid' => '回答人',
            'ip' => 'IP',
            'status' => '状态',
            'updated_at' => '修改时间',
            'created_at' => '新建时间',
            'agree' => '顶的次数',
        ];
    }
}
