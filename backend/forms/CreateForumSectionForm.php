<?php

namespace backend\forms;

use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use backend\models\ForumSection;
use backend\models\ForumCategory;
use backend\models\ForumSectionCategory;
use common\components\helpers\LanguageHelper;

class CreateForumSectionForm extends Model
{
    public $title;
    public $categories;
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
            [['title', 'language'], 'trim'],
            [['title', 'language'], 'required'],
            ['categories', 'safe'],
            ['language', 'in', 'range' => array_keys($languages)],
        ];
    }

    public function create()
    {
        $connection = Yii::$app->db;
        $transaction = $connection->beginTransaction();
        try {
            $section = new ForumSection();
            $section->title = $this->title;
            $section->language = $this->language;
            $section->save();
            $sectionId = $section->id;
            
            if ($this->categories) { 
                foreach ($this->categories as $categoryId) {
                    $category = new ForumSectionCategory([
                        'section_id' => $sectionId,
                        'category_id' => $categoryId
                    ]);
                    $category->save();
                }
            }

            $transaction->commit();
            return $sectionId;
        } catch(Exception $e) {
            $transaction->rollback();
            $this->addError('id', $e->getMessage());
            return false;
        }
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
