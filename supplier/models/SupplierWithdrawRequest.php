<?php
namespace supplier\models;

use Yii;

class SupplierWithdrawRequest extends \common\models\SupplierWithdrawRequest
{
    public function getStatusLabel($format = '<span class="label label-%s">%s</span>')
    {
        $list = [
            self::STATUS_REQUEST => 'warning',
            self::STATUS_APPROVE => 'info',
            self::STATUS_DONE => 'primary',
            self::STATUS_CANCEL => 'default',
        ];
        $labels = self::getStatusList();
        $color = $list[$this->status];
        $label = $labels[$this->status];
        return sprintf($format, $color, $label);
    }
}
