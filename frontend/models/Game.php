<?php
namespace frontend\models;

use yii\db\ActiveQuery;

class Game extends \common\models\Game
{
	public static function find()
	{
		return new GameQuery(get_called_class());
	}

	public function findAvailablePromotions($promotions)
	{
		$gameId = $this->id;
		$forGames = array_filter($promotions, function($promotion) use ($gameId) {
			return $promotion->canApplyGame($gameId);
		});
		return $forGames;
	}

	public function findTheBestPromotion($promotions)
	{
		$forGames = $this->findAvailablePromotions($promotions);
		if (empty($forGames)) return null;
		$quantity = 0.5;
		$amount = $this->price * $quantity;
		usort($forGames, function($p1, $p2) use ($amount) {
			$a1 = $p1->calculateBenifit($amount);
			$a2 = $p2->calculateBenifit($amount);
			if ($a1 == $a2) return 0;
		    return ($a1 < $a2) ? -1 : 1;
		});
		return reset($forGames);
	}

    public function getImageUrl($size = null, $default = '/images/no-img.png')
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
