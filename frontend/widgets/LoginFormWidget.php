<?php
namespace frontend\widgets;

use Yii;
use yii\base\Widget;
use frontend\forms\LoginForm;

class LoginFormWidget extends Widget
{
    public $formId = 'flash-login-form';
    public $loginUrl = '';
    public function run()
    {
        $model = new LoginForm();
        $this->registerClientScript();
        return $this->render('login', [
            'model' => $model, 
            'id' => $this->formId,
            'loginUrl' => $this->loginUrl
        ]);
    }

    protected function getScriptCode()
    {
        $formId = $this->formId;
        $loginUrl = $this->loginUrl;
        return "
$('html').on('submit', 'form#$formId', function() {
    var form = $(this);
    $.ajax({
        url: '$loginUrl',
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