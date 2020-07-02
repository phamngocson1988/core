<?php
namespace backend\forms;

use Yii;
use yii\base\Model;
use backend\models\GameSetting;

class CreateGameSettingForm extends Model
{
	public $method;
    public $version;
    public $pack;

	/**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['method', 'version', 'pack'], 'trim'],
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

        $pack = $this->getPack();
        $pack->value = $this->pack;
        $pack->save();
        
        return true;
    }

    public function loadData()
    {
        $method = $this->getMethod();
        $pack = $this->getPack();
        $version = $this->getVersion();
        $this->method = $method->value;
        $this->pack = $pack->value;
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
        $model = GameSetting::find()->where(['key' => 'pack'])->one();
        if (!$model) {
            $model = new GameSetting(['key' => 'pack']);
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
