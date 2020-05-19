<?php
namespace website\widgets;

use Yii;
use yii\base\Widget;
use yii\helpers\Url;
use website\forms\UpdateSecureProfileForm;

class SecureFormWidget extends Widget
{
    public $formId = 'flash-secure-form';
    public $url = 'fullfill-profile.html';

    public function run()
    {
        $model = new UpdateSecureProfileForm();
        $model->loadForm();
        $this->registerClientScript();
        return $this->render('secure', ['model' => $model, 'id' => $this->formId, 'url' => $this->url]);
    }

    protected function getScriptCode()
    {
        $id = $this->formId;
        return "
$('html').on('submit', 'form#$id', function() {
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
                    window.location = '/';
                }, 2000);
                toastr.success('Your profile is updated.'); 
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