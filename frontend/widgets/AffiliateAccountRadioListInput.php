<?php
namespace frontend\widgets;

use yii\widgets\InputWidget;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use Yii;

class AffiliateAccountRadioListInput extends InputWidget
{
    public $items = [];

    public function run()
    {
        $items = (array)$this->items;
        $options = (array)$this->options;
        $encode = ArrayHelper::getValue($options, 'encode', true);
        $options['item'] = function($index, $label, $name, $checked, $value) use ($encode) {
            $opts['value'] = $value;
            $deleteUrl = Url::to(['affiliate/delete-account', 'id' => $value]);
            $actions = sprintf('<div class="action">
                                <div class="del icon-del">
                                  <a href="%s" class="delete-account-link"><img src="/images/icon/trash-can.svg"/></a>
                                </div>
                              </div>', $deleteUrl);
            $input = sprintf('<label class="btn flex-fill w-25 mr-2">%s%s%s</label>', Html::radio($name, $checked, $opts), $label, $actions);
            return $input;
        };
        return Html::activeRadioList($this->model, $this->attribute, $items, $options);
    }
}