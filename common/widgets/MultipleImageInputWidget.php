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
    
    // Items
    public $itemOptions = [
        'size' => '300x300',
        'tag' => 'div',
        'options' => ['class' => 'col-md-2']
    ];
    public $item_template = "<div class='thumbnail'>{image}{input}{close}</div>";

    // Container
    public $containerOptions = [
        'tag' => 'div',
        'options' => ['class' => 'row']
    ];
    public $template = "{items}{choose_button}";

    // Buttons
    public $chooseButtonOptions = [
        'tag' => 'span', 
        'options' => ['class' => 'btn default']
    ];
    public $parts = [
        '{items}' => '',
        '{choose_button}' => '',
    ];

    protected $_hash = '';

    public function init()
    {
        $this->generateHash();
        // Init item
        $itemOptions = (array)$this->itemOptions;
        $options = ArrayHelper::getValue($this->itemOptions, 'options', []);
        $itemClass = ArrayHelper::getValue($options, 'class', '');
        $itemIdentifier = $this->getItemIdentifier();
        $itemClass .= " $itemIdentifier";
        $this->itemOptions['options']['class'] = $itemClass;
        $itemTag = ArrayHelper::getValue($this->itemOptions, 'tag', 'div');
        $this->itemOptions['tag'] = $itemTag;
        $size = ArrayHelper::getValue($this->itemOptions, 'size', '300x300');
        $this->itemOptions['size'] = $size;

        // Init container
        $containerTag = ArrayHelper::getValue($this->containerOptions, 'tag', 'div');
        $this->containerOptions['tag'] = $containerTag;
        $conOptions = ArrayHelper::getValue($this->containerOptions, 'options', []);
        $containerClass = ArrayHelper::getValue($conOptions, 'class', '');
        $containerIdentifier = $this->getContainerIdentifier();
        $containerClass .= " $containerIdentifier";
        $this->containerOptions['options']['class'] = $containerClass;

        parent::init();
    }

    protected function generateHash()
    {
        if (!$this->_hash) {
            $this->_hash = Yii::$app->security->generateRandomString();
        }
        return $this->_hash;
    }

    public function run()
    {
        $id = $this->options['id'];
        $items = $this->generateItems();
        $containerTag = ArrayHelper::getValue($this->containerOptions, 'tag', 'div');
        $conOptions = ArrayHelper::getValue($this->containerOptions, 'options', []);
        $container = Html::tag($containerTag, $items, $conOptions);        
        $chooseButton = $this->generateChooseButton();
        $elements = ['{items}' => $container, '{choose_button}' => $chooseButton];
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
        $size = $this->getItemSize();

        $itemOptions = (array)$this->itemOptions;
        $tag = ArrayHelper::remove($itemOptions, 'tag', 'div');
        $options = ArrayHelper::remove($itemOptions, 'options', []);

        foreach ($items as $no => $item) {
            $obj = Image::findOne($item);
            if (!$obj) continue;
            $url = $obj->getUrl($size);
            $image = Html::img($url, []);
            $input = $this->generateInput($no);
            $close = $this->generateCloseButton();
            $item = strtr($this->item_template, ['{image}' => $image, '{input}' => $input, '{close}' => $close]);
            $elements .= Html::tag($tag, $item, $options);
        }
        return $elements;
    }

    protected function getItemSize()
    {
        return ArrayHelper::getValue($this->itemOptions, 'size', '300x300');
    }

    protected function getItemIdentifier()
    {
        return sprintf("%s-%s", 'item', $this->generateHash());
    }

    protected function getContainerIdentifier()
    {
        return sprintf("%s-%s", 'container', $this->generateHash());
    }

    protected function generateInput($no = null)
    {
        if ($this->hasModel()) {
            $attribute = "$this->attribute[$no]";
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

        // Item
        $size = $this->getItemSize();
        $itemIdentifier = $this->getItemIdentifier();
        $itemTag = ArrayHelper::getValue($this->itemOptions, 'tag', 'div');
        $options = ArrayHelper::getValue($this->itemOptions, 'options', []);
        $item_template = addslashes(Html::tag($itemTag, $this->item_template, $options));

        // Container
        $containerIdentifier = $this->getContainerIdentifier();

        $close = addslashes($this->generateCloseButton());
        $input = addslashes($this->generateInput());
        $image = addslashes(Html::img("", []));
        return "
if (!{$manager}) {
    var {$manager} = new ImageManager();
}
$('#{$chooseButtonId}').selectImage({$manager}, {
  type: 'multiple',
  size: '{$size}',
  callback: function(imgs) {
    $.each(imgs, function( index, img ) {
        var str = '{$item_template}';
        var image = $('<div/>').append('{$image}');
        image.find('img').attr('src', img.src);
        var close = '{$close}';
        var input = $('<div/>').append('{$input}');
        input.find('input').val(img.id);
        str = str.replace('{image}', image.html());
        str = str.replace('{input}', input.html());
        str = str.replace('{close}', close);
        $('.{$containerIdentifier}').append(str);
    });
  }
});
$('.{$containerIdentifier}').on('click', '.{$removeButtonClass}', function(e){
  e.preventDefault();
  $(this).closest('.{$itemIdentifier}').fadeOut(300, function(){ $(this).remove(); });
  return false;
})";
    }

    protected function registerClientScript()
    {
        $js = [];
        $view = $this->getView();
        $js[] = $this->getScriptCode();
        $view->registerJs(implode("\n", $js));
    }


}