<?php
namespace frontend\components\cart;

use yii\base\Model;
use yii2mod\cart\models\CartItemInterface;

class CartItem extends Model implements CartItemInterface
{
    public $id;

    public $quantity = 1;

    /** @var ProductOption **/
    protected $_option;
    /** @var Product **/
    protected $_product;

    public function init()
    {
        parent::init();
        $this->getOption();
        $this->getProduct();
    }

    public function rules()
    {
        return [
            ['id', 'required'],
            ['id', 'validateOption'],
            ['id', 'validateProduct']
        ];
    }

    public function validateOption($attribute, $params)
    {
        $option = $this->getOption();
        if (!$option) {
            $this->addError($attribute, 'Gói sản phẩm này không khả dụng');
            return false;
        }
    }

    protected function getOption()
    {
        if (!$this->_option) {
            $this->_option = ProductOption::findOne($this->id);
        }
        return $this->_option;
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
            $option = $this->getOption();
            if (!$option) return null;
            $this->_product = Product::findOne($option->product_id);
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

    // ============== implement interface ===========//
    public function getPrice() : int
    {
        return (int)$this->getOption()->price;
    }

    public function getLabel()
    {
        return sprintf('%s - %s', $this->getProduct()->title, $this->getOption()->title);
    }

    public function getUniqueId()
    {
        return sprintf('%d_%d', $this->getProduct()->id, $this->getOption()->id);
    }
}