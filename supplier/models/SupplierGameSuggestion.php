<?php
namespace supplier\models;

use Yii;

class SupplierGameSuggestion extends \common\models\SupplierGameSuggestion
{
	const SCENARIO_CREATE = 'SCENARIO_CREATE';
    const SCENARIO_EDIT = 'SCENARIO_EDIT';

    public function scenarios()
    {
        return [
            self::SCENARIO_CREATE => ['title', 'description', 'image_id'],
            self::SCENARIO_EDIT => ['id', 'title', 'description', 'image_id'],
        ];
    }
	public function rules()
    {
        return [
            ['title', 'required'],
            ['id', 'required', 'on' => self::SCENARIO_EDIT],
            [['description', 'image_id'], 'safe'],
        ];
    }

    public function getStatusLabel($format = '<span class="label label-%s">%s</span>')
    {
        $list = [
            self::STATUS_NEW => 'warning',
            self::STATUS_DONE => 'primary',
        ];
        $labels = self::getStatusList();
        $color = $list[$this->status];
        $label = $labels[$this->status];
        return sprintf($format, $color, $label);
    }
}