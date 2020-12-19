<?php

namespace backend\forms;

use Yii;
use yii\base\Model;
use backend\models\ForumCategory;
use common\components\helpers\LanguageHelper;

class CreateForumCategoryForm extends Model
{
    public $title;
    public $intro;
    public $language;

    public function init()
    {
        $languages = $this->fetchLanguages();
        $keys = array_keys($languages);
        if (!in_array($this->language, $keys)) {
            $this->language = reset($keys);
        }
    }

    public function rules()
    {
        $languages = $this->fetchLanguages();
        return [
            [['title', 'language'], 'required'],
            ['intro', 'trim'],
            ['language', 'in', 'range' => array_keys($languages)],
        ];
    }

    public function create()
    {
        $category = new ForumCategory();
        $category->title = $this->title;
        $category->intro = $this->intro;
        $category->language = $this->language;
        return $category->save();
    }

    public function fetchLanguages()
    {
        return LanguageHelper::fetchLanguages();
    }
}
