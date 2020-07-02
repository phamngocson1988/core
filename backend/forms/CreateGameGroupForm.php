<?php

namespace backend\forms;

use Yii;
use yii\base\Model;
use backend\models\GameGroup;
use backend\models\GameSetting;

class CreateGameGroupForm extends Model
{
    public $title;
    public $method;
    public $version;
    public $pack;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'method', 'version', 'pack'], 'required'],
        ];
    }

    public function save()
    {
        $group = new GameGroup();
        $group->method = $this->method;
        $group->version = $this->version;
        $group->pack = $this->pack;
    }

    public function fetchMethod()
    {
    	$model = GameSetting::find()->where(['key' => 'method'])->one();
    	$values = array_map(function($value) {
    		$value = trim($value);
    		$parts = explode("|", $value);
    		return count($parts) ? trim($parts[0]) : '';
    	}, explode(",", $model->value));
    	return array_combine($values, $values);
    }

}
