<?php
namespace common\behaviors;

use Yii;
use yii\helpers\ArrayHelper;
use yii\behaviors\AttributeBehavior;
use common\models\UserNotificationSetting;

class UserNotificationSettingBehavior extends AttributeBehavior
{
    public function getNotificationSetting($platform, $key) 
    {
        $owner = $this->owner; // User
        $setting = UserNotificationSetting::find()->where([
          'user_id' => $owner->id,
          'platform' => $platform,
          'key' => $key,
        ])->one();
        return $setting ? $setting->value : '';
    }

    public function setNotificationSetting($platform, $key, $value = '')
    {
        $owner = $this->owner;
        $setting = UserNotificationSetting::find()->where([
          'user_id' => $owner->id,
          'platform' => $platform,
          'key' => $key,
        ])->one();
        if (!$setting) {
          $setting = new UserNotificationSetting([
            'user_id' => $owner->id,
            'platform' => $platform,
            'key' => $key,
          ]);
        }
        $setting->value = $value;
        return $setting->save();
    }

    public function getNotificationSettings()
    {
        $owner = $this->owner;
        $setting = UserNotificationSetting::find()->where([
            'user_id' => $owner->id,
        ])->select(['key', 'platform', 'value'])->asArray()->all();
        return ArrayHelper::map($setting, 'key', 'value', 'platform');
    }
}
