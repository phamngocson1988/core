<?php
namespace common\models;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use yii\helpers\ArrayHelper;
use common\models\UserWallet;
// use common\behaviors\UserCommissionBehavior;
use common\behaviors\UserWalletBehavior;
use common\behaviors\UserResellerBehavior;
use common\behaviors\AffiliateBehavior;

/**
 * User model
 *
 * @property integer $id
 * @property string $name
 * @property string $username
 * @property integer $avatar
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property string $auth_key
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $password write-only password
 */
class User extends ActiveRecord implements IdentityInterface
{
    const STATUS_DELETED = 0;
    const STATUS_INACTIVE = 1;
    const STATUS_ACTIVE = 10;

    const IS_NOT_RESELLER = 1;
    const IS_RESELLER = 2;
    const RESELLER_LEVEL_1 = 1;
    const RESELLER_LEVEL_2 = 2;
    const RESELLER_LEVEL_3 = 3;

    const IS_NOT_SUPPLIER = 1;
    const IS_SUPPLIER = 2;


    const IS_TRUST = 'Y';
    const IS_NOT_TRUST = 'N';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user}}';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => 'updated_at',
                'value' => date('Y-m-d H:i:s')
            ],
            // [
            //     'class' => UserCommissionBehavior::className(),
            // ],
            [
                'class' => UserWalletBehavior::className(),
            ],
            [
                'class' => UserResellerBehavior::className(),
            ],
            'affiliate' => AffiliateBehavior::className(),
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_DELETED, self::STATUS_INACTIVE]],
        ];
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'status' => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return bool
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }

    public function generateSalerCode()
    {
        $this->saler_code = Yii::$app->security->generateRandomString(6);
    }

    public function getRoles()
    {
        $auth = Yii::$app->authManager;
        $roles = $auth->getRolesByUser($this->id);
        $roleNames = ArrayHelper::map($roles, 'name', 'description');
        return $roleNames;
    }

    public static function getUserStatus()
    {
        return [
            self::STATUS_ACTIVE => Yii::t('app', 'active'),
            self::STATUS_INACTIVE => Yii::t('app', 'inactive'),
            self::STATUS_DELETED => Yii::t('app', 'disable'),
        ];
    }

    public static function getResellerStatus()
    {
        return [
            self::IS_NOT_RESELLER => 'Customer',
            self::IS_RESELLER => 'Reseller',
        ];
    }

    public function getStatusLabel()
    {
        $labels = self::getUserStatus();
        return ArrayHelper::getValue($labels, $this->status);
    }

    public function getAvatarImage()
    {
        return $this->hasOne(Image::className(), ['id' => 'avatar']);
    }

    public function getAvatarUrl($size = null, $default = '/vendor/assets/pages/media/profile/profile_user.jpg')
    {
        $image = $this->avatarImage;
        if (!$image) {
            return $default;
        }
        return $image->getUrl($size);
    }

    public function getName()
    {
        return ($this->name) ? $this->name : $this->username;
    }

    public function isActive()
    {
        return $this->status == self::STATUS_ACTIVE;
    }

    public function isVerifyPhone()
    {
        return (int)$this->is_verify_phone;
    }

    public function isInactive()
    {
        return $this->status == self::STATUS_INACTIVE;
    }

    public function isDeleted()
    {
        return $this->status == self::STATUS_DELETED;
    }

    public function getWallet()
    {
        return $this->hasMany(UserWallet::className(), ['user_id' => 'id'])->where('status = :status', [':status' => UserWallet::STATUS_COMPLETED]);
    }

    public function getTransactions()
    {
        return $this->hasMany(PaymentTransaction::className(), ['user_id' => 'id'])->where('status = :status', [':status' => PaymentTransaction::STATUS_COMPLETED]);
    }

    public function getOrders()
    {
        return $this->hasMany(Order::className(), ['customer_id' => 'id']);
    }

    public function getOrder()
    {
        return $this->hasOne(Order::className(), ['customer_id' => 'id'])->orderBy(['order.created_at' => SORT_DESC]);
    }

    public function getWalletAmount()
    {
        $command = $this->getWallet();
        return $command->sum('coin');
    }

    public function getWalletTopupAmount()
    {
        $command = $this->getWallet();
        $command->andWhere(['type' => UserWallet::TYPE_INPUT]);
        return $command->sum('coin');
    }

    public function getWalletWithdrawAmount()
    {
        $command = $this->getWallet();
        $command->andWhere(['type' => UserWallet::TYPE_OUTPUT]);
        return $command->sum('coin');
    }

    public function getCountryName()
    {
        $country = Country::findOne($this->country_code);
        if ($country) return $country->country_name;
    }

    public function isReseller() 
    {
        return $this->is_reseller == self::IS_RESELLER;
    }

    public function getReseller()
    {
        return $this->hasOne(UserReseller::className(), ['user_id' => 'id']);
    }

    public function getOldResellerLabel()
    {
        $labels = [
            self::RESELLER_LEVEL_1 => 'Gold',
            self::RESELLER_LEVEL_2 => 'Diamond',
            self::RESELLER_LEVEL_3 => 'Platinum',
        ];
        return ArrayHelper::getValue($labels, $this->old_reseller_level, '');
    }

    public function getResellerLabel()
    {
        $labels = [
            self::RESELLER_LEVEL_1 => 'Gold',
            self::RESELLER_LEVEL_2 => 'Diamond',
            self::RESELLER_LEVEL_3 => 'Platinum',
        ];
        return ArrayHelper::getValue($labels, $this->reseller_level);
    }

    /**
     * Active member: là tài khoản có ít nhất 1 giao dịch (top up or mua hàng)
     */
    public function isActiveMember()
    {
        $transaction = $this->getTransactions();
        $order = $this->getOrders();
        return $transaction->count() || $order->count();
    }

    public function isTrust()
    {
        return $this->trust == self::IS_TRUST;
    }
}
