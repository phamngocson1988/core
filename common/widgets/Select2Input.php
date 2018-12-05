<?php
namespace common\widgets;

use yii\widgets\InputWidget;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\Json;
use yii\web\JsExpression;

/**
 * Select2Input
 *
 * @author Son Pham
 */
class Select2Input extends InputWidget
{
    /**
     * @var string
     */
    public $loadUrl;

    /**
     * @var array
     */
    public $items = [];
    
    /**
     * @var array
     */
    public $clientOptions = [
        'minimumInputLength' => 2
    ];

    /**
     * @inheritdoc
     */
    public function run()
    {
        $this->registerClientScript();
        if ($this->hasModel()) {
            echo Html::activeDropDownList($this->model, $this->attribute, $this->items, $this->options);
        } else {
            echo Html::dropDownList($this->name, $this->value, $this->items, $this->options);
        }
    }

    /**
     * Registers the needed JavaScript.
     */
    public function registerClientScript()
    {
        $id = $this->options['id'];

        if ($this->loadUrl !== null) {
            $url = Url::to($this->loadUrl);
            $this->clientOptions['ajax'] = [
                'url' => $url,
                'delay' => '500',
                'allowClear' => true,
                'type' => 'GET',
                'dataType' => 'json',
                'processResults' => new JsExpression('function (data) {return {results: data.data.items};}')
            ];
        }

        $options = Json::encode($this->clientOptions);
        $view = $this->getView();
        Select2Asset::register($view);
        $view->registerJs("jQuery('#$id').select2($options);");
    }

}
/*
$('.find-user').select2({
  ajax: {
    delay: 500,
    allowClear: true,
    url: '{/literal}{$links.user_suggestion}{literal}',
    type: "GET",
    dataType: 'json',
    processResults: function (data) {
      // Tranforms the top-level key of the response object from 'items' to 'results'
      return {
        results: data.data.items
      };
    }
  }
});
*/
