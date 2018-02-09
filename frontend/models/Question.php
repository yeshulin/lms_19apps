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
class Question extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%question}}';
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
            [['id', 'userid', 'touserid', 'created_at', 'updated_at', 'status'], 'integer'],
            [['qsign','question'], 'required'],
            [['question','content'], 'string'],
            [['question'], 'string', 'max' => 50],
            [['content'], 'string', 'max' => 300],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '问题ID',
            'qsign' => '所属标识',
            'question' => '标题',
            'content' => '内容',
            'userid' => '提问人',
            'touserid' => '问题所属人',
            'ip' => 'IP',
            'status' => '状态',
            'listorder' => '排序',
            'updated_at' => '修改时间',
            'created_at' => '新建时间',
            'agree' => '顶的次数',
        ];
    }
}
