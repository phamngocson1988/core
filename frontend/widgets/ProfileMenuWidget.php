<?php
namespace frontend\widgets;

use Yii;
use yii\base\Widget;
use frontend\models\Operator;
use frontend\models\OperatorFavorite;
use frontend\models\Complain;

class ProfileMenuWidget extends Widget
{
    public function run()
    {
        // Favorite
        $operatorTable = Operator::tableName();
        $favoriteTable = OperatorFavorite::tableName();
        $userId = Yii::$app->user->id;
        $operators = Operator::find()
        ->innerJoin($favoriteTable, "{$favoriteTable}.operator_id = {$operatorTable}.id AND {$favoriteTable}.user_id = $userId")
        ->limit(6)
        ->all();

        // Complain
        $complains = Complain::find()
        ->where([
            'user_id' => $userId,
            'status' => Complain::STATUS_OPEN,
        ])->limit(3)->all();
        return $this->render('profile-menu', [
            'user' => Yii::$app->user->getIdentity(), 
            'operators' => $operators,
            'complains' => $complains
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