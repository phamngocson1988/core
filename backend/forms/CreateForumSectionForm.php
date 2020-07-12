<?php

namespace backend\forms;

use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use backend\models\ForumSection;
use backend\models\ForumCategory;
use backend\models\ForumSectionCategory;

class CreateForumSectionForm extends Model
{
    public $title;
    public $categories;

    public function rules()
    {
        return [
            [['title'], 'required'],
            ['categories', 'safe']
        ];
    }

    public function create()
    {
        $connection = Yii::$app->db;
        $transaction = $connection->beginTransaction();
        try {
            $section = new ForumSection();
            $section->title = $this->title;
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
        $categories = ForumCategory::find()->all();
        return ArrayHelper::map($categories, 'id', 'title');
    }
}
