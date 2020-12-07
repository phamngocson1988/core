<?php
namespace api\models;

use Yii;
use yii\helpers\ArrayHelper;
use yii\db\ActiveQuery;

class Game extends \common\models\Game
{
	public static function find()
	{
		return new GameQuery(get_called_class());
	}

	public function fields()
    {
        return [
            'id',
            'title',
            'short_title',
            'excerpt',
            'content',
            'pack',
            'unit_name',
            'soldout',
            'price',
            'original_price',
            'reseller_price' => function ($model) {
            	if (!Yii::$app->user->isGuest) {
            		$user = Yii::$app->user->identity;
	                return $model->getResellerPrice($user->reseller_level);
            	}
            	return $model->getPrice();
            },
            'categories' => function($model) {
            	return ArrayHelper::getColumn($model->categories, 'name');
            },
            'image' => function ($model) {
                return $model->getImageUrl();
            },
        ];
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