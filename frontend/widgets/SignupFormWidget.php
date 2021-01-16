<?php
namespace frontend\widgets;

use Yii;
use yii\base\Widget;
use yii\helpers\Url;
use frontend\forms\SignupForm;

class SignupFormWidget extends Widget
{
    public $formId = 'flash-signup-form';
    public function run()
    {
        $model = new SignupForm();
        $this->registerClientScript();
        return $this->render('signup', ['model' => $model, 'id' => $this->formId]);
    }

    protected function getScriptCode()
    {
        $id = $this->formId;
        $profileUrl = Url::to(['profile/index', '#' => 'modalSecure']);
        return "
$('html').on('submit', 'form#$id', function() {
    var form = $(this);
    $.ajax({
        url: '/signup.html',
        type: 'post',
        dataType : 'json',
        data: form.serialize(),
        success: function (result, textStatus, jqXHR) {
            console.log(result);
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