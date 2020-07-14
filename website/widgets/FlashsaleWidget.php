<?php
namespace website\widgets;

use Yii;
use yii\base\Widget;
use yii\helpers\ArrayHelper;
use website\models\FlashSale;
use website\models\FlashSaleGame;

class FlashsaleWidget extends Widget
{
    public function run()
    {
        $now = date('Y-m-d H:i:s');
        $flashsale = FlashSale::find()
        ->where(['<=', 'start_from', $now])
        ->andWhere(['>=', 'start_to', $now])
        ->one();
        if (!$flashsale) return '';
        $flashsaleGames = FlashSaleGame::find()
        ->where(['flashsale_id' => $flashsale->id])
        ->andWhere(['>', 'remain', 0])
        ->limit(5)->all();
        return $this->render('flashsale', [
            'flashsale' => $flashsale,
            'flashsaleGames' => $flashsaleGames,
        ]);
    }
}