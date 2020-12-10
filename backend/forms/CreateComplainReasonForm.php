<?php

namespace backend\forms;

use Yii;
use yii\base\Model;
use backend\models\ComplainReason;
use common\components\helpers\LanguageHelper;

class CreateComplainReasonForm extends Model
{
    public $title;
    public $language;

    public function init()
    {
        $languages = array_keys(Yii::$app->params['languages']);
        if (!in_array($this->language, $languages)) {
            $this->language = reset($languages);
        }
    }

    public function attributeLabels()
    {
        return [
            'title' => Yii::t('app', 'title'),
            'language' => Yii::t('app', 'language'),
        ];
    }

    public function rules()
    {
        return [
            [['title'], 'required'],
            ['language', 'required'],
            ['language', 'in', 'range' => array_keys(Yii::$app->params['languages'])],
        ];
    }

    public function create()
    {
        $reason = new ComplainReason();
        $reason->title = $this->title;
        $reason->language = $this->language;
        return $reason->save();
    }

    public function fetchLanguages()
    {
        return LanguageHelper::fetchLanguages();
    }
}
