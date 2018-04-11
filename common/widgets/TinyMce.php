<?php
namespace common\widgets;

use dosamigos\tinymce\TinyMce as BaseTinyMce;
use yii\helpers\Url;

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
		if ($this->allow_upload_image) {
			$url = Url::to(['@backend/image/editor']);
			$func = "function (blobInfo, success, failure) {
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

xhr.send(formData);
});";
			$this->clientOptions['images_upload_handler'] = $func;
		}
	}
}