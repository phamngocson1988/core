<?php

namespace backend\forms;

use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use backend\models\Category;

class FetchCategoryForm extends Model
{
    public $language;

    private $_command;
    
    public function attributeLabels()
    {
        return [
            'language' => Yii::t('app', 'language'),
        ];
    }
    protected function createCommand()
    {
        $command = Category::find();

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
        return ArrayHelper::map(Yii::$app->params['languages'], 'code', 'title');
    }
}
