<?php
namespace website\widgets;

use Yii;
use yii\base\Widget;
use yii\helpers\Url;
use website\forms\ChangePasswordForm;

class ChangePasswordFormWidget extends Widget
{
    public $formId = 'flash-change-password-form';
    public function run()
    {
        $model = new ChangePasswordForm();
        $this->registerClientScript();
        return $this->render('change-password', [
            'model' => $model, 
            'id' => $this->formId,
            'actionUrl' => Url::to(['profile/password']),
        ]);
    }

    protected function getScriptCode()
    {
        $id = $this->formId;
        $profileUrl = Url::to(['profile/index']);
        return "
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