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
        $options = $this->options;
        // print_r($options);
        $options['labelOptions'] = ['class' => 'mt-checkbox'];
        $options['label'] = isset($options['label']) ? $options['label'] : Html::encode($this->model->getAttributeLabel(Html::getAttributeName($this->attribute)));
        $options['label'] .= '<span></span>';
        return Html::activeCheckbox($this->model, $this->attribute, $options);
    }
}