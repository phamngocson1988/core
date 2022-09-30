<?php
namespace website\components\cart;

use Yii;
use yii2mod\cart\models\CartItemInterface;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use website\models\Game;
use website\models\Promotion;
use website\models\ResellerPrice;
use common\models\CurrencySetting;

class CartItem extends Game implements CartItemInterface
{
    const SCENARIO_CALCULATE_CART = 'SCENARIO_CALCULATE_CART';
    const SCENARIO_ADD_CART = 'SCENARIO_ADD_CART';
    const SCENARIO_UPDATE_CART = 'SCENARIO_UPDATE_CART';
    const SCENARIO_BULK_CART = 'SCENARIO_BULK_CART';

    public $quantity = 1;
    public $username;
    public $password;
    public $character_name;
    public $recover_code;
    public $recover_file_id;
    public $server;
    public $note;
    public $login_method;
    public $voucher;
    public $currency;
    public $raw;
    public $bulk;

    // Saler
    public $saler_code;

    protected $_game;
    protected $_promotion;

    public function scenarios()
    {
        return [
            self::SCENARIO_CALCULATE_CART => ['id', 'quantity', 'voucher', 'currency'],
            self::SCENARIO_ADD_CART => ['id', 'quantity', 'currency'],
            self::SCENARIO_UPDATE_CART => ['id', 'quantity', 'username', 'password', 'character_name', 'login_method', 'server', 'recover_code', 'recover_file_id', 'note', 'voucher', 'saler_code'],
            self::SCENARIO_BULK_CART => ['id', 'quantity', 'raw', 'bulk'],
        ];
    }

    public function rules()
    {
        return [
            ['quantity', 'number'],
            [['server', 'note', 'recover_code', 'recover_file_id', 'voucher', 'currency', 'bulk', 'saler_code'], 'trim'],
            ['voucher', 'validateVoucher'],
            [['username', 'password', 'character_name', 'login_method'], 'required', 'on' => self::SCENARIO_UPDATE_CART],
            [['quantity', 'raw'], 'required', 'on' => self::SCENARIO_BULK_CART],
            ['quantity', 'validateQuantity'],

            ['recover_code', 'required', 'whenClient' => "function (attribute, value) {
                var loginMethod = $('#login_method').val();
                loginMethod.trim();
                if (!loginMethod) return false;
                return ['facebook', 'google'].includes(loginMethod);
            }",
            'when' =>  [$this, 'validateRecoverCode'],
            'on' => [self::SCENARIO_UPDATE_CART],
            'message' => 'Recover code is required in case you choose facebook/google'
            ],
            ['recover_code', 'match', 'pattern' => '/^\d{8}(\s\d{8})*$/i', 'on' => [self::SCENARIO_UPDATE_CART], 'message' => 'Recovery codes are invalid.'],

        ];
    }

    public function validateRecoverCode($model) 
    {
        return in_array($model->login_method, ['facebook', 'google']);
    }

    public function fetchLoginMethod()
    {
        return [
            'facebook' => 'Facebook',
            'google' => 'Google',
            'account' => 'Game Account',
            'other' => 'Other Method'
        ];
    }

    public function fetchPlatform()
    {
        return [
            'android' => 'Android',
            'ios' => 'IOS'
        ];
    }

    public function fetchCurrency()
    {
        $models = CurrencySetting::find()
        ->where(['status' => CurrencySetting::STATUS_ACTIVE])
        ->orderBy(['is_fix' => SORT_DESC])
        ->all();
        return ArrayHelper::map($models, 'code', 'code');
    }

    public function getUnit() 
    {
        return $this->pack;
    }

    public function getSubtotalUnit()
    {
        $unit = $this->getUnit();
        $quantity = $this->quantity;
        return $unit * $quantity;   
    }

    public function getTotalUnit()
    {
        $subTotal = $this->getSubtotalUnit();
        $promotion = $this->getPromotion();
        if ($promotion) {
            $promotionUnit = $promotion->apply($this->getSubtotalUnit());
            $subTotal += $promotionUnit;
        }
        return $subTotal;
    }

    public function getUnitName()
    {
        return $this->unit_name;
    }

    public function getTotalOriginalPrice()
    {
        return $this->getOriginalPrice() * $this->quantity;
    }

    public function getSubTotalPrice()
    {
        $quantity = $this->quantity;
        return $this->getPrice() * $quantity;        
    }

    public function getTotalPrice()
    {
        $sub = $this->getSubTotalPrice();
        return $sub;
    }

    /**
     * Returns the price for the cart item
     */
    public function getPrice() : int
    {
        if (Yii::$app->user->isGuest) return parent::getPrice();
        $user = Yii::$app->user->getIdentity();

        if (!$user->isReseller()) return parent::getPrice();
        else {
            $now = date('Y-m-d H:i:s');
            $resellerPrice = ResellerPrice::find()
            ->select(['price'])
            ->where([
                'reseller_id' => $user->id,
                'game_id' => $this->id
            ])->one();
            return $resellerPrice ? max(0, $resellerPrice->price + (float)$this->reseller_price_amplitude) : 0;
            // return $this->getResellerPrice($user->reseller_level);
        }
    }

    /**
     * Returns the label for the cart item (displayed in cart etc)
     *
     * @return int|string
     */
    public function getLabel() 
    {
        return $this->title;
    }

    /**
     * Returns unique id to associate cart item with product
     *
     * @return int|string
     */
    public function getUniqueId() 
    {
        return $this->id;
    }

    // Promotion
    public function getPromotion()
    {
        if (!$this->_promotion) {
            $this->_promotion = Promotion::findOne(['code' => $this->voucher]);
        }
        return $this->_promotion;
    }

    public function validateVoucher($attribute, $params)
    {
        if (!$this->voucher) return;
        $promotion = $this->getPromotion();
        if (!$promotion) {
            $this->addError($attribute, 'This voucher code is not valid');
            return;
        }
        $user = Yii::$app->user->getIdentity();
        if (!$user->phone) {
            $this->addError($attribute, 'Your account is not eligible for this promotion.');
            $this->_promotion = null;
            return;
        }
        if ($promotion->promotion_scenario != Promotion::SCENARIO_BUY_GEMS) {
            $this->addError($attribute, 'This voucher code is not valid');
            $this->_promotion = null;
            return;
        }
        if (!$promotion->canApplyForGame($this->id) && !$promotion->canApplyForUser(Yii::$app->user->id)) {
            $this->addError($attribute, 'This voucher code is not valid for this user');
            $this->_promotion = null;
            return;
        }
    }

    public function validateQuantity($attribute, $params) 
    {
        if ($this->min_quantity && $this->quantity < $this->min_quantity) {
            $message = sprintf('Action is failed. Min order should be: %s', number_format($this->min_quantity));
            $this->addError($attribute, $message);
        }
    }
}