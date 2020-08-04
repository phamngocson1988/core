<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;

class GameGroup extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%game_group}}';
    }

    public function getMethods() 
    {
    	$ids = explode(",", $this->method);
    	if (!count($ids)) return [];
    	return GameMethod::find()->where(['in', 'id', $ids])->all();
    }

    public function getVersions() 
    {
    	$ids = explode(",", $this->version);
    	if (!count($ids)) return [];
    	return GameVersion::find()->where(['in', 'id', $ids])->all();
    }

    public function getPackages() 
    {
    	return $this->hasMany(GamePackage::className(), ['group_id' => 'id']);
    }
}