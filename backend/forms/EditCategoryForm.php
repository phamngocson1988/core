<?php
namespace backend\forms;

use Yii;
use yii\base\Model;
use backend\models\Category;
use common\components\helpers\LanguageHelper;

class EditCategoryForm extends Model
{
    public $id;
    public $title;
    public $image_id;
    public $language;

    protected $_category;

    public function rules()
    {
        return [
            [['id', 'title'], 'required'],
            ['id', 'validateCategory'],
            [['image_id', 'language'], 'safe']
        ];
    }

    public function validateCategory($attribute, $params = [])
    {
        $category = $this->getCategory();
        if (!$category) {
            $this->addError($attribute, Yii::t('app', 'category_is_not_exist'));
        }
    }

    public function getCategory()
    {
        if (!$this->_category) {
            $this->_category = Category::findOne($this->id);
        }
        return $this->_category;
    }

    public function update()
    {
        $category = $this->getCategory();
        $category->title = $this->title;
        $category->image_id = $this->image_id;
        return $category->save();
    }

    public function loadData()
    {
        $category = $this->getCategory();
        $this->title = $category->title;
        $this->image_id = $category->image_id;
        $this->language = $category->language;
    }

    public function fetchLanguages()
    {
        return LanguageHelper::fetchLanguages();
    }
}
