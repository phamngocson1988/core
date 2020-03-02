<?php
namespace frontend\components\cart;

use Yii;
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
    public $raw;
    public $reception_email;
    /* Saler */
    public $saler_code;

    const SCENARIO_ADD_CART = 'add_cart';
    const SCENARIO_EDIT_CART = 'edit_cart';
    const SCENARIO_INFO_CART = 'info_cart';
    const SCENARIO_RECEPTION_CART = 'reception_cart';
    const SCENARIO_IMPORT_CART = 'import_cart';
    const SCENARIO_IMPORT_RAW = 'import_raw';

    public static $quantites = [
        '0.5' => 0.5, 
        1 => 1, 
        '1.5' => 1.5, 
        2 => 2, 
        '2.5' => 2.5, 
        3 => 3, 
        '3.5' => 3.5, 
        4 => 4, 
        '4.5' => 4.5, 
        5 => 5, 
        '5.5' => 5.5, 
        6 => 6, 
        '6.5' => 6.5, 
        7 => 7, 
        '7.5' => 7.5, 
        8 => 8, 
        '8.5' => 8.5, 
        9 => 9, 
        '9.5' => 9.5, 
        10 => 10, 
        '10.5' => 10.5, 
        11 => 11, 
        '11.5' => 11.5, 
        12 => 12, 
        '12.5' => 12.5, 
        13 => 13, 
        '13.5' => 13.5, 
        14 => 14,
        '14.5' => 14.5,
        15 => 15,
        '15.5' => 15.5,
        16 => 16,
        '16.5' => 16.5,
        17 => 17,
        '17.5' => 17.5,
        18 => 18,
        '18.5' => 18.5,
        19 => 19,
        '19.5' => 19.5,
        20 => 20,
        25 => 25,
        30 => 30,
        35 => 35,
        40 => 40,
        45 => 45,
        50 => 50,
    ];

    public function init()
    {
        $this->quantity = ($this->quantity > 0) ? $this->quantity : 1;
        $this->platform = ($this->platform) ? $this->platform : 'android';
    }

    public function scenarios()
    {
        return [
            self::SCENARIO_ADD_CART => ['id', 'quantity'],
            self::SCENARIO_EDIT_CART => ['id', 'quantity'],
            self::SCENARIO_INFO_CART => ['id', 'username', 'password', 'character_name', 'platform', 'login_method', 'server', 'recover_code', 'note', 'saler_code'],
            self::SCENARIO_IMPORT_CART => ['id', 'quantity', 'username', 'password', 'character_name', 'platform', 'login_method', 'server', 'recover_code', 'note'],
            self::SCENARIO_RECEPTION_CART => ['id', 'reception_email'],
            self::SCENARIO_IMPORT_RAW => ['id', 'raw', 'quantity'],
        ];
    }

    public function rules()
    {
        return [
            [['id'], 'required'],
            [['quantity'], 'required', 'on' => [self::SCENARIO_EDIT_CART, self::SCENARIO_ADD_CART, self::SCENARIO_IMPORT_CART, self::SCENARIO_IMPORT_RAW]],
            ['quantity', 'number'],

            [['username', 'password', 'character_name', 'platform'], 'required', 'on' => [self::SCENARIO_INFO_CART, self::SCENARIO_IMPORT_CART]],
            [['server', 'note', 'login_method', 'recover_code'], 'trim', 'on' => [self::SCENARIO_INFO_CART, self::SCENARIO_IMPORT_CART]],
            ['recover_code', 'required', 'whenClient' => "function (attribute, value) {
                return $('#login_method').val() != 'account';
                return ['facebook', 'google'].includes($('#login_method').val());
            }",
            'when' =>  [$this, 'validateRecoverCode'],
            'on' => [self::SCENARIO_INFO_CART, self::SCENARIO_IMPORT_CART],
            'message' => 'Recover code is required in case you choose facebook/google'
            ],
            ['recover_code', 'match', 'pattern' => '/^\d{8}(\s\d{8})*$/i', 'on' => [self::SCENARIO_INFO_CART, self::SCENARIO_IMPORT_CART], 'message' => 'Recovery codes are invalid.'],

            ['reception_email', 'required', 'on' => self::SCENARIO_RECEPTION_CART],
            ['saler_code', 'trim', 'on' => self::SCENARIO_INFO_CART],
            ['raw', 'required', 'on' => self::SCENARIO_IMPORT_RAW],
        ];
    }

    public function validateRecoverCode($model) 
    {
        return in_array($model->login_method, ['facebook', 'google']);
    }

    public function getTotalPrice()
    {
        return $this->getPrice() * (float)$this->quantity;
    }

    public function getPrice()
    {
        if (Yii::$app->user->isGuest) return parent::getPrice();
        $user = Yii::$app->user->getIdentity();
        if (!$user->isReseller()) return parent::getPrice();
        return $this->getResellerPrice($user->reseller_level);
    }

    public function getTotalOriginalPrice()
    {
        return $this->getOriginalPrice() * (float)$this->quantity;
    }

    public function getTotalUnit()
    {
        $pack = $this->pack;
        $quantity = $this->quantity;
        return $pack * $quantity;
    }

    // ============== implement interface ===========//
    // public function getPrice()
    // {
    //     // return (int)$this->price;
    //     return $this->getPrice();
    // }

    public function getLabel()
    {
        return $this->title;
    }

    public function getUniqueId()
    {
        return 'item' . $this->id;
    }
}