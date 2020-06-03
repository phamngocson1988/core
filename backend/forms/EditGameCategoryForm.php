<?php

namespace backend\forms;

use Yii;
use yii\base\Model;
use backend\models\GameCategory;

class EditGameCategoryForm extends Model
{
    public $id;
    public $name;
    protected $_category;

    public function rules()
    {
        return [
            [['id', 'name'], 'required'],
        ];
    }

    public function save()
    {
        $category = $this->getCategory();
        $category->name = $this->name;
        return $category->save();
    }

    public function getCategory()
    {
        if (!$this->_category) {
            $this->_category = GameCategory::findOne($this->id);
        }
        return $this->_category;
    }

    public function loadData()
    {
        $category = $this->getCategory();
        $this->name = $category->name;
    }
}
