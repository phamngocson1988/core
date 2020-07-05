<?php
namespace backend\forms;

use Yii;
use yii\base\Model;
use backend\models\GameSetting;

class CreateGameSettingForm extends Model
{
	public $method;
    public $version;
    public $package;

	/**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['method', 'version', 'package'], 'trim'],
        ];
    }


    public function create()
    {
        $method = $this->getMethod();
        $method->value = $this->method;
        $method->save();

        $version = $this->getVersion();
        $version->value = $this->version;
        $version->save();

        $package = $this->getPack();
        $package->value = $this->package;
        $package->save();
        
        return true;
    }

    public function loadData()
    {
        $method = $this->getMethod();
        $package = $this->getPack();
        $version = $this->getVersion();
        $this->method = $method->value;
        $this->package = $package->value;
        $this->version = $version->value;

    }

    public function getMethod()
    {
        $model = GameSetting::find()->where(['key' => 'method'])->one();
        if (!$model) {
            $model = new GameSetting(['key' => 'method']);
        }
        return $model;
    }

    public function getPack()
    {
        $model = GameSetting::find()->where(['key' => 'package'])->one();
        if (!$model) {
            $model = new GameSetting(['key' => 'package']);
        }
        return $model;
    }

    public function getVersion()
    {
        $model = GameSetting::find()->where(['key' => 'version'])->one();
        if (!$model) {
            $model = new GameSetting(['key' => 'version']);
        }
        return $model;
    }
}
