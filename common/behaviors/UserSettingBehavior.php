<?php
namespace common\behaviors;

use Yii;
use yii\helpers\ArrayHelper;
use yii\behaviors\AttributeBehavior;
use common\models\UserSetting;

class UserSettingBehavior extends AttributeBehavior
{
    public function getSetting($key) 
    {
        $owner = $this->owner; // User
        $setting = UserSetting::find()->where([
          'user_id' => $owner->id,
          'key' => $key
        ])->one();
        return $setting ? $setting->value : '';
    }

    public function setSetting($key, $value = '')
    {
        $owner = $this->owner;
        $setting = UserSetting::find()->where([
          'user_id' => $owner->id,
          'key' => $key
        ])->one();
        if (!$setting) {
          $setting = new UserSetting([
            'user_id' => $owner->id,
            'key' => $key,
          ]);
        }
        $setting->value = $value;
        return $setting->save();
    }

    public function getSettings()
    {
        $owner = $this->owner;
        $setting = UserSetting::find()->where([
            'user_id' => $owner->id,
        ])->select(['key', 'value'])->asArray()->all();
        return ArrayHelper::map($setting, 'key', 'value');
    }
}
