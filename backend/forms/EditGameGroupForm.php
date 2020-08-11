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
        $group->save();
        $packageIds = $this->package;
        if (count($packageIds)) {
            $packages = GamePackage::find()
            ->where(['group_id' => $group->id])
            ->andWhere(['in', 'id', $packageIds])
            ->select(['id'])
            ->asArray()
            ->all();
            $currentIds = array_column($packages, 'id');
            $diff = array_diff($packageIds, $currentIds);
            foreach ($diff as $diffValue) {
                $packForm = new CreateGamePackageForm([
                    'group_id' => $group->id,
                    'title' => $diffValue,
                ]);
                $newPack = $packForm->save();
                $currentIds[] = $newPack->id;
            }
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
        $group = $this->getGroup();
        return ArrayHelper::map($group->packages, 'id', 'title');
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
        $packages = $group->packages;

        $this->title = $group->title;
        $this->method = explode(",", $group->method);
        $this->version = explode(",", $group->version);
        $this->package = ArrayHelper::getColumn($packages, 'id');
    }
}
