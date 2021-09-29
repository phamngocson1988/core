<?php
namespace website\widgets;

use Yii;
use yii\base\Widget;
use yii\helpers\Url;
use website\forms\LoginForm;

class LoginFormWidget extends Widget
{
    public $formId = 'flash-login-form';

    public function run()
    {
        $model = new LoginForm();
        $this->registerClientScript();
        $loginScenario = LoginForm::SCENARIO_LOGIN;
        $verifyScenario = LoginForm::SCENARIO_VERIFY;
        return $this->render('login', [
            'model' => $model, 
            'id' => $this->formId,
            'scenario' => LoginForm::SCENARIO_LOGIN,
            'loginScenario' => $loginScenario,
            'verifyScenario' => $verifyScenario,
        ]);
    }

    protected function getScriptCode()
    {
        $id = $this->formId;
        $loginUrl = Url::to(['site/login'], true);
        $loginScenario = LoginForm::SCENARIO_LOGIN;
        $verifyScenario = LoginForm::SCENARIO_VERIFY;
        return "
$('html').on('submit', 'form#flash-login-form', function() {
    showLoader();
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
                let scenario = $('#scenario').val();
                if (scenario === '$loginScenario') {
                    $('#scenario').val('$verifyScenario');
                    // $('#securityCode').closest('div').attr('style', 'display:block');
                    // $('#back-login-form').attr('style', 'display:block');
                    // $('#username').attr('style', 'display:none');
                    // $('#password').attr('style', 'display:none');
                    // $('#rememberMe').attr('style', 'display:none');
                    $(\"[scenario='$loginScenario']\").attr('style', 'display:none');
                    $(\"[scenario='$verifyScenario']\").attr('style', 'display:block');
                    $('#submit').text('Verify');
                } else {
                    location.reload();
                }
            }
        },
        complete: hideLoader
    });
    return false;
});
$('html').on('click', '#back-login-form', function() {
    // $('#securityCode').closest('div').attr('style', 'display:none');
    // $('#back-login-form').attr('style', 'display:none');
    // $('#username').attr('style', 'display:block');
    // $('#password').attr('style', 'display:block');
    // $('#rememberMe').attr('style', 'display:block');

    $(\"[scenario='$verifyScenario']\").attr('style', 'display:none');
    $(\"[scenario='$loginScenario']\").attr('style', 'display:block');
    $('#submit').text('Login');
    $('#scenario').val('$loginScenario');
});
$('html').on('click', '#login-resend-code-button', function() {
    $('#scenario').val('$loginScenario');
    $('html').find('form#flash-login-form').submit();
});
";
    }

    protected function getCssCode()
    {
        return '.hint-block {color: #ffc107; margin-left: 10px;}';
    }

    protected function registerClientScript()
    {
        $js = [];
        $view = $this->getView();
        $js = $this->getScriptCode();
        $view->registerJs($js);
        $css = $this->getCssCode();
        $view->registerCss($css);
    }


}