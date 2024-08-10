<?php
namespace api\models;

use Yii;
use api\behaviors\OrderNotificationBehavior;
use api\behaviors\OrderComplainBehavior;
use common\models\OrderFile;

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
            'payment_token',
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
			'raw',
            'bulk',
            'order_from_sublink',
            'reseller_id',
            'game' => function ($model) {
            	return $model->game;
            },
            'evidence_before' => function($model) {
                $images = $model->getEvidencesByType(OrderFile::TYPE_EVIDENCE_BEFORE);
                return array_map(function($image) {
                    return $image->getUrl();
                }, $images);
            },
            'evidence_after' => function($model) {
                $images = $model->getEvidencesByType(OrderFile::TYPE_EVIDENCE_AFTER);
                return array_map(function($image) {
                    return $image->getUrl();
                }, $images);
            },
        ];
    }
}