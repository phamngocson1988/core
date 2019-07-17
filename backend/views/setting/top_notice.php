<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use unclead\multipleinput\MultipleInput;

?>
<!-- BEGIN PAGE BAR -->
<div class="page-bar">
  <ul class="page-breadcrumb">
    <li>
      <a href="/"><?=Yii::t('app', 'home')?></a>
      <i class="fa fa-circle"></i>
    </li>
    <li>
      <a href="javascript:;"><?=Yii::t('app', 'settings');?></a>
      <i class="fa fa-circle"></i>
    </li>
    <li>
      <span>Thông báo trên đầu trang web</span>
    </li>
  </ul>
</div>
<!-- END PAGE BAR -->
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title">Thông báo trên đầu trang web</h1>
<!-- END PAGE TITLE-->
<div class="row">
  <div class="col-md-12">
    <?php $form = ActiveForm::begin(['options' => ['class' => 'form-horizontal form-row-seperated']]);?>
      <div class="portlet">
        <div class="portlet-title">
          <div class="actions btn-set">
            <a href="{$back}" class="btn default">
            <i class="fa fa-angle-left"></i> <?=Yii::t('app', 'back')?></a>
            <button type="submit" class="btn btn-success">
            <i class="fa fa-check"></i> <?=Yii::t('app', 'save')?>
            </button>
          </div>
        </div>
        <div class="portlet-body">
          <div class="tabbable-bordered">
            <ul class="nav nav-tabs">
              <li class="active">
                <a href="#tab_general" data-toggle="tab"> <?=Yii::t('app', 'main_content')?></a>
              </li>
            </ul>
            <div class="tab-content">
              <div class="tab-pane active" id="tab_general">
                <?php $model->top_notice = @unserialize($model->top_notice);?>
                <?=$form->field($model, 'top_notice')->widget(MultipleInput::className(), [
                  'max' => 10,
                  'columns' => [
                    [
                      'name'  => 'notice',
                      'title' => 'Nội dung thông báo',
                      'enableError' => true
                    ],
                    [
                      'name'  => 'link',
                      'title' => 'Liên kết',
                      'enableError' => true
                    ]
                  ]
                ])->label(false);?>
              </div>
            </div>
          </div>
        </div>
      </div>
    <?php ActiveForm::end()?>
  </div>
</div>
<template id="rule-template" style="display: none">
</template>
<template id="benefit-template" style="display: none">
</template>
<?php
$script = <<< JS
hideAllRuleItems();
function hideAllRuleItems() {
  $( "#rule-container .rule-item" ).appendTo( $( "#rule-template" ) );
}
$('#rules').on('change', function(){
  var val = $(this).val();
  hideAllRuleItems();
  if (!val) return;
  $('#' + val).appendTo( $( "#rule-container" ) );
});

hideAllBenefitItems();
function hideAllBenefitItems() {
  $( "#benefit-container .benefit-item" ).appendTo( $( "#benefit-template" ) );
}
$('#benefits').on('change', function(){
  var val = $(this).val();
  hideAllBenefitItems();
  if (!val) return;
  $('#' + val).appendTo( $( "#benefit-container" ) );
});
JS;
$this->registerJs($script);
?>