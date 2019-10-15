<?php

namespace supplier\components\actions;

use Yii;
use yii2mod\settings\models\enumerables\SettingStatus;

class SettingModel extends \yii2mod\settings\models\SettingModel
{
    /**
     * @inheritdoc
     */
    public function rules(): array
    {
        return [
            [['section', 'key'], 'required'],
            [['section', 'key'], 'unique', 'targetAttribute' => ['section', 'key']],
            [['value', 'type'], 'string'],
            [['section', 'key', 'description'], 'string', 'max' => 255],
            [['status'], 'integer'],
            ['status', 'default', 'value' => SettingStatus::ACTIVE],
            ['status', 'in', 'range' => SettingStatus::getConstantsByName()],
            [['type'], 'safe'],
        ];
    }
}
