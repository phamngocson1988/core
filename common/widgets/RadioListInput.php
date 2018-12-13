<?php
namespace common\widgets;

use yii\widgets\InputWidget;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use Yii;

class RadioListInput extends InputWidget
{
    public $items = [];

    public function run()
    {
        $items = (array)$this->items;
        $options = (array)$this->options;
        $encode = ArrayHelper::getValue($options, 'encode', true);
        $options['item'] = function($index, $label, $name, $checked, $value) use ($encode) {
            $label = $encode ? Html::encode($label) : $label;
            $opts['labelOptions'] = ['class' => 'mt-radio'];
            $opts['label'] = $label . '<span></span>';
            $opts['value'] = $value;
            return Html::radio($name, $checked, $opts);
        };
        return Html::activeRadioList($this->model, $this->attribute, $items, $options);
    }
}