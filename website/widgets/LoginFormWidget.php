<?php
namespace website\widgets;

use Yii;
use yii\base\Widget;
use yii\helpers\Url;
use website\forms\LoginForm;

class LoginFormWidget extends Widget
{
    public $formId = 'flash-login-form';
    protected $url;

    public function run()
    {
        $this->url = Url::to(['site/login'], true);
        $model = new LoginForm();
        $this->registerClientScript();
        return $this->render('login', [
            'model' => $model, 
            'id' => $this->formId,
            'url' => $this->url,
        ]);
    }

    protected function getScriptCode()
    {
        $id = $this->formId;
        $url = $this->url;
        return "
$('html').on('submit', 'form#flash-login-form', function() {
    var form = $(this);
    $.ajax({
        url: '$url',
        type: 'post',
        dataType : 'json',
        data: form.serialize(),
        success: function (result, textStatus, jqXHR) {
            console.log(result);
            if (result.status == false) {
                toastr.error(result.errors);
                return false;
            } else {
                location.reload();
            }
        },
    });
            return false;
});
";
    }

    protected function registerClientScript()
    {
        $js = [];
        $view = $this->getView();
        $js = $this->getScriptCode();
        $view->registerJs($js);
    }


}