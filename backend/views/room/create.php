<?php 
use yii\widgets\ActiveForm;
?>
<div class="page-bar">
  <ul class="page-breadcrumb">
    <li>
      <a href="/">{Yii::t('app', 'home')}</a>
      <i class="fa fa-circle"></i>
    </li>
    <li>
      <a href="{url route='post/index'}">{Yii::t('app', 'manage_posts')}</a>
      <i class="fa fa-circle"></i>
    </li>
    <li>
      <span>{Yii::t('app', 'create_post')}</span>
    </li>
  </ul>
</div>
<!-- END PAGE BAR -->
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title"><?= Yii::t('app', 'create_room');?></h1>
<?php $form = ActiveForm::begin(['options' => ['role' => 'form', 'class' => 'form-horizontal']]); ?>
<div class="row">
  <div class="col-md-8">
    <!-- BEGIN SAMPLE FORM PORTLET-->
    <div class="portlet light bordered">
    	<h3 class="form-section">Validation States With Icons</h3>
      <div class="portlet-body">
        <?= $form->field($model, 'title', [
          'labelOptions' => ['class' => 'col-md-2 control-label'],
          'template' => '{label}<div class="col-md-9">{input}{hint}{error}</div>'
        ])->textInput() ?>
        <?= $form->field($model, 'description', [
          'labelOptions' => ['class' => 'col-md-2 control-label'],
          'template' => '{label}<div class="col-md-9">{input}{hint}{error}</div>'
        ])->textArea() ?>
        <?= $form->field($model, 'status', [
          'labelOptions' => ['class' => 'col-md-2 control-label'],
          'template' => '{label}<div class="col-md-10">{input}{hint}{error}</div>'
        ])->radioList(['Y' => 'Yes<span></span>', 'N' => 'No<span></span>'], [
          'class' => 'md-checkbox-list', 
          'encode' => false , 
          'itemOptions' => ['labelOptions' => ['class'=>'mt-checkbox', 'style' => 'display: block']]
        ])->label('Status') ?>
      </div>
    </div>
  </div>
  <div class="col-md-4">
    <!-- BEGIN SAMPLE FORM PORTLET-->
    <div class="portlet light bordered">
    	<h3 class="form-section">Validation States With Icons</h3>
    	<?= $form->field($model, 'price', [
        'labelOptions' => ['class' => 'col-md-2 control-label'],
        'template' => '{label}<div class="col-md-9">{input}{hint}{error}</div>'
      ])->textInput() ?>
    </div>
  </div>
  <input type="submit">
  <!-- END SAMPLE FORM PORTLET-->
</div>
<?php ActiveForm::end() ?>