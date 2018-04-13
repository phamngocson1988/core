<?php
namespace common\widgets;

use dosamigos\tinymce\TinyMce as BaseTinyMce;
use dosamigos\tinymce\TinyMceAsset;
use dosamigos\tinymce\TinyMceLangAsset;
use yii\helpers\Url;
use yii\helpers\Json;
use Yii;

/**
 * @link https://github.com/2amigos/yii2-tinymce-widget
 */
class TinyMce extends BaseTinyMce
{
	public $allow_upload_image = true;

	public $clientOptions = [
		'plugins' => [
            "advlist autolink lists link charmap print preview anchor",
            "searchreplace visualblocks code fullscreen",
            "insertdatetime media table contextmenu paste image code"
        ],
        'toolbar' => "undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image",
	];

    public function init()
    {
        parent::init();
        $this->clientOptions['images_upload_handler'] = 'images_upload_handler';
    }

    protected function getImageHanderFunction()
    {
        $url = Url::to(['image/editor']);
        $param = Yii::$app->request->csrfParam;
        $token = Yii::$app->request->csrfToken;
        return "function images_upload_handler(blobInfo, success, failure) {
var xhr, formData;
xhr = new XMLHttpRequest();
xhr.withCredentials = false;
xhr.open('POST', '$url');
xhr.onload = function() {   
  var json;
  if (xhr.status != 200) {
    failure('HTTP Error: ' + xhr.status);
    return;
  }

  json = JSON.parse(xhr.responseText);

  if (!json || typeof json.location != 'string') {
    failure('Invalid JSON: ' + xhr.responseText);
    return;
  }

  success(json.location);
};
formData = new FormData();
formData.append('file', blobInfo.blob(), blobInfo.filename());
formData.append('$param', '$token');
xhr.send(formData);
};";
    }

    protected function registerClientScript()
    {
        $js = [];
        $view = $this->getView();

        TinyMceAsset::register($view);

        $id = $this->options['id'];

        $this->clientOptions['selector'] = "#$id";
        // @codeCoverageIgnoreStart
        if ($this->language !== null && $this->language !== 'en') {
            $langFile = "langs/{$this->language}.js";
            $langAssetBundle = TinyMceLangAsset::register($view);
            $langAssetBundle->js[] = $langFile;
            $this->clientOptions['language_url'] = $langAssetBundle->baseUrl . "/{$langFile}";
        }
        // @codeCoverageIgnoreEnd

        $options = Json::encode($this->clientOptions);
        $regex = "/(?<=\:)\"images_upload_handler\"/";
        $options = preg_replace($regex, "images_upload_handler", $options);
        $js[] = $this->getImageHanderFunction();
        $js[] = "tinymce.init($options);";
        if ($this->triggerSaveOnBeforeValidateForm) {
            $js[] = "$('#{$id}').parents('form').on('beforeValidate', function() { tinymce.triggerSave(); });";
        }
        $view->registerJs(implode("\n", $js));
    }


}