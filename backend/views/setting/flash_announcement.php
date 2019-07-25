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
      <span>Thông báo nhanh</span>
    </li>
  </ul>
</div>
<!-- END PAGE BAR -->
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title">Thông báo nhanh</h1>
<!-- END PAGE TITLE-->
<div class="row">
  <div class="col-md-12">
    <?php $form = ActiveForm::begin(['options' => ['class' => 'form-horizontal form-row-seperated']]);?>
    <div class="portlet">
      <div class="portlet-title">
        <div class="caption">Thông báo nhanh</div>
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
          <?php echo $this->render('@backend/views/setting/_widget_tabs.php', ['tab' => 'flash_announcement']);?>
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