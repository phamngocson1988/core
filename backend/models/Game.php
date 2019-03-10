<?php
namespace backend\models;

use Yii;
use common\models\Game as BaseGame;

class Game extends BaseGame
{
	public static function deleteAll($condition = null, $params = [])
    {
        return static::updateAll(['status' => self::STATUS_DELETE], $condition);
    }
}