<?php
namespace frontend\widgets;

use Yii;
use yii\base\Widget;
use frontend\models\Operator;
use frontend\models\OperatorFavorite;

class ProfileMenuWidget extends Widget
{
    public function run()
    {
        // $this->registerClientScript();
        // Favorite
        $operatorTable = Operator::tableName();
        $favoriteTable = OperatorFavorite::tableName();
        $userId = Yii::$app->user->id;
        $operators = Operator::find()
        ->innerJoin($favoriteTable, "{$favoriteTable}.operator_id = {$operatorTable}.id AND {$favoriteTable}.user_id = $userId")
        ->limit(6)
        ->all();
        return $this->render('profile-menu', [
            'user' => Yii::$app->user->getIdentity(), 
            'operators' => $operators
        ]);
    }

    protected function getScriptCode()
    {
        return "";
    }

    protected function registerClientScript()
    {
        $js = [];
        $view = $this->getView();
        $js = $this->getScriptCode();
        $view->registerJs($js);
    }


}