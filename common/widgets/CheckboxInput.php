<?php
namespace common\widgets;

use yii\widgets\InputWidget;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use Yii;

class CheckboxInput extends InputWidget
{
    public function run()
    {
        $options['labelOptions'] = ['class' => 'mt-checkbox'];
        $options['label'] = Html::encode($this->model->getAttributeLabel(Html::getAttributeName($this->attribute))) . '<span></span>';
        return Html::activeCheckbox($this->model, $this->attribute, $options);
    }
}