<?php
namespace reseller\components\cart;

use Yii;
use reseller\models\GameReseller;

class CartItemReseller extends CartItem
{
	public $row_index;
    public $no;

    // public function getPrice()
    // {
    // 	$model = GameReseller::findOne([
    // 		'game_id' => $this->id,
    // 		'reseller_id' => Yii::$app->user->id,
    // 	]);
    // 	if ($model) {
    // 		return (int)$model->price;
    // 	}
    //     return (int)$this->price;
    // }
}