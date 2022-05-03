<?php
namespace api\models;

use Yii;

class OrderComplains extends \common\models\OrderComplains
{
    public function fields()
    {
        return [
            'id',
            'order_id',
            'content',
            'content_type',
            'is_read',
            'is_customer',
            'object_name',
            'ouath_sublink_client_id',
            'user_sublink_id',
            'created_at',
            'created_by',
            'is_reseller' => function ($model) {
            	return $model->created_by == Yii::$app->user->id && $model->is_customer != self::IS_CUSTOMER;
            },
        ];
    }

    public function isCustomer()
    {
        return $this->is_customer == self::IS_CUSTOMER;
    }
}