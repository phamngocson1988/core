<?php
namespace backend\models;

use Yii;
use common\models\User as CommonUser;

/**
 * @property string $password
 */
class User extends CommonUser
{
	const SCENARIO_CREATE = 'create';
    const SCENARIO_EDIT = 'edit';
    const SCENARIO_UPDATE_SALER_CODE = 'update_saler_code';

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_CREATE] = ['name', 'country_code', 'phone', 'address', 'birthday', 'status', 'password'];
        $scenarios[self::SCENARIO_EDIT] = ['id', 'name', 'country_code', 'phone', 'address', 'birthday', 'status'];
        $scenarios[self::SCENARIO_UPDATE_SALER_CODE] = ['id', 'saler_code'];
        return $scenarios;
    }

    public function rules()
    {
        return [
            ['id', 'required', 'on' => self::SCENARIO_EDIT],
            [['name'], 'required'],
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            [['country_code', 'phone', 'address', 'birthday'], 'safe'],

            ['name', 'trim'],
            ['name', 'required'],

            ['username', 'trim'],
            ['username', 'required'],
            ['username', 'match', 'pattern' => '/^[A-Za-z0-9_]+$/u', 'message' => Yii::t('app', 'validate_alphanumeric')],
            ['username', 'unique', 'targetClass' => '\backend\models\User', 'message' => Yii::t('app', 'validate_username_unique')],


            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => '\backend\models\User', 'message' => Yii::t('app', 'validate_email_unique')],

            ['password', 'required'],
            ['password', 'string', 'min' => 6],
            [['password'],'safe'],

            ['status', 'in', 'range' => array_keys(User::getUserStatus())],

            [['phone', 'address', 'birthday'], 'trim'],
            ['phone', 'match', 'pattern' => '/^[0-9]+((\.|\s)?[0-9]+)*$/i'],
            ['saler_code', 'required', 'on' => self::SCENARIO_UPDATE_SALER_CODE]
        ];
    }

    public function attributeLabels()
    {
        return [
            'name' => Yii::t('app', 'name'),
            'username' => Yii::t('app', 'username'),
            'email' => Yii::t('app', 'email'),
            'phone' => Yii::t('app', 'contact_phone'),
            'address' => Yii::t('app', 'address'),
            'birthday' => Yii::t('app', 'birthday'),
            'password' => Yii::t('app', 'password'),
            'status' => Yii::t('app', 'status'),
        ];
    }
}