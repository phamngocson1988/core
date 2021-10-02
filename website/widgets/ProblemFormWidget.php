<?php
namespace website\widgets;

use Yii;
use yii\base\Widget;
use yii\helpers\Url;
use website\forms\PasswordResetRequestForm;
use website\forms\AskEmailRequestForm;

class ProblemFormWidget extends Widget
{
    public $emailFormId = 'flash-problem-email-form';
    public $phoneFormId = 'flash-problem-phone-form';
    public $emailUrl = 'request-password-reset.html';
    public $phoneUrl = 'request-email-reset.html';

    public function run()
    {
        $emailModel = new PasswordResetRequestForm();
        $phoneModel = new AskEmailRequestForm();
        $this->registerClientScript();
        return $this->render('problem', [
            'emailModel' => $emailModel, 
            'phoneModel' => $phoneModel, 
            'emailFormId' => $this->emailFormId, 
            'phoneFormId' => $this->phoneFormId, 
            'emailUrl' => $this->emailUrl,
            'phoneUrl' => $this->phoneUrl
        ]);
    }

    protected function getScriptCode()
    {
        $emailFormId = $this->emailFormId;
        $phoneFormId = $this->phoneFormId;
        return "
$('html').on('submit', 'form#$emailFormId', function() {
    var form = $(this);
    $.ajax({
        url: form.attr('action'),
        type: form.attr('method'),
        dataType : 'json',
        data: form.serialize(),
        success: function (result, textStatus, jqXHR) {
            console.log(result);
            if (result.status == false) {
                toastr.error(result.errors);
                return false;
            } else {
                setTimeout(() => {  
                    location.reload();
                }, 2000);
                toastr.success('Your request is sent. Please check your email'); 
            }
        },
    });
    return false;
});
$('html').on('submit', 'form#$phoneFormId', function() {
    var form = $(this);
    $.ajax({
        url: form.attr('action'),
        type: form.attr('method'),
        dataType : 'json',
        data: form.serialize(),
        success: function (result, textStatus, jqXHR) {
            console.log(result);
            if (result.status == false) {
                toastr.error(result.errors);
                return false;
            } else {
                $('#email_suggestion').html(result.email);
            }
        },
    });
    return false;
});
$('.show_find_email_button').on('click', function() {
    $('.forgot-password').toggle('fast');
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