<?php

namespace backend\forms;

use Yii;
use yii\base\Model;
use backend\models\ForumCategory;

class CreateForumCategoryForm extends Model
{
    public $title;
    public $intro;

    public function rules()
    {
        return [
            [['title'], 'required'],
            ['intro', 'trim']
        ];
    }

    public function create()
    {
        $category = new ForumCategory();
        $category->title = $this->title;
        $category->intro = $this->intro;
        return $category->save();
    }
}
