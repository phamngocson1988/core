<?php

namespace client\forms;

use Yii;
use yii\base\Model;
use common\models\Category;
use common\components\helpers\StringHelper;
use yii\helpers\ArrayHelper;

class CreateCategoryForm extends Model
{
    public $name;
    public $parent_id;
    public $slug;
    public $visible = Category::VISIBLE;
    public $meta_title;
    public $meta_keyword;
    public $meta_description;
    public $icon;
    public $image_id;
    public $type;

    public function rules()
    {
        return [
            [['name', 'slug'], 'required'],
            ['slug', 'validateSlug'],
            ['visible', 'default', 'value' => Category::VISIBLE],
        ];
    }

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_DEFAULT] = ['name', 'parent_id', 'slug', 'visible', 'type', 'meta_title', 'meta_keyword', 'meta_description', 'icon', 'image_id'];
        return $scenarios;
    }

    public function save()
    {
        if ($this->validate()) {
            try {
                $category = new Category();
                $category->name = $this->name;
                $category->slug = $this->slug;
                $category->parent_id = $this->parent_id;
                $category->visible = $this->visible;
                $category->meta_title = $this->meta_title;
                $category->meta_keyword = $this->meta_keyword;
                $category->meta_description = $this->meta_description;
                $category->icon = $this->icon;
                $category->image_id = $this->image_id;
                $category->type = $this->type;
                return $category->save();
            } catch (Exception $e) {
                return false;
            }
        }
        return false;
    }

    public function validateSlug($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $slug = $this->$attribute;
            if (!preg_match('/^[a-z][-a-z0-9]*$/', $slug)) {
                $this->addError($attribute, Yii::t('app', 'slug_not_match', ['slug' => $slug]));
            }

            if (Category::find()->where(['slug' => $slug])->count() > 0) {
                $this->addError($attribute, Yii::t('app', 'slug_not_exist', ['slug' => $slug]));
            }
        }
    }

    public function getAvailableParent()
    {
        $locations = Category::find()->where(['type' => $this->type])->all();
        $locations = ArrayHelper::map($locations, 'id', 'name');
        return $locations;
    }
}
