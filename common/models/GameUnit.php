<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;

class GameUnit extends ActiveRecord
{
	public static function tableName()
    {
        return '{{%game_price}}';
    }

    public function rules()
    {
        return [
            [['game_id', 'quantity', 'unit'], 'required'],
            ['quantity', 'number', 'min' => 0.5],
            ['unit', 'integer', 'min' => 1],
        ];
    }
}