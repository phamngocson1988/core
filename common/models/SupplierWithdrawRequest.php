<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;

class SupplierWithdrawRequest extends ActiveRecord
{
    CONST STATUS_REQUEST = "request";
    const STATUS_APPROVE = "approve";
    const STATUS_EXECUTE = "execute";
    const STATUS_CANCEL = "cancel";

    public static function tableName()
    {
        return '{{%supplier_withdraw_request}}';
    }
}
