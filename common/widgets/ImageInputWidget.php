<?php
namespace common\widgets;

use yii\widgets\InputWidget;
use Yii;

class ImageInputWidget extends InputWidget
{
    public $imageId;
    public $imageSrc;
    public $imageOptions;
	public $multiple = false;
    public $template = "{image}{input}";
    public $clientOptions = [];

    public function init()
    {
        if (!$this->imageId) {
            $this->imageId = time();
        }
        parent::inti();
    }
    public function run()
    {
        if ($this->hasModel()) {
            $input = Html::hiddenInput($this->model, $this->attribute, $this->options);
        } else {
            $input = Html::hiddenInput($this->name, $this->value, $this->options);
        }
        $image = Html::img($this->imageSrc, $this->imageOptions);
        $terms = ["{image}", "{input}"];
        $elements   = [$image, $input];
        echo str_replace($terms, $elements, $this->template);
        $this->registerClientScript();
    }

    protected function getScriptCode()
    {
        $id = $this->options['id'];
        $imageId = $this->imageId;
        return "var manager{$id} = new ImageManager();
$('#{$id}').selectImage(manager{$id}, {
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