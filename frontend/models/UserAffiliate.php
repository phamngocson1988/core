<?php
namespace frontend\models;

use Yii;

class UserAffiliate extends \common\models\UserAffiliate
{
    public function rules()
    {
        return [
            [['preferred_im', 'im_account', 'company', 'channel', 'channel_type'], 'required'],
            ['status', 'default', 'value' => self::STATUS_DISABLE]
        ];
    }
}