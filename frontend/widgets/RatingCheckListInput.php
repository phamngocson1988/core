<?php
namespace frontend\widgets;

use yii\widgets\InputWidget;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use Yii;

class RatingCheckListInput extends InputWidget
{
    public $items = [];

    public function run()
    {
        $items = (array)$this->items;
        $options = (array)$this->options;
        $encode = ArrayHelper::getValue($options, 'encode', true);
        $options['item'] = function($index, $label, $name, $checked, $value) use ($encode) {
            $opts['value'] = $value;
            $opts['id'] = $index;
            $opts['class'] = 'custom-control-input';
            $input = sprintf('%s<label class="custom-control-label" for="%s">%s</label>', Html::checkbox($name, $checked, $opts), $index, $label);
            return Html::tag('div', $input, ['class' => 'custom-control custom-checkbox my-1 mr-sm-2']);
        };
        return Html::activeCheckboxList($this->model, $this->attribute, $items, $options);
    }
}