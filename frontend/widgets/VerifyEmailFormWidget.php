<?php
namespace frontend\widgets;

use Yii;
use yii\base\Widget;
use yii\helpers\Url;
use frontend\forms\VerifyEmailForm;

class VerifyEmailFormWidget extends Widget
{
    public $formId = 'flash-verify-email-form';
    public $buttonId = 'verify-email-button';

    public function run()
    {
        $model = new VerifyEmailForm();
        $this->registerClientScript();
        return $this->render('verify-email', [
            'model' => $model, 
            'id' => $this->formId,
            'actionUrl' => $this->getVerifyCodeUrl(),
            'buttonId' => $this->buttonId,
        ]);
    }

    protected function getRequestCodeUrl()
    {
        return Url::to(['profile/request-email-code']);
    }

    protected function getVerifyCodeUrl()
    {
        return Url::to(['profile/verify-email-code']);
    }

    protected function getScriptCode()
    {
        $id = $this->formId;
        $buttonId = $this->buttonId;
        $requestEmailCodeUrl = $this->getRequestCodeUrl();
        $profileUrl = Url::to(['profile/index']);
        return "
$('html').on('click', '#$buttonId', function() {
    console.log('verify email click');
    $(this).prop('disabled', true);
    (function(){
      var counter = 60*2;
      $('#$buttonId').html(\"<span id='count-verify-email'>120</span>\");
      setInterval(function() {
        counter--;
        if (counter >= 0) {
          span = document.getElementById(\"count-verify-email\");
          span.innerHTML = counter;
        }
        // Display 'counter' wherever you want to display it.
        if (counter === 0) {
            clearInterval(counter);
            $('#$buttonId').html(\"Security token<br /><small>Request Token</small></button>\");
            $('#$buttonId').prop('disabled', false);
        }
      }, 1000);
    })();

    $.ajax({
        url: '$requestEmailCodeUrl',
        type: 'post',
        dataType : 'json',
        success: function (result, textStatus, jqXHR) {
            console.log(result);
            if (result.status == false) {
                toastr.error(result.errors);
            } else {
                toastr.success('An email with verification code has just sent to you.');
            }
        },
    });
    return false;
});
$('html').on('submit', 'form#$id', function() {
    var form = $(this);
    $.ajax({
        url: form.attr('action'),
        type: 'post',
        dataType : 'json',
        data: form.serialize(),
        success: function (result, textStatus, jqXHR) {
            console.log(result);
            form[0].reset();
            if (result.status == false) {
                toastr.error(result.errors);
                return false;
            } else {
                window.location.href = '$profileUrl';
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