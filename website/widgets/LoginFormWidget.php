<?php
namespace website\widgets;

use Yii;
use yii\base\Widget;
use website\forms\LoginForm;

class LoginFormWidget extends Widget
{
    public $formId = 'flash-login-form';
    public function run()
    {
        $model = new LoginForm();
        $this->registerClientScript();
        return $this->render('login', ['model' => $model, 'id' => $this->formId]);
    }

    protected function getScriptCode()
    {
        $id = $this->formId;
        return "
$('html').on('submit', 'form#flash-login-form', function() {
    var form = $(this);
    $.ajax({
        url: '/login.html',
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