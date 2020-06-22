<?php
use yii\helpers\Url;
use yii\widgets\ActiveForm;

$this->registerCssFile('vendor/assets/global/plugins/bootstrap-tagsinput/bootstrap-tagsinput.css', ['depends' => ['\yii\bootstrap\BootstrapAsset']]);
$this->registerCssFile('vendor/assets/global/plugins/bootstrap-tagsinput/bootstrap-tagsinput-typeahead.css', ['depends' => ['\yii\bootstrap\BootstrapAsset']]);


$this->registerJsFile('vendor/assets/global/plugins/bootstrap-tagsinput/bootstrap-tagsinput.min.js', ['depends' => '\backend\assets\AppAsset']);
$this->registerJsFile('vendor/assets/global/plugins/typeahead/handlebars.min.js', ['depends' => '\backend\assets\AppAsset']);
$this->registerJsFile('vendor/assets/global/plugins/typeahead/typeahead.bundle.min.js', ['depends' => '\backend\assets\AppAsset']);
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
      <span><?=Yii::t('app', 'create_operator_meta');?></span>
    </li>
  </ul>
</div>
<!-- END PAGE BAR -->
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title"><?=Yii::t('app', 'create_operator_meta');?></h1>
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
                  <?=$form->field($model, 'product', [
                    'labelOptions' => ['class' => 'col-md-2 control-label'],
                    'inputOptions' => ['class' => 'form-control input-large', 'data-role' => "tagsinput"],
                    'template' => '{label}<div class="col-md-6">{input}{hint}{error}</div>'
                  ])->textInput();?>      

                  <?=$form->field($model, 'deposit_method', [
                    'labelOptions' => ['class' => 'col-md-2 control-label'],
                    'inputOptions' => ['class' => 'form-control input-large', 'data-role' => "tagsinput"],
                    'template' => '{label}<div class="col-md-6">{input}{hint}{error}</div>'
                  ])->textInput();?>      

                  <?=$form->field($model, 'withdrawal_method', [
                    'labelOptions' => ['class' => 'col-md-2 control-label'],
                    'inputOptions' => ['class' => 'form-control input-large', 'data-role' => "tagsinput"],
                    'template' => '{label}<div class="col-md-6">{input}{hint}{error}</div>'
                  ])->textInput();?>        

                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <?php ActiveForm::end()?>
  </div>
</div>