<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use common\models\Order;
use common\models\Game;
use common\models\Product;

/**
 * OrderItems model
 */
class OrderItems extends ActiveRecord
{
    const TYPE_PRODUCT = 'product';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%order_items}}';
    }

    public function getOrder()
    {
        return $this->hasOne(Order::className(), ['id' => 'order_id']);
    }

    public function getGame()
    {
        return $this->hasOne(Game::className(), ['id' => 'game_id']);
    }

    public function getProduct()
    {
        return $this->hasOne(Product::className(), ['id' => 'product_id']);
    }

    public function getTotalPrice()
    {
        return $this->price * $this->quantity;
    }

    public function getTotalUnit()
    {
        return $this->unit * $this->quantity;
    }
}
