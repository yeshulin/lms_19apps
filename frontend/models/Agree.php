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
class Agree extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%agree}}';
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
            [['id', 'userid', 'created_at', 'updated_at','touserid'], 'integer'],
            [['userid','commentid'], 'required'],
            [['commentid'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '评论ID',
            'userid' => '问题ID',
            'commentid' => '答案ID',
            'created_at' => '新建时间',
            'touserid' => '顶谁',
        ];
    }
}
