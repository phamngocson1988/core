<?php
namespace backend\models;

use Yii;
use backend\behaviors\GameNotificationBehavior;

class Game extends \common\models\Game
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['notification'] = GameNotificationBehavior::className();
        return $behaviors;
    }

	public static function deleteAll($condition = null, $params = [])
    {
        return static::updateAll(['status' => self::STATUS_DELETE], $condition);
    }
}