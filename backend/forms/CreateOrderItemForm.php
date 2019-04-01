<?php
namespace backend\forms;

use Yii;
use common\models\OrderItems;
use common\models\Game;
use common\models\Product;
use yii\helpers\ArrayHelper;

class CreateOrderItemForm extends OrderItems
{
    public function rules()
    {
        return [
            [['game_id', 'product_id', 'quantity'], 'required'],
            ['game_id', 'validateGame'],
            ['product_id', 'validateProduct'],
            [['username', 'password', 'platform', 'login_method', 'character_name'], 'required'],
            [['recover_code', 'server', 'note'], 'trim'],
        ];
    }

    public function validateGame($attribute, $params)
    {
        if (!$this->game) {
            $this->addError($attribute, 'Game không tồn tại');
        }
    }

    public function validateProduct($attribute, $params)
    {
        if (!$this->product) {
            $this->addError($attribute, 'Gói game không tồn tại');
        }
    }

    public function beforeSave($insert)
    {
        if (!parent::beforeSave($insert)) {
            return false;
        }
        $this->item_title = sprintf("%s - %s", $this->game->title, $this->product->title);
        $this->type = self::TYPE_PRODUCT;
        $this->price = $this->product->price;
        $this->total = $this->product->price * $this->quantity;
        $this->unit_name = $this->game->unit_name;
        $this->unit = $this->product->unit;
        $this->total_unit = $this->product->unit * $this->quantity;
        return true;
    }
}