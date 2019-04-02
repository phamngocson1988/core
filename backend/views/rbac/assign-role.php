<?php 
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\web\JsExpression;
?>
<div class="page-bar">
  <ul class="page-breadcrumb">
    <li>
      <a href="/"><?=Yii::t('app', 'home')?></a>
      <i class="fa fa-circle"></i>
    </li>
    <li>
      <a href="<?=Url::to(['rbac/role'])?>"><?=Yii::t('app', 'manage_roles');?></a>
      <i class="fa fa-circle"></i>
    </li>
    <li>
      <span><?=Yii::t('app', 'assign_role');?></span>
    </li>
  </ul>
</div>
<!-- END PAGE BAR -->
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title"><?=Yii::t('app', 'assign_role');?></h1>
<!-- END PAGE TITLE-->
<div class="row">
  <div class="col-md-12">
    <?php $form = ActiveForm::begin(['options' => ['class' => 'form-horizontal form-row-seperated']]);?>
    <div class="portlet">
      <div class="portlet-title">
        <div class="caption"><?=Yii::t('app', 'assign_role');?></div>
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
                <?php $user = $model->getUser();?>
                <?=$form->field($model, 'user_id', [
                  'labelOptions' => ['class' => 'col-md-2 control-label'],
                  'template' => '{label}<div class="col-md-6">{input}{hint}{error}</div>'
                ])->widget(kartik\select2\Select2::classname(), [
                  'initValueText' => ($user) ? sprintf("%s - %s", $user->username, $user->email) : '',
                  'options' => ['class' => 'form-control'],
                  'pluginOptions' => [
                    'placeholder' => 'Tìm người dùng',
                    'allowClear' => true,
                    'minimumInputLength' => 3,
                    'ajax' => [
                        'url' => Url::to(['user/suggestion']),
                        'dataType' => 'json',
                        'processResults' => new JsExpression('function (data) {return {results: data.data.items};}')
                    ]
                  ]
                ])->label('Người dùng')?>

                <?=$form->field($model, 'role', [
                  'labelOptions' => ['class' => 'col-md-2 control-label'],
                  'template' => '{label}<div class="col-md-6">{input}{hint}{error}</div>'
                ])->dropDownList($model->getRoles(), ['prompt' => Yii::t('app', 'choose')]);?>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <?php ActiveForm::end()?>
  </div>
</div>