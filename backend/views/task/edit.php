<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use dosamigos\datepicker\DatePicker;
use dosamigos\datepicker\DateRangePicker;
use yii\web\JsExpression;
?>
<!-- BEGIN PAGE BAR -->
<div class="page-bar">
  <ul class="page-breadcrumb">
    <li>
      <a href="/"><?=Yii::t('app', 'home')?></a>
      <i class="fa fa-circle"></i>
    </li>
    <li>
      <a href="<?=Url::to(['task/index'])?>"><?=Yii::t('app', 'manage_tasks')?></a>
      <i class="fa fa-circle"></i>
    </li>
    <li>
      <span><?=Yii::t('app', 'edit_task')?></span>
    </li>
  </ul>
</div>
<!-- END PAGE BAR -->
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title"><?=Yii::t('app', 'edit_task')?></h1>
<!-- END PAGE TITLE-->
<div class="row">
  <div class="col-md-12">
    <?php $form = ActiveForm::begin(['options' => ['class' => 'form-horizontal form-row-seperated']]);?>
      <?=$form->field($model, 'id', ['template' => '{input}'])->hiddenInput()?>
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
                    'inputOptions' => ['id' => 'name', 'class' => 'form-control'],
                    'template' => '{label}<div class="col-md-10">{input}{hint}{error}</div>'
                  ])->textInput()?>
                  <?=$form->field($model, 'description', [
                    'labelOptions' => ['class' => 'col-md-2 control-label'],
                    'inputOptions' => ['class' => 'slug form-control'],
                    'template' => '{label}<div class="col-md-10">{input}{hint}{error}</div>'
                  ])->textArea()?>
                  <?=$form->field($model, 'start_date', [
                    'labelOptions' => ['class' => 'col-md-2 control-label'],
                    'template' => '{label}<div class="col-md-4">{input}{hint}{error}</div>'
                  ])->widget(DateRangePicker::className(), [
                    'attributeTo' => 'due_date', 
                    'labelTo' => Yii::t('app', 'due_date'),
                    'form' => $form,
                    'clientOptions' => [
                        'autoclose' => true,
                        'format' => 'yyyy-mm-dd',
                        'keepEmptyValues' => true,
                        'todayHighlight' => true
                    ]
                  ])?>
                  <?php $assignee = $model->getAssignee()?>
                  <?=$form->field($model, 'assignee', [
                    'labelOptions' => ['class' => 'col-md-2 control-label'],
                    'template' => '{label}<div class="col-md-5">{input}{hint}{error}</div>'
                  ])->widget(kartik\select2\Select2::classname(), [
                    'initValueText' => ($assignee) ? sprintf("%s - %s", $assignee->username, $assignee->email) : '',
                    'options' => ['class' => 'form-control'],
                    'pluginOptions' => [
                      'placeholder' => Yii::t('app', 'select_assignee'),
                      'allowClear' => true,
                      'minimumInputLength' => 3,
                      'ajax' => [
                          'url' => $links['user_suggestion'],
                          'dataType' => 'json',
                          'processResults' => new JsExpression('function (data) {return {results: data.data.items};}')
                      ]
                    ]
                  ])->label(Yii::t('app', 'assignee'))?>

                  <?=$form->field($model, "percent", [
                    'labelOptions' => ['class' => 'col-md-2 control-label'],
                    'inputOptions' => ['id' => 'name', 'class' => 'form-control'],
                    'template' => '{label}<div class="col-md-5">{input}{hint}{error}</div>'
                  ])->widget(\yii2mod\slider\IonSlider::className(), [
                    'pluginOptions' => ['min' => 0, 'max' => 100, 'step' => 10, 'hide_min_max' => true]
                  ]);?>
                  <?=$form->field($model, 'status', [
                    'labelOptions' => ['class' => 'col-md-2 control-label'],
                    'inputOptions' => ['class' => 'form-control'],
                    'template' => '{label}<div class="col-md-5">{input}{hint}{error}</div>'
                  ])->dropDownList($model->getStatusList())->label(Yii::t('app', 'status'))?>

                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <?php ActiveForm::end()?>
  </div>
</div>