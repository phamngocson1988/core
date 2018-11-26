<?php
namespace common\widgets;

use yii\widgets\InputWidget;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use common\models\Image;
use Yii;

class MultipleImageInputWidget extends InputWidget
{
    public $handler = 'manager';
    
    public $itemOptions = [];
    public $itemSize = '300x300';
    public $item_template = "<div class='col-md-2 image-item'><div class='thumbnail'>{image}{input}{close}</div></div>";

    public $template = "<div class='row multiple-image-container'>{items}</div><div>{choose_button}</div>";
    public $chooseButtonOptions = ['tag' => 'span', 'options' => ['class' => 'btn default']];
    public $parts = [
        '{items}' => '',
        '{choose_button}' => '',
    ];
    public $clientOptions = [];

    public function run()
    {
        $id = $this->options['id'];
        $items = $this->generateItems();
        $chooseButton = $this->generateChooseButton();
        $elements = ['{items}' => $items, '{choose_button}' => $chooseButton];
        echo strtr($this->template, $elements);
        $this->registerClientScript();
    }

    protected function generateItems()
    {
        $id = $this->options['id'];
        $model = $this->model;
        $attribute = $this->attribute;
        $elements = '';
        $items = (array)$model->$attribute;
        foreach ($items as $no => $item) {
            $obj = Image::findOne($item);
            $url = $obj->getUrl($this->itemSize);
            $image = Html::img($url, $this->itemOptions);
            $input = $this->generateInput($no);
            $close = $this->generateCloseButton();
            $elements .= strtr($this->item_template, ['{image}' => $image, '{input}' => $input, '{close}' => $close]);
        }
        return $elements;
    }

    protected function generateInput($no = 0)
    {
        if ($this->hasModel()) {
            $attribute = "$this->attribute[]";
            return Html::activeHiddenInput($this->model, $attribute, $this->options);
        } else {
            return Html::hiddenInput($this->name, $this->value, $this->options);
        }
        return '';
    }

    protected function generateCloseButton()
    {
        return "<i class='glyphicon glyphicon-remove remove-button' style='position: absolute; top:0;right:15px'></i>";
    }

    protected function generateChooseButton()
    {
        $id = $this->options['id'];
        $chooseOptions = (array)$this->chooseButtonOptions;
        $tag = ArrayHelper::getValue($chooseOptions, 'tag', 'span');
        $options = ArrayHelper::getValue($chooseOptions, 'options', []);
        $options['id'] = "choose_$id";
        $options['onClick'] = "javascript:;";
        $this->parts['{choose_button}'] = Html::tag($tag, Yii::t('app', 'choose_image'), $options);
        return $this->parts['{choose_button}'];
    }

    protected function getScriptCode()
    {
        $id = $this->options['id'];
        $imageId = "image_$id";
        $chooseButtonId = "choose_$id";
        $removeButtonClass = "remove-button";
        $manager = $this->handler;
        $clientOptions = (array)$this->clientOptions;
        $clientOptions = json_encode($clientOptions);
        $item_template = addslashes($this->item_template);
        $close = addslashes($this->generateCloseButton());
        $input = addslashes($this->generateInput());
        $image = addslashes(Html::img("", $this->itemOptions));
        return "
if (!{$manager}) {
    var {$manager} = new ImageManager({$clientOptions});
}
$('#{$chooseButtonId}').selectImage({$manager}, {
  type: 'multiple',
  callback: function(imgs) {
    var elements = '';
    $.each(imgs, function( index, img ) {
        var str = '{$item_template}';
        var image = '{$image}';
        $(image).attr('src', img.src);
        var close = '{$close}';
        var input = '{$input}';
        $(input).attr('value', img.id);
        str = str.replace('{image}', image);
        str = str.replace('{input}', input);
        str = str.replace('{close}', close);
        elements += str;
    });
    $('.multiple-image-container').append(elements);
  }
});
$('.{$removeButtonClass}').on('click', function(e){
  e.preventDefault();
  $(this).closest('.image-item').fadeOut(300, function(){ $(this).remove(); });
  return false;
})";
    }

    protected function registerClientScript()
    {
        $js = [];
        $view = $this->getView();
        $js[] = $this->getScriptCode();
        // $js[] = "tinymce.init($options);";
        $view->registerJs(implode("\n", $js));
    }


}