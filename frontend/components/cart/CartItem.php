<?php
namespace frontend\components\cart;

use yii\base\Model;
use yii2mod\cart\models\CartItemInterface;
use frontend\models\ProductOption;
use frontend\models\Product;

class CartItem extends Model implements CartItemInterface
{
    public $id;

    public $quantity = 1;

    /** @var Product **/
    protected $_product;

    const SCENARIO_ADD_ITEM = 'add_item';
    const SCENARIO_ADD_INFOR = 'add_infor';

    public function init()
    {
        parent::init();
        $this->getProduct();
    }

    public function scenarios()
    {
        return [
            self::SCENARIO_ADD_ITEM => ['id', 'quantity'],
            self::SCENARIO_ADD_INFOR => ['id', 'quantity'],
        ];
    }

    public function rules()
    {
        return [
            ['id', 'required', 'on' => self::SCENARIO_ADD_ITEM],
            ['id', 'validateProduct', 'on' => self::SCENARIO_ADD_ITEM],
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