<?php
namespace frontend\widgets;

use Yii;
use yii\base\Widget;
use yii\helpers\Url;
use frontend\forms\VerifyPhoneForm;

class VerifyAccountFormWidget extends Widget
{
    public $formId = 'flash-verify-phone-form';
    public $phoneId = 'verify-phone-element';
    public $buttonId = 'verify-phone-button';

    public function run()
    {
        $model = new VerifyPhoneForm();
        $this->registerClientScript();
        return $this->render('verify', [
            'model' => $model, 
            'id' => $this->formId,
            'actionUrl' => $this->getVerifyCodeUrl(),
            'phoneId' => $this->phoneId,
            'buttonId' => $this->buttonId,
        ]);
    }

    protected function getRequestCodeUrl()
    {
        return Url::to(['profile/request-sms-code']);
    }

    protected function getVerifyCodeUrl()
    {
        return Url::to(['profile/verify-sms-code']);
    }

    protected function getScriptCode()
    {
        $id = $this->formId;
        $phoneId = $this->phoneId;
        $buttonId = $this->buttonId;
        $requestSmsCodeUrl = $this->getRequestCodeUrl();
        $verifySmsCodeUrl = $this->getVerifyCodeUrl();
        $profileUrl = Url::to(['profile/index']);
        return "
$('html').on('click', '#$buttonId', function() {
    console.log('verify phone click');
    var phone = $('#$phoneId').val();
    phone = phone.trim();
    if (!phone) return;

    $(this).prop('disabled', true);
    (function(){
      var counter = 60*2;
      $('#$buttonId').html(\"<span id='count'>120</span>\");
      setInterval(function() {
        counter--;
        if (counter >= 0) {
          span = document.getElementById(\"count\");
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
        url: '$requestSmsCodeUrl',
        type: 'post',
        dataType : 'json',
        data: {phone: phone},
        success: function (result, textStatus, jqXHR) {
            console.log(result);
            if (result.status == false) {
                toastr.error(result.errors);
            } else {
                toastr.success('A sms code has just sent to ' + phone);
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