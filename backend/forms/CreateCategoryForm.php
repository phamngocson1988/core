<?php

namespace backend\forms;

use Yii;
use yii\base\Model;
use backend\models\Category;

class CreateCategoryForm extends Model
{
    public $title;
    public $image_id;

    public function rules()
    {
        return [
            [['title'], 'required'],
            ['image_id', 'safe']
        ];
    }

    public function create()
    {
        $category = new Category();
        $category->title = $this->title;
        $category->image_id = $this->image_id;
        return $category->save();
    }
}
