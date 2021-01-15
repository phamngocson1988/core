<?php
namespace frontend\widgets;

use Yii;
use yii\base\Widget;
use yii\helpers\ArrayHelper;
use frontend\models\FlashSale;
use frontend\models\FlashSaleGame;
use frontend\models\Game;

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
        // $flashsaleGames = FlashSaleGame::find()
        // ->where(['flashsale_id' => $flashsale->id])
        // ->andWhere(['>', 'remain', 0])
        // ->limit(5)->all();

        $gameTable = Game::tableName();
        $flashGameTable = FlashSaleGame::tableName();
        $flashsaleGames = FlashSaleGame::find()
        ->innerJoin($gameTable, "{$gameTable}.id = {$flashGameTable}.game_id")
        ->where(["{$flashGameTable}.flashsale_id" => $flashsale->id])
        ->andWhere(["{$gameTable}.status" => Game::STATUS_VISIBLE])
        ->andWhere(['>', "{$flashGameTable}.remain", 0])
        ->select(["{$flashGameTable}.*"])
        ->limit(5)
        ->all();

        return $this->render('flashsale', [
            'flashsale' => $flashsale,
            'flashsaleGames' => $flashsaleGames,
        ]);
    }
}