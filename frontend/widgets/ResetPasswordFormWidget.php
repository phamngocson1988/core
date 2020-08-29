<?php
namespace frontend\widgets;

use Yii;
use yii\base\Widget;
use frontend\forms\PasswordResetRequestForm;

class ResetPasswordFormWidget extends Widget
{
    public $formId = 'flash-reset-password-form';
    public $requestUrl = '';
    public function run()
    {
        $model = new PasswordResetRequestForm();
        $this->registerClientScript();
        return $this->render('resetPassword', [
            'model' => $model, 
            'id' => $this->formId,
            'requestUrl' => $this->requestUrl
        ]);
    }

    protected function getScriptCode()
    {
        $formId = $this->formId;
        $requestUrl = $this->requestUrl;
        return "
$('html').on('submit', 'form#$formId', function() {
    var form = $(this);
    $.ajax({
        url: '$requestUrl',
        type: 'post',
        dataType : 'json',
        data: form.serialize(),
        success: function (result, textStatus, jqXHR) {
            console.log(result);
            if (result.status == false) {
                toastr.error(result.errors);
                return false;
            } else {
                toastr.success(result.message);
                setTimeout(() => {  
                    location.reload();
                }, 1000);
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