<?php
namespace frontend\forms;

use Yii;
use common\models\User;
use frontend\models\Game;
use yii\helpers\ArrayHelper;

class EditUserForm extends User
{
    public function rules()
    {
        return [
            ['name', 'required'],
            [['name', 'country_code', 'phone', 'address', 'birthday', 'favorite'], 'trim'],
        ];
    }

    public function fetchGames()
    {
        $games = Game::find()->all();
        return ArrayHelper::map($games, 'id', 'title');
    }
}

