<?php

namespace common\models;

use yii\behaviors\TimestampBehavior;
use Yii;
use yii\db\ActiveRecord;
/**
 * This is the model class for table "msa_agree".
 *
 * @property int $id
 * @property int $userid 用户名
 * @property string $commentid 评论ID：回答和提问
 * @property int $addtime 顶的时间
 * @property int $touserid 评论所属ID
 */
class Agree extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'msa_agree';
    }


    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['addtime'],
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
            [['userid', 'commentid', 'touserid'], 'required'],
            [['userid', 'touserid'], 'integer'],
            [['commentid'], 'string', 'max' => 30],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'userid' => 'Userid',
            'commentid' => 'Commentid',
            'addtime' => 'Addtime',
            'touserid' => 'Touserid',
        ];
    }
}
