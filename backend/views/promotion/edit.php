<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use dosamigos\datepicker\DatePicker;
use dosamigos\datepicker\DateRangePicker;
use yii\web\JsExpression;
use common\models\Promotion;
use common\widgets\TinyMce;
?>
<!-- BEGIN PAGE BAR -->
<div class="page-bar">
  <ul class="page-breadcrumb">
    <li>
      <a href="/"><?=Yii::t('app', 'home')?></a>
      <i class="fa fa-circle"></i>
    </li>
    <li>
      <a href="<?=Url::to(['promotion/index'])?>">Quản lý khuyến mãi</a>
      <i class="fa fa-circle"></i>
    </li>
    <li>
      <span>Chỉnh sửa khuyến mãi</span>
    </li>
  </ul>
</div>
<!-- END PAGE BAR -->
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title">Chỉnh sửa khuyến mãi</h1>
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
                <div class="form-body">
                  <?=$form->field($model, 'title', [
                    'labelOptions' => ['class' => 'col-md-2 control-label'],
                    'inputOptions' => ['class' => 'form-control'],
                    'template' => '{label}<div class="col-md-6">{input}{hint}{error}</div>'
                  ])->textInput()->label('Tiêu đề khuyến mãi')?>

                  <?=$form->field($model, 'code', [
                    'labelOptions' => ['class' => 'col-md-2 control-label'],
                    'inputOptions' => ['class' => 'form-control', 'disabled' => true],
                    'template' => '{label}<div class="col-md-6">{input}{hint}{error}</div>'
                  ])->textInput()->label('Mã khuyến mãi');?>

                  <?=$form->field($model, 'content', [
                    'labelOptions' => ['class' => 'col-md-2 control-label'],
                    'inputOptions' => ['id' => 'content', 'class' => 'form-control'],
                    'template' => '{label}<div class="col-md-6">{input}{hint}{error}</div>'
                  ])->widget(TinyMce::className(), [
                    'options' => ['rows' => 10]
                  ]);?>
                  
                  <?=$form->field($model, 'image_id', [
                    'labelOptions' => ['class' => 'col-md-2 control-label'],
                    'template' => '{label}<div class="col-md-10">{input}{hint}{error}</div>'
                  ])->widget(common\widgets\ImageInputWidget::className(), [
                    'template' => '<div class="fileinput-preview thumbnail" style="width: 150px; height: 150px;">{image}{input}</div>{choose_button}{cancel_button}',
                    'imageOptions' => ['width' => 150, 'height' => 150]
                  ])->label('Hình ảnh');?>

                  <?=$form->field($model, 'promotion_type', [
                    'labelOptions' => ['class' => 'col-md-2 control-label'],
                    'template' => '{label}<div class="col-md-6">{input}{hint}{error}</div>',
                  ])->dropDownList(['fix' => 'Áp dụng cụ thể', 'percent' => 'Áp dụng theo phần trăm'])->label('Phương thức áp dụng');?>

                  <?=$form->field($model, 'value', [
                    'labelOptions' => ['class' => 'col-md-2 control-label'],
                    'inputOptions' => ['class' => 'form-control'],
                    'template' => '{label}<div class="col-md-6">{input}{hint}{error}</div>'
                  ])->textInput()->label('Giá trị áp dụng')?>

                  <?=$form->field($model, 'promotion_scenario', [
                    'labelOptions' => ['class' => 'col-md-2 control-label'],
                    'template' => '{label}<div class="col-md-6">{input}{hint}{error}</div>',
                  ])->dropDownList([Promotion::SCENARIO_BUY_GEMS => 'Áp dụng khi mua gems của game', Promotion::SCENARIO_BUY_COIN => 'Áp dụng khi nạp tiền ví Kingcoin'])->label('Ngữ cảnh áp dụng');?>

                  <?=$form->field($model, 'user_using', [
                    'labelOptions' => ['class' => 'col-md-2 control-label'],
                    'inputOptions' => ['class' => 'form-control', 'type' => 'number'],
                    'template' => '{label}<div class="col-md-6">{input}{hint}{error}</div>'
                  ])->textInput()->label('Số lần sử dụng cho 1 người dùng')?>

                  <?=$form->field($model, 'from_date', [
                    'labelOptions' => ['class' => 'col-md-2 control-label'],
                    'template' => '{label}<div class="col-md-4">{input}{hint}{error}</div>'
                  ])->widget(DateRangePicker::className(), [
                    'attributeTo' => 'to_date', 
                    'labelTo' => 'Đến',
                    'form' => $form,
                    'clientOptions' => [
                        'autoclose' => true,
                        'format' => 'yyyy-mm-dd',
                        'keepEmptyValues' => true,
                        'todayHighlight' => true
                    ]
                  ])?>

                  <?=$form->field($model, 'game_ids', [
                    'labelOptions' => ['class' => 'col-md-2 control-label'],
                    'template' => '{label}<div class="col-md-4">{input}{hint}{error}</div>',
                  ])->widget(kartik\select2\Select2::classname(), [
                    'data' => ArrayHelper::map($games, 'id', 'title'),
                    'options' => ['class' => 'form-control', 'multiple' => 'true'],
                  ])->label('Áp dụng cho game')?>

                  <?=$form->field($model, 'user_ids', [
                    'labelOptions' => ['class' => 'col-md-2 control-label'],
                    'template' => '{label}<div class="col-md-4">{input}{hint}{error}</div>',
                  ])->widget(kartik\select2\Select2::classname(), [
                    'data' => ArrayHelper::map($users, 'id', 'email'),
                    'options' => ['class' => 'form-control', 'multiple' => 'true'],
                  ])->label('Áp dụng cho khách hàng')?>

                  <?=$form->field($model, 'status', [
                    'labelOptions' => ['class' => 'col-md-2 control-label'],
                    'template' => '{label}<div class="col-md-6">{input}{hint}{error}</div>',
                  ])->dropDownList(['Y' => 'Đã kích hoạt', 'N' => 'Chưa kích hoạt'])->label('Trạng thái');?>

                  <hr><!-- Rule -->
                  <?php 
                  $rules = Promotion::getRuleHandlers();
                  $ruleList = [];
                  foreach ($rules as $identifier => $params) {
                    $ruleList[$identifier] = $params['title'];
                  }
                  ?>
                  <?=$form->field($model, 'rule_name', [
                    'labelOptions' => ['class' => 'col-md-2 control-label'],
                    'inputOptions' => ['class' => 'form-control', 'id' => 'rules'],
                    'template' => '{label}<div class="col-md-6">{input}{hint}{error}</div>',
                  ])->dropDownList($ruleList, ['prompt' => 'Chọn quy định áp dụng'])->label('Quy định áp dụng');?>

                  <div id="rule-container">
                  <?php foreach ($rules as $identifier => $params) {
                    if ($identifier == $model->rule_name) {
                      $rule = $model->getRule();
                    } else {
                      $rule = Yii::createObject($params);
                    }
                    $content = '';
                    foreach ($rule->safeAttributes() as $attr) {
                      $content .= $rule->render($form, $attr, [
                        'options' => ['class' => 'form-group rule', 'id' => $identifier],
                        'labelOptions' => ['class' => 'col-md-2 control-label'],
                        'template' => '{label}<div class="col-md-6">{input}{hint}{error}</div>'
                      ]);
                    }
                    echo Html::tag('div', $content, ['class' => 'rule-item', 'id' => $identifier]);
                  } ?>
                  </div>

                  <hr><!-- Benefit -->
                  <?php 
                  $benefits = Promotion::getBenefitHandlers();
                  $benefitList = [];
                  foreach ($benefits as $identifier => $params) {
                    $benefitList[$identifier] = $params['title'];
                  }
                  ?>
                  <?=$form->field($model, 'benefit_name', [
                    'labelOptions' => ['class' => 'col-md-2 control-label'],
                    'inputOptions' => ['class' => 'form-control', 'id' => 'benefits'],
                    'template' => '{label}<div class="col-md-6">{input}{hint}{error}</div>',
                  ])->dropDownList($benefitList, ['prompt' => 'Chọn giá trị áp dụng'])->label('Giá trị áp dụng');?>
                  
                  <div id="benefit-container">
                  <?php foreach ($benefits as $identifier => $params) {
                    if ($identifier == $model->benefit_name) {
                      $benefit = $model->getBenefit();
                    } else {
                      $benefit = Yii::createObject($params);
                    }
                    $content = '';
                    foreach ($benefit->safeAttributes() as $attr) {
                      $content .= $benefit->render($form, $attr, [
                        'options' => ['class' => 'form-group benefit', 'id' => $identifier],
                        'labelOptions' => ['class' => 'col-md-2 control-label'],
                        'template' => '{label}<div class="col-md-6">{input}{hint}{error}</div>'
                      ]);
                    }
                    echo Html::tag('div', $content, ['class' => 'benefit-item', 'id' => $identifier]);
                  } ?>
                  </div>

                </div>
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
$('#rules').trigger('change');

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
$('#benefits').trigger('change');
JS;
$this->registerJs($script);
?>