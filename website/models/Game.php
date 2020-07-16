<?php
namespace website\models;

use Yii;
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

    public function getPrice()
    {
        $flashsale = $this->getFlashSalePrice();
        if ($flashsale) return $flashsale->price;
        return parent::getPrice();
    }   

    public function getResellerPrice($level = User::RESELLER_LEVEL_1)
    {
        $flashsale = $this->getFlashSalePrice();
        if ($flashsale) return $flashsale->price;
        return parent::getPrice();
    }

    public function getFlashSalePrice() 
    {
        $now = date('Y-m-d H:i:s');
        $flashSaleTable = FlashSale::tableName();
        $flashSaleGameTable = FlashSaleGame::tableName();
        return FlashSaleGame::find()
        ->innerJoin($flashSaleTable, "{$flashSaleTable}.id = {$flashSaleGameTable}.flashsale_id")
        ->where(['<=', "{$flashSaleTable}.start_from", $now])
        ->andWhere(['>=', "{$flashSaleTable}.start_to", $now])
        ->andWhere(["{$flashSaleGameTable}.game_id" => $this->id])
        ->one();
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
