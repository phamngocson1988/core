<?php
namespace website\behaviors;
use yii\behaviors\AttributeBehavior;

class GamePriceBehavior extends AttributeBehavior
{
	public function getCogs() // Cost of goods sold: giá vốn
    {
	    $owner = $this->owner; // game
        return round(array_sum([$owner->price1, $owner->price2, $owner->price3]) / 3, 1);
    }

    public function getPrice()
    {
	    $owner = $this->owner; // game
        $cogs = $owner->getCogs();
        $desired_profit = Yii::$app->settings->get('ApplicationSettingForm', 'desired_profit', 0);
        $managing_cost_rate = Yii::$app->settings->get('ApplicationSettingForm', 'managing_cost_rate', 0);
        $investing_cost_rate = Yii::$app->settings->get('ApplicationSettingForm', 'investing_cost_rate', 0);
        /** 
         * Change requirement  from Leo
         * Giá lẻ= giá thu vào + BDLN lẻ  mong muốn
         * @date 2020-05-04
         */
        // $price = ($cogs + $desired_profit) * (100 + $managing_cost_rate + $investing_cost_rate) / 100;
        $price = $cogs + $desired_profit;
        return ceil($price);
    }   

    public function getResellerPrice($level = User::RESELLER_LEVEL_1)
    {
	    $owner = $this->owner; // game
        $cogs = $owner->getCogs();
        $desired_profit = Yii::$app->settings->get('ApplicationSettingForm', 'reseller_desired_profit', 0);
        $managing_cost_rate = Yii::$app->settings->get('ApplicationSettingForm', 'managing_cost_rate', 0);
        $investing_cost_rate = Yii::$app->settings->get('ApplicationSettingForm', 'investing_cost_rate', 0);
        /** 
         * Change requirement  from Leo
         * Giá lẻ= giá thu vào + BDLN lẻ  mong muốn
         * @date 2020-05-04
         */
        // $price = ($cogs + $desired_profit) * (100 + $managing_cost_rate + $investing_cost_rate) / 100;
        $price = $cogs + $desired_profit;
        $price = ceil($price);
        if ($level == User::RESELLER_LEVEL_1) return $price + 4;
        if ($level == User::RESELLER_LEVEL_3) return $price - 1.5;
        return $price;
    }

    public function getOriginalPrice()
    {
	    $owner = $this->owner; // game
        return ($owner->original_price) ? $owner->original_price : $owner->getPrice();
    }
}
