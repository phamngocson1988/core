<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

class UserAffiliate extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%user_affiliate}}';
    }

    public function rules()
    {
        return [
            [['preferred_im', 'im_account', 'company', 'channel', 'channel_type'], 'required']
        ];
    }
}