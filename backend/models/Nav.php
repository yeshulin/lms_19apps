<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "{{%nav}}".
 *
 * @property integer $id
 * @property string $name
 * @property integer $parent
 * @property string $url
 * @property integer $order
 *
 * @property Nav $parent0
 * @property Nav[] $nav
 */
class Nav extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%nav}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name','url'],'required'],
            [['order'], 'integer'],
            [['name'], 'string', 'max' => 128],
            [['url'], 'string', 'max' => 256],
            [['parent'], 'exist', 'skipOnError' => true, 'targetClass' => Nav::className(), 'targetAttribute' => ['parent' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '名称',
            'parent' => '父级ID',
            'url' => '链接',
            'order' => '排序',
        ];
    }

    static public function getNavByid($parent = 0, $PermissionsByUser = []){
        if ($parent == 0) {
            $where = 'parent is null';
        }
        else {
            $where = ['parent'=> $parent];
        }
        $permissionNav = [];
        $NavModel = new Nav();
        $Navs = $NavModel->find()->where($where)->all();
        if (is_array($Navs)) {
            foreach ($Navs as $k => $v) {
                if (($one = $NavModel->find()->where(['parent' => $v['id']])->one()) !== null && !empty($NavsReturn = self::getNavByid($v['id'], $PermissionsByUser))) {
                    $permissionNav[$k]['label'] = $v['name'];
                    $permissionNav[$k]['url'] = '';
                    $permissionNav[$k]['items'] = self::getNavByid($v['id'], $PermissionsByUser);
                }
                else if (!empty($v['url'])) {
                    $route = trim($v['url'], '/');
                    if (strpos($route, '/')) {
                        $permission = str_replace('/', '::', $route);
                    }
                    else{
                        $permission = $route.'::*';
                    }
                    if (in_array($permission, $PermissionsByUser) || in_array('*::*', $PermissionsByUser)) {
                        $permissionNav[$k]['label'] = $v['name'];
                        $permissionNav[$k]['url'] = [$v['url']];
                    }
                }
            }
        }
        return $permissionNav;
    }


    static public function getNavByUserid($userid){
        $PermissionsByUser = array_keys(Yii::$app->authManager->getPermissionsByUser($userid));
        return self::getNavByid(0, $PermissionsByUser);
    }

    public static function getNavList($parent = 0, $NavsList = [''=>'一级导航'], $ri = 0){
        $ri ++;
        if ($parent == 0) {
            $where = 'parent is null';
        }
        else {
            $where = ['parent'=> $parent];
        }
        $Navs = self::find()->where($where)->orderBy(['order'=>SORT_DESC])->all();
        foreach ($Navs as $v) {
            $NavsList[$v['id']] = '├'.str_repeat('-', $ri*5) .$v['name'];
            if (self::find()->where(['parent'=>$v['id']])->one() !== null) {
                $NavsList = self::getNavList($v['id'], $NavsList,$ri);
            }
        }
        return $NavsList;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParent0()
    {
        return $this->hasOne(Nav::className(), ['id' => 'parent']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNavs()
    {
        return $this->hasMany(Nav::className(), ['parent' => 'id']);
    }
}
