<?php
namespace frontend\widgets;

use yii\base\Widget;
use frontend\models\Game;
use frontend\models\Promotion;

class GameListWidget extends Widget
{
    public function run()
    {
    	$models = Game::find()->all();
    	$promotions = Promotion::findValid()->all();
        return $this->render('game_list', [
        	'models' => $models,
        	'promotions' => $promotions
        ]);
    }
}