<?php
namespace frontend\widgets;

use yii\base\Widget;
use frontend\forms\LoginForm;

class LoginPopupWidget extends Widget
{
	public $popup_id = 'popup-login-modal'; 

    public function run()
    {
    	$model = new LoginForm();
    	$this->registerClientScript();
        return $this->render('login_popup', ['model' => $model, 'popup_id' => $this->popup_id]);
    }

    protected function getScriptCode()
    {
        $id = $this->popup_id;
        return "
$('#{$id} form').on('submit', function(e) {
  e.preventDefault();
  e.stopImmediatePropagation();
  var form = $(this);
  $.ajax({
      url: form.attr('action'),
      type: form.attr('method'),
      dataType : 'json',
      data: form.serialize(),
      success: function (result, textStatus, jqXHR) {
        if (!result.status) {
          if (result.errors) {
            alert(result.errors);
          }
        } else {
          window.location.reload();
        }
      },
  });
  return false;
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