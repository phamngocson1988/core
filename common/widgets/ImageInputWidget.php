<?php
namespace common\widgets;

use yii\widgets\InputWidget;
use yii\helpers\Html;
use Yii;

class ImageInputWidget extends InputWidget
{
    public $imageSrc;
    public $imageOptions;
	public $multiple = false;
    public $template = "{image}{input}{buttons}";
    public $clientOptions = [];

    public function run()
    {
        $id = $this->options['id'];
        if ($this->hasModel()) {
            $input = Html::activeHiddenInput($this->model, $this->attribute, $this->options);
        } else {
            $input = Html::hiddenInput($this->name, $this->value, $this->options);
        }

        $imageOptions = (array)$this->imageOptions;
        $imageOptions['id'] = 'image_' . $id;
        $image = Html::img($this->imageSrc, $imageOptions);
        $chooseButton = Html::tag('span', Yii::t('app', 'choose_image'), ['class' => 'btn default', 'for' => $id, 'id' => 'choose_' . $id]);
        $cancelButton = Html::tag('span', Yii::t('app', 'remove'), ['class' => 'btn red', 'for' => $id, 'id' => 'cancel_' . $id]);"</span>";
        $buttons = "<div>$chooseButton $cancelButton</div>";
        $terms = ["{image}", "{input}", "{buttons}"];
        $elements   = [$image, $input, $buttons];
        echo str_replace($terms, $elements, $this->template);
        $this->registerClientScript();
    }

    protected function getScriptCode()
    {
        $id = $this->options['id'];
        $imageId = "image_$id";
        $chooseButtonId = "choose_$id";
        $cancelButtonId = "cancel_$id";
        return "var manager{$id} = new ImageManager();
$('#{$chooseButtonId}').selectImage(manager{$id}, {
  callback: function(img) {
    var thumb = img.src;
    var id = img.id;
    $('#{$imageId}').attr('src', thumb).removeClass('hide');
    $('#{$imageId}').val(id);
  }
});";
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