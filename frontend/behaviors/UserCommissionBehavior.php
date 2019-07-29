<?php 
namespace frontend\behaviors;

use Yii;
use yii\base\Behavior;
use frontend\models\UserCommission;

class UserCommissionBehavior extends Behavior
{
    public $id;
    
    public function canWithDraw()
    {
        $condition = Yii::$app->settings->get('AffiliateProgramForm', 'min_member');
        if (!$condition) return true;
        $command = UserCommission::find()->select('member_id')->where([
            'user_id' => $this->id,
        ])->distinct();
        return (int)$condition > $command->count();
    }
}