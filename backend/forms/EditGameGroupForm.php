<?php

namespace backend\forms;

use Yii;
use yii\base\Model;
use backend\models\GameGroup;
use backend\models\GameSetting;

class EditGameGroupForm extends Model
{
    public $id;
    public $title;
    public $method;
    public $version;
    public $package;

    protected $_group;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'title', 'method', 'version', 'package'], 'required'],
        ];
    }

    public function create()
    {
        $group = $this->getGroup();
        $group->title = $this->title;
        $group->method = implode(",", $this->method);
        $group->version = implode(",", $this->version);
        $group->package = implode(",", $this->package);
        return $group->save();
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

    public function fetchVersion()
    {
        $model = GameSetting::find()->where(['key' => 'version'])->one();
        $values = array_map(function($value) {
            return trim($value);
        }, explode(",", $model->value));
        return array_combine($values, $values);
    }

    public function fetchPack()
    {
        $model = GameSetting::find()->where(['key' => 'package'])->one();
        $values = array_map(function($value) {
            return trim($value);
        }, explode(",", $model->value));
        return array_combine($values, $values);
    }

    public function getGroup()
    {
        if (!$this->_group) {
            $this->_group = GameGroup::findOne($this->id);
        }
        return $this->_group;
    }

    public function loadData()
    {
        $group = $this->getGroup();
        $this->title = $group->title;
        $this->method = explode(",", $group->method);
        $this->version = explode(",", $group->version);
        $this->package = explode(",", $group->package);
    }
}
