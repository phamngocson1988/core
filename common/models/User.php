<?php
namespace common\models;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use yii\helpers\ArrayHelper;
use common\models\UserWallet;

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

    CONST IS_NOT_RESELLER = 1;
    const IS_RESELLER = 2;


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

    public function getOrders()
    {
        return $this->hasMany(Order::className(), ['customer_id' => 'id']);
    }

    public function getWalletAmount()
    {
        $wallets = $this->wallet;
        $arr = ArrayHelper::getColumn($wallets, 'coin');
        return array_sum($arr);
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
        return ArrayHelper::getValue(Yii::$app->params['country_code'], $this->country_code, '');
    }

    public function isReseller() 
    {
        return $this->is_reseller == self::IS_RESELLER;
    }
}
