<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\SluggableBehavior;

class Paygate extends ActiveRecord
{
    const STATUS_INACTIVE = 1;
    const STATUS_ACTIVE = 10;

    const PAYGATE_TYPE_ONLINE = 'online';
    const PAYGATE_TYPE_OFFLINE = 'offline';


    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%paygate}}';
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => 'updated_at',
                'value' => date('Y-m-d H:i:s')
            ],
            [
                'class' => BlameableBehavior::className(),
                'createdByAttribute' => 'created_by',
                'updatedByAttribute' => 'updated_by',
            ],
            [
                'class' => SluggableBehavior::className(),
                'attribute' => 'name',
                'slugAttribute' => 'identifier',
                'immutable' => true,
                'ensureUnique'=>true,
            ],
        ];
    }

    public function isActive()
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    public function getImage()
    {
        return $this->hasOne(Image::className(), ['id' => 'logo']);
    }

    public function getImageUrl($size = null, $default = '/vendor/assets/pages/media/profile/profile_user.jpg')
    {
        $image = $this->image;
        if (!$image) {
            return $default;
        }
        return $image->getUrl($size);
    }

    public function getFee($amount) 
    {
        $fee = $this->transfer_fee;
        if ($fee) {
            $type = $this->transfer_fee_type;
            return $type == 'fix' ? $fee : number_format($fee * $amount / 100, 1);
        } else {
            return 0;
        }
    }

    public function getPaymentType()
    {
        return $this->paygate_type;
    }

    public function isOnline() 
    {
        return $this->getPaymentType() == self::PAYGATE_TYPE_ONLINE;
    }
}
