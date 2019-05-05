<?php
namespace backend\models;

use Yii;

class Game extends \common\models\Game
{
    const SCENARIO_CREATE = 'create';
    const SCENARIO_EDIT = 'edit';

    public function scenarios()
    {
        return [
            self::SCENARIO_CREATE => ['title', 'excerpt', 'content', 'unit_name', 'status', 'image_id', 'price'],
            self::SCENARIO_EDIT => ['id', 'excerpt', 'title', 'content', 'unit_name', 'status', 'image_id', 'price'],
        ];
    }

    public function rules()
    {
        return [
            ['id', 'required', 'on' => self::SCENARIO_EDIT],
            [['title', 'content', 'unit_name', 'price'], 'required'],
            ['status', 'default', 'value' => self::STATUS_VISIBLE],
            [['image_id', 'excerpt'], 'safe']
        ];
    }

	public static function deleteAll($condition = null, $params = [])
    {
        return static::updateAll(['status' => self::STATUS_DELETE], $condition);
    }
}