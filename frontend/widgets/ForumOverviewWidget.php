<?php
namespace frontend\widgets;

use Yii;
use yii\base\Widget;
use yii\helpers\ArrayHelper;
use frontend\models\ForumTopic;
use frontend\models\ForumSection;
use frontend\models\ForumCategory;
use frontend\models\ForumSectionCategory;

class ForumOverviewWidget extends Widget
{
	public $section_id;
	public $section;

    public function run()
    {
    	$section = $this->getSection();
    	if (!$section) return '';

        $sectionCategories = ForumSectionCategory::find()->where(['section_id' => $section->id])->all();
        if (!count($sectionCategories)) return '';

        $categoryIds = ArrayHelper::getColumn($sectionCategories, 'category_id');
        $categories = ForumCategory::findAll($categoryIds);
        return $this->render('forum_overview', [
            'section' => $section, 
            'categories' => $categories, 
        ]);
    }

    public function getSection()
    {
    	if (!$this->section) {
    		$this->section = ForumSection::findOne($this->section_id);
    	}
    	return $this->section;
    }
}