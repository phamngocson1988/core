<?php

namespace backend\modules\shop\forms;

use Yii;
use yii\base\Model;
use common\modules\shop\models\ProductPackage;

class CreatePackageForm extends Model
{
    public $title;
    public $price;
    public $gems;

    public function rules()
    {
        return [
            [['title', 'price', 'gems'], 'required'],
        ];
    }

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_DEFAULT] = ['title', 'price', 'gems'];
        return $scenarios;
    }

    public function save()
    {
        if ($this->validate()) {
            try {
                $package = new ProductPackage();
                $package->title = $this->title;
                $package->price = $this->price;
                $package->gems = $this->gems;
                $package->sale_off_type = "fix";
                $package->status = "Y";
                return $package->save();
            } catch (Exception $e) {
                return false;
            }
        }
        return false;
    }
}
