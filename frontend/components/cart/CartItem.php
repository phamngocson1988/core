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

    public $account_username;

    public $account_password;

    public $account_note;

    /** @var ProductOption **/
    protected $_option;
    /** @var Product **/
    protected $_product;

    const SCENARIO_ADD_ITEM = 'add_item';
    const SCENARIO_ADD_INFOR = 'add_infor';

    public function init()
    {
        parent::init();
        $this->getOption();
        $this->getProduct();
    }

    public function scenarios()
    {
        return [
            self::SCENARIO_ADD_ITEM => ['id', 'quantity'],
            self::SCENARIO_ADD_INFOR => ['id', 'quantity', 'account_username', 'account_password', 'account_note'],
        ];
    }

    public function rules()
    {
        return [
            ['id', 'required', 'on' => self::SCENARIO_ADD_ITEM],
            ['id', 'validateOption', 'on' => self::SCENARIO_ADD_ITEM],
            ['id', 'validateProduct', 'on' => self::SCENARIO_ADD_ITEM],
            [['account_username', 'account_password', 'account_note'], 'trim', 'on' => self::SCENARIO_ADD_INFOR],
            [['account_username', 'account_password'], 'required', 'on' => self::SCENARIO_ADD_INFOR],
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