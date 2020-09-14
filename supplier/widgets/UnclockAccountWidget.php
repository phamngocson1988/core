<?php
namespace supplier\widgets;

use yii\base\Widget;
use supplier\forms\UnclockAccountForm;
use yii\helpers\Url;

class UnclockAccountWidget extends Widget
{
    public $formId;
    public $unclockUrl;
    public function init()
    {
        parent::init();
        $this->unclockUrl = $this->unclockUrl ? $this->unclockUrl : Url::to(['site/unclock-account']);
        $this->formId = $this->formId ? $this->formId : 'UnclockAccountWidgetID';
    }
	public function run()
    {
        $model = new UnclockAccountForm();
        $this->registerClientScript();
        return $this->render('unclockaccount', [
            'model' => $model, 
            'unclockUrl' => $this->unclockUrl, 
            'formId' => $this->formId, 
        ]);
    }

    protected function registerClientScript()
    {
        $formId = $this->formId;
        $unclockUrl = $this->unclockUrl;
        $js = [];
        $view = $this->getView();
        $js[] = "
$('html').on('click', 'form#$formId button', function() {
    var form = $(this).closest('form');
    $.ajax({
        url: '$unclockUrl',
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
        $view->registerJs(implode("\n", $js));
    }
}