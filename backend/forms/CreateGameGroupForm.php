<?php

namespace backend\forms;

use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use backend\models\GameGroup;
use backend\models\GameSetting;
use backend\models\GameMethod;
use backend\models\GameVersion;
use backend\models\GamePackage;

class CreateGameGroupForm extends Model
{
    public $title;
    public $method;
    public $version;
    public $package;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'method', 'version', 'package'], 'required'],
        ];
    }

    public function create()
    {
        $group = new GameGroup();
        $group->title = $this->title;
        $group->method = implode(",", $this->method);
        $group->version = implode(",", $this->version);
        $group->save();
        // Create packages
        $packages = $this->package;
        if (count($packages)) {
            foreach ($packages as $packageValue) {
                $packForm = new CreateGamePackageForm([
                    'group_id' => $group->id,
                    'title' => $packageValue,
                ]);
                $packForm->save();
            }

        } else {
            $group->package = '';
        }
        return $group;
    }

    public function fetchMethod()
    {
        return ArrayHelper::map(GameMethod::find()->all(), 'id', 'title');
    }

    public function fetchVersion()
    {
        return ArrayHelper::map(GameVersion::find()->all(), 'id', 'title');
    }

    public function fetchPack()
    {
        return [];
    }
    // public function fetchMethod()
    // {
    // 	$model = GameSetting::find()->where(['key' => 'method'])->one();
    // 	$values = array_map(function($value) {
    // 		$value = trim($value);
    // 		$parts = explode("|", $value);
    // 		return count($parts) ? trim($parts[0]) : '';
    // 	}, explode(",", $model->value));
    // 	return array_combine($values, $values);
    // }

    // public function fetchVersion()
    // {
    //     $model = GameSetting::find()->where(['key' => 'version'])->one();
    //     $values = array_map(function($value) {
    //         return trim($value);
    //     }, explode(",", $model->value));
    //     return array_combine($values, $values);
    // }

    // public function fetchPack()
    // {
    //     $model = GameSetting::find()->where(['key' => 'package'])->one();
    //     $values = array_map(function($value) {
    //         return trim($value);
    //     }, explode(",", $model->value));
    //     return array_combine($values, $values);
    // }
}
