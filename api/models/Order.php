<?php
namespace api\models;

use Yii;
use api\behaviors\OrderNotificationBehavior;
use api\behaviors\OrderComplainBehavior;

class Order extends \common\models\Order
{
	public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['notification'] = OrderNotificationBehavior::className();
        $behaviors['complain'] = OrderComplainBehavior::className();
        return $behaviors;
    }

    public function getGame()
    {
        return $this->hasOne(Game::className(), ['id' => 'game_id']);
    }

    public function fields()
    {
        return [
            'id',
            'payment_method',
            'price',
            'quantity',
            'doing_unit',
            'total_unit',
            'total_price',
            'status',
            'created_at',
            'completed_at',
            'username',
			'password',
			'character_name',
			'recover_code',
			'server',
			'note',
			'login_method',
            'order_from _sublink',
            'game' => function ($model) {
            	return $model->game;
            },
        ];
    }
}