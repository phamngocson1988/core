<?php
namespace backend\widgets;

use Yii;
use yii\helpers\ArrayHelper;
use yii\base\Widget;

class LanguageModalWidget extends Widget
{
    public $modalId = 'choose-language';
    public $url;

    public function run()
    {
        return $this->render('language_modal_widget', [
            'modalId' => $this->modalId,
            'url' => $this->url,
            'languages' => $this->fetchLanguages()
        ]);
    }

    public function fetchLanguages()
    {
        return ArrayHelper::map(Yii::$app->params['languages'], 'code', 'title');
    }
}