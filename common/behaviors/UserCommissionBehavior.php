<?php
namespace common\behaviors;

use yii\behaviors\AttributeBehavior;
use common\models\UserCommission;
use common\models\UserCommissionWithdraw;

class UserCommissionBehavior extends AttributeBehavior
{
    public function getCommission()
    {
        $owner = $this->owner;
        return $owner->hasMany(UserCommission::className(), ['user_id' => 'id']);
    }

    public function getValidCommission()
    {
        $command = $this->getCommission();
        $command->andWhere(['status' => UserCommission::STATUS_VALID]);
        return $command;
    }

    public function getReadyCommission()
    {
        $command = $this->getValidCommission();
        return $command->andWhere(['<=', 'valid_from_date', date('Y-m-d')]);
    }

    public function getReadyCommissionTotal()
    {
        return $this->getReadyCommission()->sum('commission');
    }

    public function getPendingCommission()
    {
        $command = $this->getValidCommission();
        return $command->andWhere(['>', 'valid_from_date', date('Y-m-d')]);
    }

    public function getPendingCommissionTotal()
    {
        return $this->getPendingCommission()->sum('commission');
    }

    public function getCommissionWithdraw()
    {
        $owner = $this->owner;
        return $owner->hasMany(UserCommissionWithdraw::className(), ['user_id' => 'id']);
    }

    public function getExecutedCommissionWithdraw()
    {
        $command = $this->getCommissionWithdraw();
        return $command->andWhere(['status' => UserCommissionWithdraw::STATUS_EXECUTED]);
    }

    public function getExecutedCommissionWithdrawTotal()
    {
        return $this->getExecutedCommissionWithdraw()->sum('amount');
    }

    public function getAvailabelCommission()
    {
        $ready = $this->getReadyCommissionTotal();
        $withdraw = $this->getExecutedCommissionWithdrawTotal();
        return $ready - $withdraw;
    }
}
