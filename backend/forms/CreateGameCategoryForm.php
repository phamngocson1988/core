<?php

namespace backend\forms;

use Yii;
use yii\base\Model;
use backend\models\GameCategory;

class CreateGameCategoryForm extends Model
{
    public $name;

    public function rules()
    {
        return [
            [['name'], 'required'],
        ];
    }

    public function save()
    {
        $category = new GameCategory();
        $category->name = $this->name;
        return $category->save();
    }
}
