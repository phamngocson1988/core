<?php

namespace backend\forms;

use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use backend\models\ForumCategory;
use common\components\helpers\LanguageHelper;

class FetchForumCategoryForm extends Model
{
    public $q;
    public $language;

    private $_command;
    
    public function attributeLabels()
    {
        return [
            'q' => Yii::t('app', 'keyword'),
            'language' => Yii::t('app', 'language'),
        ];
    }
    protected function createCommand()
    {
        $command = ForumCategory::find();
        if ($this->q) {
            $command->andWhere(['or',
                ['like', "title", $this->q],
                ['like', "intro", $this->q],

            ]);
        }
        if ($this->language) {
            $command->andWhere(["language" => $this->language]);
        }
        $this->_command = $command;
    }

    public function getCommand()
    {
        if (!$this->_command) {
            $this->createCommand();
        }
        return $this->_command;
    }

    public function fetchLanguages()
    {
        return LanguageHelper::fetchLanguages();
    }
}
