<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;

class BankAccountRole extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%bank_account_role}}';
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => 'updated_at',
                'value' => date('Y-m-d H:i:s')
            ],
            [
                'class' => BlameableBehavior::className(),
                'createdByAttribute' => 'created_by',
                'updatedByAttribute' => 'updated_by',
            ],
        ];
    }

    public function getBank()
    {
        return $this->hasOne(Bank::className(), ['id' => 'bank_id'])
            ->viaTable(BankAccount::tableName(), ['id' => 'bank_account_id']);
    }

    public function getBankAccount()
    {
        return $this->hasOne(BankAccount::className(), ['id' => 'bank_account_id']);
        
    }

    public function getRole()
    {
        $auth = Yii::$app->authManager;
        return $auth->getRole($this->role_id);
    }
}
