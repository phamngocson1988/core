<?php
use yii\widgets\ActiveForm;
?>
<div class="page-bar">
  <ul class="page-breadcrumb">
    <li>
      <a href="/"><?=Yii::t('app', 'home');?></a>
      <i class="fa fa-circle"></i>
    </li>
    <li>
      <a href="javascript:;"><?=Yii::t('app', 'settings');?></a>
      <i class="fa fa-circle"></i>
    </li>
    <li>
      <span>Chương trình bán hàng liên kết</span>
    </li>
  </ul>
</div>
<!-- END PAGE BAR -->
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title">Chương trình bán hàng liên kết</h1>
<!-- END PAGE TITLE-->
<div class="row">
  <div class="col-md-12">
    <?php $form = ActiveForm::begin(['options' => ['class' => 'form-horizontal form-row-seperated']]);?>
    <div class="portlet">
      <div class="portlet-title">
        <div class="caption">Chương trình bán hàng liên kết</div>
        <div class="actions btn-set">
          <button type="reset" class="btn default">
          <i class="fa fa-angle-left"></i> <?=Yii::t('app', 'reset');?>
          <button type="submit" class="btn btn-success">
          <i class="fa fa-check"></i> <?=Yii::t('app', 'save');?>
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
                <?=$form->field($model, 'content', [
                  'labelOptions' => ['class' => 'col-md-2 control-label'],
                  'inputOptions' => ['id' => 'content', 'class' => 'form-control'],
                  'template' => '{label}<div class="col-md-10">{input}{hint}{error}</div>'
                ])->widget(\common\widgets\TinyMce::className(), [
                  'options' => ['rows' => 20]
                ]);?>

                <?=$form->field($model, 'value', [
                  'labelOptions' => ['class' => 'col-md-2 control-label'],
                  'inputOptions' => ['class' => 'form-control'],
                  'template' => '{label}<div class="col-md-10">{input}{hint}{error}</div>'
                ])->textInput()->label('Giá trị nhận được cho một gói (cho người dùng thường');?>

                <?=$form->field($model, 'reseller_value', [
                  'labelOptions' => ['class' => 'col-md-2 control-label'],
                  'inputOptions' => ['class' => 'form-control'],
                  'template' => '{label}<div class="col-md-10">{input}{hint}{error}</div>'
                ])->textInput()->label('Giá trị nhận được cho một gói (cho reseller - chỉ apply với loại FIX)');?>

                <?=$form->field($model, 'type', [
                  'labelOptions' => ['class' => 'col-md-2 control-label'],
                  'inputOptions' => ['class' => 'form-control'],
                  'template' => '{label}<div class="col-md-10">{input}{hint}{error}</div>'
                ])->dropDownList(['fix' => 'Giá trị cố định', 'percent' => 'Tính theo phần trăm'])->label('Cách tính');?>

                <?=$form->field($model, 'duration', [
                  'labelOptions' => ['class' => 'col-md-2 control-label'],
                  'inputOptions' => ['class' => 'form-control'],
                  'template' => '{label}<div class="col-md-10">{input}{hint}{error}</div>'
                ])->textInput()->label('Thời gian chờ (theo ngày)');?>

                <?=$form->field($model, 'min_member', [
                  'labelOptions' => ['class' => 'col-md-2 control-label'],
                  'inputOptions' => ['class' => 'form-control'],
                  'template' => '{label}<div class="col-md-10">{input}{hint}{error}</div>'
                ])->textInput()->label('Số thành viên tối thiểu');?>

                <?=$form->field($model, 'status', [
                  'labelOptions' => ['class' => 'col-md-2 control-label'],
                  'template' => '{label}<div class="col-md-10">{input}{hint}{error}</div>'
                ])->checkbox(['1' => 'Kích hoạt<span></span>'], [
                  'class' => 'md-checkbox', 
                  'encode' => false , 
                  'itemOptions' => ['labelOptions' => ['class'=>'mt-checkbox', 'style' => 'display: block']]
                ])->label('Trạng thái');?>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <?php ActiveForm::end();?>
  </div>
</div>