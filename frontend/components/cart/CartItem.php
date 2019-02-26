<?php
namespace frontend\components\cart;

use yii\base\Model;
use yii2mod\cart\models\CartItemInterface;
use frontend\models\ProductOption;
use frontend\models\Product;

class CartItem extends Model implements CartItemInterface
{
    public $id;

    public $quantity;

    public $username;
    public $password;
    public $character_name;
    public $recover_code;
    public $server;
    public $note;
    /** @var Product **/
    protected $_product;

    public function init()
    {
        parent::init();
        $this->getProduct();
    }

    public function rules()
    {
        return [
            [['id', 'quantity', 'username', 'password', 'character_name'], 'required'],
            ['id', 'validateProduct'],
            [['server', 'recover_code', 'note'], 'trim']
        ];
    }

    public function validateProduct($attribute, $params)
    {
        $product = $this->getProduct();
        if (!$product) {
            $this->addError($attribute, 'Sản phẩm này không khả dụng');
            return false;
        }
    }

    protected function getProduct()
    {
        if (!$this->_product) {
            $this->_product = Product::findOne($this->id);
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

    public function getGame()
    {
        return $this->getProduct()->game;
    }

    // ============== implement interface ===========//
    public function getPrice() : int
    {
        return (int)$this->getProduct()->price;
    }

    public function getLabel()
    {
        return sprintf('%s', $this->getProduct()->title);
    }

    public function getUniqueId()
    {
        return sprintf('%d', $this->getProduct()->id);
    }
}