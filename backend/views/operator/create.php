<?php
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\helpers\Html;

$this->registerCssFile('@web/vendor/assets/global/plugins/bootstrap-tagsinput/bootstrap-tagsinput.css', ['depends' => ['\yii\bootstrap\BootstrapAsset']]);
$this->registerCssFile('@web/vendor/assets/global/plugins/bootstrap-tagsinput/bootstrap-tagsinput-typeahead.css', ['depends' => ['\yii\bootstrap\BootstrapAsset']]);
$this->registerJsFile('@web/vendor/assets/global/plugins/bootstrap-tagsinput/bootstrap-tagsinput.min.js', ['depends' => '\backend\assets\AppAsset']);
$this->registerJsFile('@web/vendor/assets/global/plugins/typeahead/handlebars.min.js', ['depends' => '\backend\assets\AppAsset']);
$this->registerJsFile('@web/vendor/assets/global/plugins/typeahead/typeahead.bundle.min.js', ['depends' => '\backend\assets\AppAsset']);
$this->registerCssFile('@web/vendor/assets/global/plugins/bootstrap-select/css/bootstrap-select.css', ['depends' => ['\yii\bootstrap\BootstrapAsset']]);
$this->registerJsFile('@web/vendor/assets/global/plugins/bootstrap-select/js/bootstrap-select.min.js', ['depends' => '\backend\assets\AppAsset']);
$this->registerJsFile('@web/vendor/assets/pages/scripts/components-bootstrap-select.min.js', ['depends' => '\backend\assets\AppAsset']);
$userList = $model->fetchUsers();
?>
<!-- BEGIN PAGE BAR -->
<div class="page-bar">
  <ul class="page-breadcrumb">
    <li>
      <a href="/"><?=Yii::t('app', 'home')?></a>
      <i class="fa fa-circle"></i>
    </li>
    <li>
      <a href="<?=Url::to(['operator/index'])?>"><?=Yii::t('app', 'operator_list');?></a>
      <i class="fa fa-circle"></i>
    </li>
    <li>
      <span><?=Yii::t('app', 'create_operator');?></span>
    </li>
  </ul>
