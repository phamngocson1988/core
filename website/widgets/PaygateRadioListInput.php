<?php
namespace website\widgets;

use yii\widgets\InputWidget;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use Yii;

class PaygateRadioListInput extends InputWidget
{
    public $items = [];

    public function run()
    {
        $items = (array)$this->items;
        $options = (array)$this->options;
        $encode = ArrayHelper::getValue($options, 'encode', true);
        $options['item'] = function($index, $label, $name, $checked, $value) use ($encode) {
            $opts['value'] = $value;
            $opts['autocomplete'] = 'off';
            $input = sprintf('<label class="btn flex-fill btn-secondary">%s%s</label>', Html::radio($name, $checked, $opts), $label);
            return $input;
        };
        return Html::activeRadioList($this->model, $this->attribute, $items, $options);
    }
}