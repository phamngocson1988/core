<?php
namespace frontend\components\cart;

use yii2mod\cart\models\CartItemInterface;
use common\models\Game;
use common\models\Product;

class CartItem extends Game implements CartItemInterface
{
    public $product_id;
    public $quantity;
    public $username;
    public $password;
    public $character_name;
    public $recover_code;
    public $server;
    public $note;
    public $platform;
    public $login_method;

    const SCENARIO_ADD = 'add';
    const SCENARIO_EDIT = 'edit';

    /** @var Product **/
    protected $_product;

    public function scenarios()
    {
        return [
            self::SCENARIO_ADD => ['id', 'product_id', 'quantity'],
            self::SCENARIO_EDIT => ['product_id', 'quantity', 'username', 'password', 'character_name', 'platform', 'login_method', 'server', 'recover_code', 'note'],
        ];
    }

    public function rules()
    {
        return [
            [['id', 'product_id', 'quantity'], 'required', 'on' => self::SCENARIO_ADD],
            [['product_id', 'quantity', 'username', 'password', 'character_name', 'platform', 'login_method'], 'required', 'on' => self::SCENARIO_EDIT],
            [['server', 'recover_code', 'note'], 'trim', 'on' => self::SCENARIO_EDIT],
            ['product_id', 'validateProduct'],
        ];
    }

    public function validateProduct($attribute, $params)
    {
        $product = $this->getProduct();
        if (!$product) {
            $this->addError($attribute, 'Không tìm thấy gói sản phẩm');
        }
    }

    public function getProduct()
    {
        if (!$this->_product) {
            $this->_product = Product::findOne($this->product_id);
        }
        return $this->_product;
    }

    public function setQuantity($num)
    {
        $this->quantity = max(0, (int)$num);
    }

    public function increase()
    {
        $this->setQuantity($this->quantity++);
    }

    public function descrease()
    {
        $this->setQuantity($this->quantity--);
    }

    public function getTotalPrice()
    {
        return $this->getPrice() * $this->quantity;
    }

    public function getUnitName()
    {
        return $this->unit_name;
    }

    public function getUnitGame()
    {
        return $this->_product->unit;
    }

    public function getTotalUnitGame()
    {
        return $this->_product->unit * $this->quantity;
    }

    public function getGameId()
    {
        return $this->id;
    }

    // ============== implement interface ===========//
    public function getPrice() : int
    {
        $product = $this->getProduct();
        if (!$product) return 0;
        return (int)$product->price;
    }

    public function getLabel()
    {
        return $this->title;
    }

    public function getUniqueId()
    {
        return $this->id;
    }
}