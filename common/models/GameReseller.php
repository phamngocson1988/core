<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;

class GameReseller extends ActiveRecord
{
	public static function tableName()
    {
        return '{{%game_reseller}}';
    }

    public function rules()
    {
        return [
            [['game_id', 'reseller_id', 'price'], 'required'],
        ];
    }
}