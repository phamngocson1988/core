<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\widgets\ImageInputWidget;
use backend\components\datepicker\DatePicker;
?>  

<!-- BEGIN PAGE BAR -->
<div class="page-bar">
  <ul class="page-breadcrumb">
    <li>
      <a href="/"><?=Yii::t('app', 'home')?></a>
      <i class="fa fa-circle"></i>
    </li>
    <li>
      <a href="<?=Url::to(['ads/index'])?>"><?=Yii::t('app', 'manage_ads');?></a>
      <i class="fa fa-circle"></i>
    </li>
    <li>
      <span><?=Yii::t('app', 'edit_ads');?></span>
    </li>
  </ul>
</div>
<!-- END PAGE BAR -->
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title"><?=Yii::t('app', 'edit_ads');?></h1>
<!-- END PAGE TITLE-->
<div class="row">
  <div class="col-md-12">
    <?php $form = ActiveForm::begin(['options' => ['class' => 'form-horizontal form-row-seperated']]);?>
      <div class="portlet">
        <div class="portlet-title">
          <div class="caption"><?=Yii::t('app', 'edit_ads');?></div>
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
                <a href="#tab_general" data-toggle="tab"> <?=Yii::t('app', 'main_content');?></a>
              </li>
            </ul>
            <div class="tab-content">
              <div class="tab-pane active" id="tab_general">
                <div class="form-body">
                  <?=$form->field($model, 'media_id', [
                    'labelOptions' => ['class' => 'col-md-2 control-label'],
                    'template' => '{label}<div class="col-md-10">{input}{hint}{error}</div>'
                  ])->widget(common\widgets\ImageInputWidget::className(), [
                    'template' => '<div class="fileinput-preview thumbnail" style="width: 150px; height: 150px;">{image}{input}</div>{choose_button}{cancel_button}',
                    'imageOptions' => ['width' => 150, 'height' => 150]
                  ])->label('Media');?>

                  <?=$form->field($model, 'title', [
                    'labelOptions' => ['class' => 'col-md-2 control-label'],
                    'inputOptions' => ['class' => 'slug form-control'],
                    'template' => '{label}<div class="col-md-6">{input}{hint}{error}</div>'
                  ])->textInput();?>

                  <?=$form->field($model, 'link', [
                    'labelOptions' => ['class' => 'col-md-2 control-label'],
                    'inputOptions' => ['class' => 'slug form-control'],
                    'template' => '{label}<div class="col-md-6">{input}{hint}{error}</div>'
                  ])->textInput();?>

                  <?=$form->field($model, 'position', [
                    'labelOptions' => ['class' => 'col-md-2 control-label'],
                    'inputOptions' => ['class' => 'slug form-control'],
                    'template' => '{label}<div class="col-md-6">{input}{hint}{error}</div>'
                  ])->dropdownList($model->fetchPosition(), ['prompt' => Yii::t('app', 'choose_position')]);?>

                  <?= $form->field($model, 'start_date', [
                    'labelOptions' => ['class' => 'col-md-2 control-label'],
                    'inputOptions' => ['class' => 'slug form-control'],
                    'template' => '{label}<div class="col-md-6">{input}{hint}{error}</div>'
                  ])->widget(DatePicker::className(), [
                    'clientOptions' => [
                      'autoclose' => true,
                      'format' => 'yyyy-mm-dd',
                      'minuteStep' => 1,
                      'minView' => '1'
                    ],
                  ]);?>

                  <?=$form->field($model, 'end_date', [
                    'labelOptions' => ['class' => 'col-md-2 control-label'],
                    'inputOptions' => ['class' => 'slug form-control'],
                    'template' => '{label}<div class="col-md-6">{input}{hint}{error}</div>'
                  ])->widget(DatePicker::className(), [
                      'clientOptions' => [
                        'autoclose' => true,
                        'format' => 'yyyy-mm-dd',
                        'minuteStep' => 1,
                        'minView' => '1'
                      ],
                  ]);?>

                  <?=$form->field($model, 'fee', [
                    'labelOptions' => ['class' => 'col-md-2 control-label'],
                    'inputOptions' => ['class' => 'slug form-control'],
                    'template' => '{label}<div class="col-md-6">{input}{hint}{error}</div>'
                  ])->textInput();?>

                  <?=$form->field($model, 'currency', [
                    'labelOptions' => ['class' => 'col-md-2 control-label'],
                    'inputOptions' => ['class' => 'slug form-control'],
                    'template' => '{label}<div class="col-md-6">{input}{hint}{error}</div>'
                  ])->dropdownList($model->fetchCurrency(), ['prompt' => Yii::t('app', 'choose_currency')]);?>

                  <?=$form->field($model, 'contact_phone', [
                    'labelOptions' => ['class' => 'col-md-2 control-label'],
                    'inputOptions' => ['class' => 'slug form-control'],
                    'template' => '{label}<div class="col-md-6">{input}{hint}{error}</div>'
                  ])->textInput();?>

                  <?=$form->field($model, 'contact_email', [
                    'labelOptions' => ['class' => 'col-md-2 control-label'],
                    'inputOptions' => ['class' => 'slug form-control'],
                    'template' => '{label}<div class="col-md-6">{input}{hint}{error}</div>'
                  ])->textInput();?>

                  <?=$form->field($model, 'contact_name', [
                    'labelOptions' => ['class' => 'col-md-2 control-label'],
                    'inputOptions' => ['class' => 'slug form-control'],
                    'template' => '{label}<div class="col-md-6">{input}{hint}{error}</div>'
                  ])->textInput();?>

                  <?=$form->field($model, 'status', [
                    'labelOptions' => ['class' => 'col-md-2 control-label'],
                    'inputOptions' => ['class' => 'slug form-control'],
                    'template' => '{label}<div class="col-md-6">{input}{hint}{error}</div>'
                  ])->dropdownList($model->fetchStatus(), ['prompt' => Yii::t('app', 'choose_status')]);?>

                  
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <?php ActiveForm::end()?>
  </div>
</div>
