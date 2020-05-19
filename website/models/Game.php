<?php
namespace website\models;

use yii\db\ActiveQuery;

class Game extends \common\models\Game
{
	public static function find()
	{
		return new GameQuery(get_called_class());
	}

    public function getImageUrl($size = null, $default = '/images/post-item01.jpg')
    {
        $image = $this->image;
        if ($image) {
            return $image->getUrl($size);
        }
        return $default;
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
