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
      <span>Terms and conditions</span>
    </li>
  </ul>
</div>
<!-- END PAGE BAR -->
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title">Terms and conditions</h1>
<!-- END PAGE TITLE-->
<div class="row">
  <div class="col-md-12">
    <?php $form = ActiveForm::begin(['options' => ['class' => 'form-horizontal form-row-seperated']]);?>
    <div class="portlet">
      <div class="portlet-title">
        <div class="caption">Terms and conditions</div>
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
          <?php echo $this->render('@backend/views/setting/_widget_tabs.php', ['tab' => 'terms']);?>
          <div class="tab-content">
            <div class="tab-pane active" id="tab_general">
              <div class="form-body">
                <?=$form->field($model, 'member', [
                  'labelOptions' => ['class' => 'col-md-2 control-label'],
                  'inputOptions' => ['class' => 'form-control'],
                  'template' => '{label}<div class="col-md-10">{input}{hint}{error}</div>'
                ])->widget(\common\widgets\TinyMce::className(), [
                  'options' => ['rows' => 20]
                ]);?>
              </div>
              <div class="form-body">
                <?=$form->field($model, 'risk', [
                  'labelOptions' => ['class' => 'col-md-2 control-label'],
                  'inputOptions' => ['class' => 'form-control'],
                  'template' => '{label}<div class="col-md-10">{input}{hint}{error}</div>'
                ])->widget(\common\widgets\TinyMce::className(), [
                  'options' => ['rows' => 20]
                ]);?>
              </div>
              <div class="form-body">
                <?=$form->field($model, 'affiliate', [
                  'labelOptions' => ['class' => 'col-md-2 control-label'],
                  'inputOptions' => ['class' => 'form-control'],
                  'template' => '{label}<div class="col-md-10">{input}{hint}{error}</div>'
                ])->widget(\common\widgets\TinyMce::className(), [
                  'options' => ['rows' => 20]
                ]);?>
              </div>
              <div class="form-body">
                <?=$form->field($model, 'no_refund', [
                  'labelOptions' => ['class' => 'col-md-2 control-label'],
                  'inputOptions' => ['class' => 'form-control'],
                  'template' => '{label}<div class="col-md-10">{input}{hint}{error}</div>'
                ])->widget(\common\widgets\TinyMce::className(), [
                  'options' => ['rows' => 20]
                ]);?>
              </div>
              <div class="form-body">
                <?=$form->field($model, 'promotion', [
                  'labelOptions' => ['class' => 'col-md-2 control-label'],
                  'inputOptions' => ['class' => 'form-control'],
                  'template' => '{label}<div class="col-md-10">{input}{hint}{error}</div>'
                ])->widget(\common\widgets\TinyMce::className(), [
                  'options' => ['rows' => 20]
                ]);?>
              </div>
              <div class="form-body">
                <?=$form->field($model, 'disclaimer_policies', [
                  'labelOptions' => ['class' => 'col-md-2 control-label'],
                  'inputOptions' => ['class' => 'form-control'],
                  'template' => '{label}<div class="col-md-10">{input}{hint}{error}</div>'
                ])->widget(\common\widgets\TinyMce::className(), [
                  'options' => ['rows' => 20]
                ]);?>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <?php ActiveForm::end();?>
  </div>
</div>