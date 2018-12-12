<?php
namespace common\widgets;

use yii\widgets\InputWidget;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use common\models\Image;
use Yii;

class ImageInputWidget extends InputWidget
{
    public $imageSrc;
    public $imageOptions;
    public $chooseButtonOptions = ['tag' => 'span', 'options' => ['class' => 'btn default']];
    public $cancelButtonOptions = ['tag' => 'span', 'options' => ['class' => 'btn red']];
    public $handler = 'manager';
	public $multiple = false;
    public $template = "{image}{input}<div>{choose_button}{cancel_button}</div>";
    public $parts = [
        '{image}' => '',
        '{input}' => '',
        '{choose_button}' => '',
        '{cancel_button}' => '',
    ];
    public $clientOptions = [];

    public function init()
    {
        parent::init();
        $id = $this->options['id'];
        
    }
    public function run()
    {
        $id = $this->options['id'];
        $input = $this->generateInput();
        $image = $this->generateImage();
        $chooseButton = $this->generateChooseButton();
        $cancelButton = $this->generateCancelButton();
        $terms = ["{image}", "{input}", "{choose_button}", "{cancel_button}"];
        $elements   = [$image, $input, $chooseButton, $cancelButton];
        echo strtr($this->template, $this->parts);
        $this->registerClientScript();
    }

    protected function generateImage()
    {
        $id = $this->options['id'];
        $imageOptions = (array)$this->imageOptions;
        $imageOptions['id'] = isset($imageOptions['id']) ? $imageOptions['id'] : 'image_' . $id;
        $size = ArrayHelper::getValue($imageOptions, 'size');

        $images = [];
        $images[] = $this->imageSrc;
        $model = $this->model; 
        $attribute = $this->attribute;
        $value = $model->$attribute;
        $imageObject = Image::findOne($value);
        $images[] = ($imageObject) ? $imageObject->getUrl($size) : null;

        $imageOptions['alter-src'] = isset($imageOptions['alter-src']) ? $imageOptions['alter-src'] : Yii::$app->image->default_image;    
        $images[] = $imageOptions['alter-src'];
        $images = array_filter($images);

        $mainImage = reset($images);
        $this->imageSrc = $mainImage;
        $this->imageOptions = $imageOptions;
        $this->parts['{image}'] = Html::img($this->imageSrc, $this->imageOptions);
        return $this->parts['{image}'];
    }

    protected function generateInput()
    {
        if ($this->hasModel()) {
            $this->parts['{input}'] = Html::activeHiddenInput($this->model, $this->attribute, $this->options);
        } else {
            $this->parts['{input}'] = Html::hiddenInput($this->name, $this->value, $this->options);
        }
        return $this->parts['{input}'];
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

    protected function generateCancelButton()
    {
        $id = $this->options['id'];
        $cancelOptions = (array)$this->cancelButtonOptions;
        $tag = ArrayHelper::getValue($cancelOptions, 'tag', 'span');
        $options = ArrayHelper::getValue($cancelOptions, 'options', []);
        $options['id'] = "cancel_$id";
        $this->parts['{cancel_button}'] = Html::tag($tag, Yii::t('app', 'cancel_image'), $options);
        return $this->parts['{cancel_button}'];
    }

    protected function getScriptCode()
    {
        $id = $this->options['id'];
        $imageId = "image_$id";
        $chooseButtonId = "choose_$id";
        $cancelButtonId = "cancel_$id";
        $manager = $this->handler;
        $clientOptions = (array)$this->clientOptions;
        $clientOptions = json_encode($clientOptions);
        return "
if (!{$manager}) {
    var {$manager} = new ImageManager({$clientOptions});
}
$('#{$chooseButtonId}').selectImage({$manager}, {
  callback: function(img) {
    var thumb = img.src;
    var id = img.id;
    $('#{$imageId}').attr('src', thumb).removeClass('hide');
    $('#{$id}').val(id);
  }
});
$('#{$cancelButtonId}').on('click', function(e){
  e.preventDefault();
  var alter = $('#{$imageId}').attr('alter-src');
  $('#{$imageId}').attr('src', alter);
  $('#{$id}').val('');
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