<?php
namespace frontend\widgets;

use yii\base\Widget;
use frontend\models\Game;

class GameListWidget extends Widget
{
    public function run()
    {
    	$models = Game::find()->all();
        return $this->render('game_list', ['models' => $models]);
    }
}