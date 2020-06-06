<?php 
namespace website\behaviors;

use Yii;
use website\models\UserCommission;

class UserCommissionBehavior extends \common\behaviors\UserCommissionBehavior
{
    public function canWithDraw()
    {
        $owner = $this->owner;
        $condition = Yii::$app->settings->get('AffiliateProgramForm', 'min_member');
        if (!$condition) return true;
        $command = UserCommission::find()->select('member_id')->where([
            'user_id' => $owner->id,
            'status' => UserCommission::STATUS_VALID
        ])->distinct();
        return (int)$condition > $command->count();
    }
}