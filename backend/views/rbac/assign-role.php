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
      <span>Thêm vai trò cho nhân viên</span>
    </li>
  </ul>
</div>
<!-- END PAGE BAR -->
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title">Thêm vai trò cho nhân viên</h1>
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
                <?php $user = $model->getUser();?>
                <?=$form->field($model, 'user_id', [
                  'labelOptions' => ['class' => 'col-md-2 control-label'],
                  'template' => '{label}<div class="col-md-6">{input}{hint}{error}</div>'
                ])->dropDownList($model->getUsers(), ['prompt' => 'Chọn nhân viên'])->label('Nhân viên');?>

                <?=$form->field($model, 'role', [
                  'labelOptions' => ['class' => 'col-md-2 control-label'],
                  'template' => '{label}<div class="col-md-6">{input}{hint}{error}</div>'
                ])->dropDownList($model->getRoles(), ['prompt' => 'Chọn vai trò'])->label('Vai trò');?>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <?php ActiveForm::end()?>
  </div>
</div>