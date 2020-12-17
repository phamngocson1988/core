<?php
namespace frontend\widgets;

use Yii;
use yii\base\Widget;
use frontend\forms\AddStaffForm;
use frontend\models\OperatorStaff;

class AddStaffWidget extends Widget
{
    public $formId = 'add-staff-form';
    public $role;
    public $operator_id;
    public $actionUrl = '';

    public function run()
    {
        $model = new AddStaffForm([
            'role' => $this->role,
            'operator_id' => $this->operator_id
        ]);
        $roleName = $model->getRoleName();
        $title = Yii::t('app', 'Add {role}', ['role' => $roleName]);
        $this->registerClientScript();
        return $this->render('add_staff', [
            'model' => $model, 
            'id' => $this->formId,
            'title' => $title,
            'actionUrl' => $this->actionUrl
        ]);
    }

    protected function getScriptCode()
    {
        $formId = $this->formId;
        $actionUrl = $this->actionUrl;
        return "
$('html').on('submit', 'form#$formId', function() {
    var form = $(this);
    $.ajax({
        url: '$actionUrl',
        type: 'post',
        dataType : 'json',
        data: form.serialize(),
        success: function (result, textStatus, jqXHR) {
            console.log(result);
            if (result.status == false) {
                toastr.error(result.errors);
                return false;
            } else {
                if (result.next) {
                    window.location.href = result.next;
                } else {
                    location.reload();
                }
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