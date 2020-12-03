<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;

class OperatorMeta extends ActiveRecord
{
	const KEY_PRODUCT = 'product';
	const KEY_DEPOSIT_METHOD = 'deposit_method';
	const KEY_WITHDRAWVAL_METHOD = 'withdrawal_method';
	const KEY_LICENSE = 'license';

    public static function tableName()
    {
        return '{{%operator_meta}}';
    }
}