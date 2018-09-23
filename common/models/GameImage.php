<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * Game model
 *
 * @property integer $game_id
 * @property integer $image_id
 */
class GameImage extends ActiveRecord
{
	public static function tableName()
    {
        return '{{%game_image}}';
    }
}