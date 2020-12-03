<?php

namespace backend\forms;

use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use backend\models\Ads;

class FetchAdsForm extends Model
{
    public $position;
    public $language;
    public $contact_email;

    private $_command;
    
    public function attributeLabels()
    {
        return [
            'position' => Yii::t('app', 'position'),
            'language' => Yii::t('app', 'language'),
            'contact_email' => Yii::t('app', 'contact_email'),
        ];
    }
    protected function createCommand()
    {
        $command = Ads::find();

        if ($this->position) {
            $command->andWhere(['position' => $this->position]);
        }
        if ($this->language) {
            $command->andWhere(['language' => $this->language]);
        }
        if ($this->contact_email) {
            $command->andWhere(['like', 'contact_email', $this->contact_email]);
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

    public function fetchPosition()
    {
        return Ads::getPositionList();
    }

    public function fetchLanguages()
    {
        return ArrayHelper::map(Yii::$app->params['languages'], 'code', 'title');
    }
}
