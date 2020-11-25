<?php

namespace backend\forms;

use Yii;
use yii\base\Model;
use backend\models\Category;
use yii\helpers\ArrayHelper;

class CreateCategoryForm extends Model
{
    public $title;
    public $image_id;
    public $language;

    public function rules()
    {
        return [
            [['title'], 'required'],
            ['image_id', 'safe'],
            ['language', 'required'],
            ['language', 'in', 'range' => array_keys(Yii::$app->params['languages'])],
        ];
    }

    public function attributeLabels()
    {
        return [
            'title' => Yii::t('app', 'title'),
            'image_id' => Yii::t('app', 'image'),
            'language' => Yii::t('app', 'language'),
        ];
    }

    public function create()
    {
        $category = new Category();
        $category->title = $this->title;
        $category->image_id = $this->image_id;
        $category->language = $this->language;
        return $category->save();
    }

    public function fetchLanguages()
    {
        return ArrayHelper::map(Yii::$app->params['languages'], 'code', 'title');
    }
}
