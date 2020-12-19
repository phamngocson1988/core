<?php
namespace backend\forms;

use Yii;
use yii\base\Model;
use backend\models\ForumCategory;
use common\components\helpers\LanguageHelper;

class EditForumCategoryForm extends Model
{
    public $id;
    public $title;
    public $intro;
    public $language;

    protected $_category;

    public function rules()
    {
        return [
            [['id', 'title'], 'required'],
            ['id', 'validateCategory'],
            ['intro', 'trim'],
            ['language', 'safe']
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
            $this->_category = ForumCategory::findOne($this->id);
        }
        return $this->_category;
    }

    public function update()
    {
        $category = $this->getCategory();
        $category->title = $this->title;
        $category->intro = $this->intro;
        return $category->save();
    }

    public function loadData()
    {
        $category = $this->getCategory();
        $this->title = $category->title;
        $this->intro = $category->intro;
        $this->language = $category->language;
    }

    public function fetchLanguages()
    {
        return LanguageHelper::fetchLanguages();
    }
}
