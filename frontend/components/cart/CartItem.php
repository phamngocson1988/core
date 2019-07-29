<?php
namespace frontend\components\cart;

use yii2mod\cart\models\CartItemInterface;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use frontend\models\Game;

class CartItem extends Game implements CartItemInterface
{
    public $quantity;
    public $username;
    public $password;
    public $character_name;
    public $recover_code;
    public $server;
    public $note;
    public $platform;
    public $login_method;

    public $reception_email;

    const SCENARIO_ADD_CART = 'add_cart';
    const SCENARIO_EDIT_CART = 'edit_cart';
    const SCENARIO_INFO_CART = 'info_cart';
    const SCENARIO_RECEPTION_CART = 'reception_cart';
    const SCENARIO_IMPORT_CART = 'import_cart';

    // public function behaviors()
    // {
    //     return [
    //         CartItemImportBehavior::className(),
    //     ];
    // }
    public function init()
    {
        $this->quantity = ($this->quantity > 0) ? $this->quantity : 1;
    }

    public function scenarios()
    {
        return [
            self::SCENARIO_ADD_CART => ['id', 'quantity'],
            self::SCENARIO_EDIT_CART => ['id', 'quantity'],
            self::SCENARIO_INFO_CART => ['id', 'username', 'password', 'character_name', 'platform', 'login_method', 'server', 'recover_code', 'note'],
            self::SCENARIO_IMPORT_CART => ['id', 'quantity', 'username', 'password', 'character_name', 'platform', 'login_method', 'server', 'recover_code', 'note'],
            self::SCENARIO_RECEPTION_CART => ['id', 'reception_email']
        ];
    }

    public function rules()
    {
        return [
            [['id'], 'required'],
            [['quantity'], 'required', 'on' => [self::SCENARIO_EDIT_CART, self::SCENARIO_ADD_CART, self::SCENARIO_IMPORT_CART]],
            ['quantity', 'number'],

            [['username', 'password', 'character_name', 'platform'], 'required', 'on' => [self::SCENARIO_INFO_CART, self::SCENARIO_IMPORT_CART]],
            [['server', 'note', 'login_method', 'recover_code'], 'trim', 'on' => [self::SCENARIO_INFO_CART, self::SCENARIO_IMPORT_CART]],
            ['recover_code', 'validateRecoverCode', 'on' => [self::SCENARIO_INFO_CART, self::SCENARIO_IMPORT_CART]],
            ['recover_code', 'required', 'whenClient' => "function (attribute, value) {
                return $('#login_method').val();
            }", 'on' => [self::SCENARIO_INFO_CART, self::SCENARIO_IMPORT_CART],
            'message' => 'Recover code is required in case you choose facebook/google'
            ],
            ['recover_code', 'match', 'pattern' => '/^\d{8}\d*(\s\d{8}\d*)*$/i', 'on' => [self::SCENARIO_INFO_CART, self::SCENARIO_IMPORT_CART]],

            ['reception_email', 'required', 'on' => self::SCENARIO_RECEPTION_CART],

        ];
    }

    public function validateRecoverCode($attribute, $params = [])
    {
        if ($this->login_method && !$this->recover_code) {
            $this->addError($attribute, 'Recover code is required in case you choose facebook/google');
        }
    }

    public function getTotalPrice()
    {
        return $this->getPrice() * (float)$this->quantity;
    }

    public function getTotalOriginalPrice()
    {
        return $this->getOriginalPrice() * (float)$this->quantity;
    }

    public function getTotalUnit()
    {
        $pack = $this->pack;
        $quantity = $this->quantity;
        if ($this->gameUnits) {
            foreach ($this->gameUnits as $unit) {
                if ($this->quantity == $unit->quantity) return $unit->unit;
            }
        }
        return $pack * $this->quantity;
    }

    // ============== implement interface ===========//
    public function getPrice() : int
    {
        return (int)$this->price;
    }

    public function getLabel()
    {
        return $this->title;
    }

    public function getUniqueId()
    {
        return 'item' . $this->id;
    }
}