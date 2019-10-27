<?php 
namespace frontend\behaviors;

use Yii;
use frontend\models\UserCommission;

class UserCommissionBehavior extends \common\behaviors\UserCommissionBehavior
{
    public function canWithDraw()
    {
        $owner = $this->owner;
        $condition = Yii::$app->settings->get('AffiliateProgramForm', 'min_member');
        if (!$condition) return true;
        $command = UserCommission::find()->select('member_id')->where([
            'user_id' => $owner->id,
        ])->distinct();
        return (int)$condition > $command->count();
    }
}