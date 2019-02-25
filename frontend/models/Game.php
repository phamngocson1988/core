<?php
namespace frontend\models;

use yii\db\ActiveQuery;

class Game extends \common\models\Game
{
	public static function find()
	{
		return new GameQuery(get_called_class());
	}
}

class GameQuery extends ActiveQuery
{
    public function init()
    {
        $this->andOnCondition(['status' => Game::STATUS_VISIBLE]);
        parent::init();
    }
}
