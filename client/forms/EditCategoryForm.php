<?php

namespace client\forms;

use Yii;
use yii\base\Model;
use common\models\Category;
use common\components\helpers\StringHelper;
use yii\helpers\ArrayHelper;

class EditCategoryForm extends Model
{
    public $id;
    public $type;
    public $name;
    public $parent_id;
    public $slug;
    public $visible;
    public $meta_title;
    public $meta_keyword;
    public $meta_description;
    public $icon;
    public $image_id;

    private $_category;

    public function rules()
    {
        return [
            [['id', 'name', 'slug'], 'required'],
            ['slug', 'validateSlug'],
        ];
    }

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_DEFAULT] = ['id', 'type', 'name', 'parent_id', 'slug', 'visible', 'meta_title', 'meta_keyword', 'meta_description', 'icon', 'image_id'];
        return $scenarios;
    }

    public function save()
    {
        if ($this->validate()) {
            try {
                $category = $this->getCategory();
                $category->name = $this->name;
                $category->slug = $this->slug;
                $category->parent_id = $this->parent_id;
                $category->visible = $this->visible;
                $category->meta_title = $this->meta_title;
                $category->meta_keyword = $this->meta_keyword;
                $category->meta_description = $this->meta_description;
                $category->icon = $this->icon;
                $category->image_id = $this->image_id;
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

            if (Category::find()->where(['slug' => $slug])->count() > 1) {
                $this->addError($attribute, Yii::t('app', 'slug_not_exist', ['slug' => $slug]));
            }
        }
    }

    public function loadData($id)
    {
        $this->id = $id;
        $category = $this->getCategory();
        $this->name = $category->name;
        $this->slug = $category->slug;
        $this->parent_id = $category->parent_id;
        $this->visible = $category->visible;
        $this->meta_title = $category->meta_title;
        $this->meta_keyword = $category->meta_keyword;
        $this->meta_description = $category->meta_description;
        $this->icon = $category->icon;
        $this->image_id = $category->image_id;
    }

    protected function getCategory()
    {
        if ($this->_category === null) {
            $this->_category = Category::findOne($this->id);
        }

        return $this->_category;
    }

    public function getAvailableParent()
    {
        $locations = Category::find()->where(['type' => $this->type])->all();
        $locations = ArrayHelper::map($locations, 'id', 'name');
        unset($locations[$this->id]);
        return $locations;
    }

    public function hasImage()
    {
        $category = $this->getCategory();
        return $category->image;
    }
    public function getImageUrl($size)
    {
        $category = $this->getCategory();
        return $category->getImageUrl($size, '');
    }
}