</div>
<!-- END PAGE BAR -->
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title"><?=Yii::t('app', 'create_operator');?></h1>
<!-- END PAGE TITLE-->
<div class="row">
  <div class="col-md-12">
    <?php $form = ActiveForm::begin(['options' => ['class' => 'form-horizontal form-row-seperated']]);?>
      <div class="portlet">
        <div class="portlet-title">
          <div class="actions btn-set">
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
                  <?=$form->field($model, 'name', [
                    'labelOptions' => ['class' => 'col-md-2 control-label'],
                    'inputOptions' => ['class' => 'slug form-control'],
                    'template' => '{label}<div class="col-md-6">{input}{hint}{error}</div>'
                  ])->textInput();?>

                  <?=$form->field($model, 'main_url', [
                    'labelOptions' => ['class' => 'col-md-2 control-label'],
                    'inputOptions' => ['class' => 'form-control'],
                    'template' => '{label}<div class="col-md-6">{input}{hint}{error}</div>'
                  ])->textInput()?>

                  <?=$form->field($model, 'backup_url', [
                    'labelOptions' => ['class' => 'col-md-2 control-label'],
                    'inputOptions' => ['class' => 'form-control'],
                    'template' => '{label}<div class="col-md-6">{input}{hint}{error}</div>'
                  ])->widget(kartik\select2\Select2::classname(), [
                    'options' => ['class' => 'form-control', 'multiple' => 'true'],
                    'pluginOptions' => ['tags' => true]
                  ])?>

                  <?=$form->field($model, 'rebate', [
                    'labelOptions' => ['class' => 'col-md-2 control-label'],
                    'inputOptions' => ['class' => 'form-control', 'type' => 'number'],
                    'template' => '{label}<div class="col-md-6">{input}{hint}{error}</div>'
                  ])->textInput()->label('Rebate(%)')?>

                  <?=$form->field($model, 'withdrawal_limit', [
                    'labelOptions' => ['class' => 'col-md-2 control-label'],
                    'inputOptions' => ['class' => 'form-control', 'type' => 'number'],
                    'template' => '{label}<div class="col-md-6">{input}{hint}{error}</div>'
                  ])->textInput()?>

                  <?=$form->field($model, 'withdrawal_currency', [
                    'labelOptions' => ['class' => 'col-md-2 control-label'],
                    'inputOptions' => ['class' => 'form-control'],
                    'template' => '{label}<div class="col-md-6">{input}{hint}{error}</div>'
                  ])->dropdownList($model->fetchCurrency())?>

                  <?=$form->field($model, 'withdrawal_time', [
                    'labelOptions' => ['class' => 'col-md-2 control-label'],
                    'inputOptions' => ['class' => 'form-control'],
                    'template' => '{label}<div class="col-md-6">{input}{hint}{error}</div>'
                  ])->dropdownList($model->fetchWithdrawTime())?>

                  <?=$form->field($model, 'withdrawal_method', [
                    'labelOptions' => ['class' => 'col-md-2 control-label'],
                    'inputOptions' => ['class' => 'form-control'],
                    'template' => '{label}<div class="col-md-6">{input}{hint}{error}</div>'
                  ])->checkboxList($model->fetchWithdrawMethod(), [
                    'item' => function($index, $label, $name, $checked, $value) {
                      $checkbox = Html::checkbox($name, $checked, ['class' => 'form-check-input', 'value' => $value]);
                      return Html::tag('label', sprintf('%s<span class="form-check-label">%s</span>',$checkbox, $label), ['class' => 'form-check form-check-inline']);
                    }
                  ]);?>
                  <?=$form->field($model, 'owner', [
                    'labelOptions' => ['class' => 'col-md-2 control-label'],
                    'inputOptions' => ['class' => 'form-control'],
                    'template' => '{label}<div class="col-md-6">{input}{hint}{error}</div>'
                  ])->textInput()?>
                  <?=$form->field($model, 'established', [
                    'labelOptions' => ['class' => 'col-md-2 control-label'],
                    'inputOptions' => ['class' => 'form-control'],
                    'template' => '{label}<div class="col-md-6">{input}{hint}{error}</div>'
                  ])->dropdownList($model->fetchEstablishedYear())?>
                  <?=$form->field($model, 'support_email', [
                    'labelOptions' => ['class' => 'col-md-2 control-label'],
                    'inputOptions' => ['class' => 'form-control'],
                    'template' => '{label}<div class="col-md-6">{input}{hint}{error}</div>'
                  ])->textInput()?>
                  <?=$form->field($model, 'support_phone', [
                    'labelOptions' => ['class' => 'col-md-2 control-label'],
                    'inputOptions' => ['class' => 'form-control'],
                    'template' => '{label}<div class="col-md-6">{input}{hint}{error}</div>'
                  ])->textInput()?>
                  <?=$form->field($model, 'livechat_support', [
                    'labelOptions' => ['class' => 'col-md-2 control-label'],
                    'inputOptions' => ['class' => 'form-control'],
                    'template' => '{label}<div class="col-md-6">{input}{hint}{error}</div>'
                  ])->dropdownList($model->fetchLiveChat())?>

                  <?=$form->field($model, 'license', [
                    'labelOptions' => ['class' => 'col-md-2 control-label'],
                    'inputOptions' => ['class' => 'form-control'],
                    'template' => '{label}<div class="col-md-6">{input}{hint}{error}</div>'
                  ])->dropdownList($model->fetchLicense(), ['prompt' => Yii::t('app', 'choose_license')])?>

                  <?=$form->field($model, 'support_currency', [
                    'labelOptions' => ['class' => 'col-md-2 control-label'],
                    'inputOptions' => ['class' => 'form-control'],
                    'template' => '{label}<div class="col-md-6">{input}{hint}{error}</div>'
                  ])->checkboxList($model->fetchCurrency(), [
                    'item' => function($index, $label, $name, $checked, $value) {
                      $checkbox = Html::checkbox($name, $checked, ['class' => 'form-check-input', 'value' => $value]);
                      return Html::tag('label', sprintf('%s<span class="form-check-label">%s</span>',$checkbox, $label), ['class' => 'form-check form-check-inline']);
                    }
                  ])?>
                  <?=$form->field($model, 'support_language', [
                    'labelOptions' => ['class' => 'col-md-2 control-label'],
                    'inputOptions' => ['class' => 'form-control'],
                    'template' => '{label}<div class="col-md-6">{input}{hint}{error}</div>'
                  ])->checkboxList($model->fetchLanguage(), [
                    'item' => function($index, $label, $name, $checked, $value) {
                      $checkbox = Html::checkbox($name, $checked, ['class' => 'form-check-input', 'value' => $value]);
                      return Html::tag('label', sprintf('%s<span class="form-check-label">%s</span>',$checkbox, $label), ['class' => 'form-check form-check-inline']);
                    }
                  ]);?>
                  <?=$form->field($model, 'product', [
                    'labelOptions' => ['class' => 'col-md-2 control-label'],
                    'inputOptions' => ['class' => 'form-control'],
                    'template' => '{label}<div class="col-md-6">{input}{hint}{error}</div>'
                  ])->checkboxList($model->fetchProduct(), [
                    'item' => function($index, $label, $name, $checked, $value) {
                      $checkbox = Html::checkbox($name, $checked, ['class' => 'form-check-input', 'value' => $value]);
                      return Html::tag('label', sprintf('%s<span class="form-check-label">%s</span>',$checkbox, $label), ['class' => 'form-check form-check-inline']);
                    }
                  ]);?>
                  <?=$form->field($model, 'deposit_method', [
                    'labelOptions' => ['class' => 'col-md-2 control-label'],
                    'inputOptions' => ['class' => 'form-control'],
                    'template' => '{label}<div class="col-md-6">{input}{hint}{error}</div>'
                  ])->checkboxList($model->fetchDepositMethod(), [
                    'item' => function($index, $label, $name, $checked, $value) {
                      $checkbox = Html::checkbox($name, $checked, ['class' => 'form-check-input', 'value' => $value]);
                      return Html::tag('label', sprintf('%s<span class="form-check-label">%s</span>',$checkbox, $label), ['class' => 'form-check form-check-inline']);
                    }
                  ]);?>
                  <?=$form->field($model, 'overview', [
                    'labelOptions' => ['class' => 'col-md-2 control-label'],
                    'inputOptions' => ['class' => 'form-control'],
                    'template' => '{label}<div class="col-md-6">{input}{hint}{error}</div>'
                  ])->textArea()?>

                  <?=$form->field($model, 'admin_id', [
                    'labelOptions' => ['class' => 'col-md-2 control-label'],
                    'inputOptions' => ['class' => 'form-control input-large'],
                    'template' => '{label}<div class="col-md-6">{input}{hint}{error}</div>'
                  ])->widget(kartik\select2\Select2::classname(), [
                    'data' => $userList,
                    'options' => ['class' => 'form-control', 'placeholder' => 'Select an admin ...'],
                  ])?>

                  <?=$form->field($model, 'subadmin_ids', [
                    'labelOptions' => ['class' => 'col-md-2 control-label'],
                    'inputOptions' => ['class' => 'form-control input-large'],
                    'template' => '{label}<div class="col-md-6">{input}{hint}{error}</div>'
                  ])->widget(kartik\select2\Select2::classname(), [
                    'data' => $userList,
                    'options' => ['class' => 'form-control', 'multiple' => 'true'],
                  ])?>
                  <?=$form->field($model, 'moderator_ids', [
                    'labelOptions' => ['class' => 'col-md-2 control-label'],
                    'inputOptions' => ['class' => 'form-control input-large'],
                    'template' => '{label}<div class="col-md-6">{input}{hint}{error}</div>'
                  ])->widget(kartik\select2\Select2::classname(), [
                    'data' => $userList,
                    'options' => ['class' => 'form-control', 'multiple' => 'true'],
                  ])?>

                  <?=$form->field($model, 'language', [
                    'labelOptions' => ['class' => 'col-md-2 control-label'],
                    'inputOptions' => ['class' => 'form-control', 'disabled' => true, 'name' => 'language'],
                    'template' => '{label}<div class="col-md-6">{input}{hint}{error}</div>'
                  ])->dropDownList($model->fetchLanguages())?>
                  <?=$form->field($model, 'language', [
                    'options' => ['tag' => false],
                    'template' => '{input}'
                  ])->hiddenInput()?>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <?php ActiveForm::end()?>
  </div>
</div>