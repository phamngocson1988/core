<?php
namespace backend\forms;

use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use backend\models\ForumSection;
use backend\models\ForumCategory;
use backend\models\ForumSectionCategory;
use common\components\helpers\LanguageHelper;

class EditForumSectionForm extends Model
{
    public $id;
    public $title;
    public $categories;
    public $language;

    protected $_section;

    public function rules()
    {
        return [
            [['id', 'title'], 'required'],
            ['id', 'validateSection'],
            ['categories', 'safe'],
            ['language', 'safe']
        ];
    }

    public function validateSection($attribute, $params = [])
    {
        $section = $this->getSection();
        if (!$section) {
            $this->addError($attribute, Yii::t('app', 'section_is_not_exist'));
        }
    }

    public function getSection()
    {
        if (!$this->_section) {
            $this->_section = ForumSection::findOne($this->id);
        }
        return $this->_section;
    }

    public function update()
    {
        $connection = Yii::$app->db;
        $transaction = $connection->beginTransaction();
        try {
            $section = $this->getSection();
            $section->title = $this->title;
            $section->save();

            ForumSectionCategory::deleteAll(['section_id' => $section->id]);
            if ($this->categories) { 
                foreach ($this->categories as $categoryId) {
                    $category = new ForumSectionCategory([
                        'section_id' => $section->id,
                        'category_id' => $categoryId
                    ]);
                    $category->save();
                }
            }

            $transaction->commit();
            return true;
        } catch(Exception $e) {
            $transaction->rollback();
            $this->addError('id', $e->getMessage());
            return false;
        }
    }

    public function loadData()
    {
        $section = $this->getSection();
        $this->title = $section->title;
        $this->language = $section->language;
        $this->categories = $this->loadOldCategoryIds();
    }

    public function loadOldCategoryIds() 
    {
        $categories = ForumSectionCategory::find()->where(['section_id' => $this->id])->all();
        return ArrayHelper::getColumn($categories, 'category_id');
    }

    public function fetchCategory()
    {
        $categories = ForumCategory::find()
        ->where(['language' => $this->language])
        ->all();
        return ArrayHelper::map($categories, 'id', 'title');
    }

    public function fetchLanguages()
    {
        return LanguageHelper::fetchLanguages();
    }
}
