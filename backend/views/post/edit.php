<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\widgets\TinyMce;
use unclead\multipleinput\MultipleInput;
use backend\models\Game;
use common\widgets\ImageInputWidget;
use common\widgets\RadioListInput;
use common\widgets\CheckboxInput;
use backend\components\datetimepicker\DateTimePicker;
use yii\helpers\Url;
$this->registerCssFile('@web/vendor/assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.css', ['depends' => [\backend\assets\AppAsset::className()]]);
$this->registerCssFile('@web/vendor/assets/pages/css/profile.min.css', ['depends' => [\backend\assets\AppAsset::className()]]);
$this->registerJsFile('@web/vendor/assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile('@web/vendor/assets/global/plugins/jquery.sparkline.min.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile('@web/vendor/assets/pages/scripts/profile.min.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile('@web/js/jquery.number.min.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
?>
<!-- BEGIN PAGE BAR -->
<div class="page-bar">
  <ul class="page-breadcrumb">
    <li>
      <a href="/"><?=Yii::t('app', 'home');?></a>
      <i class="fa fa-circle"></i>
    </li>
    <li>
      <a href="<?=Url::to(['post/index']);?>">Quản lý bài viết</a>
      <i class="fa fa-circle"></i>
    </li>
    <li>
      <span>Chỉnh sửa bài viết</span>
    </li>
  </ul>
</div>
<!-- END PAGE BAR -->
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title"> Chỉnh sửa bài viết </h1>
<!-- END PAGE TITLE-->
<?php $form = ActiveForm::begin(['options' => ['class' => 'form-horizontal form-row-seperated form']]);?>
<div class="row">
  <div class="col-md-12">
    <!-- BEGIN PROFILE SIDEBAR -->
    <div class="profile-sidebar">
      <!-- PORTLET MAIN -->
      <div class="portlet light">
        <!-- SIDEBAR USERPIC -->
        <?=$form->field($model, 'image_id', [
          'options' => ['tag' => false, 'class' => 'profile-userpic'],
          'template' => '{input}{hint}{error}'
        ])->widget(ImageInputWidget::className(), [
          'template' => '<div class="">{image}{input}</div><div class="profile-userbuttons list-separated profile-stat">{choose_button}{cancel_button}</div>',
          'imageOptions' => ['class' => 'img-responsive'],
          'chooseButtonOptions' => ['tag' => 'span', 'options' => ['class' => 'btn btn-circle green btn-sm']],
          'cancelButtonOptions' => ['tag' => 'button', 'options' => ['class' => 'btn btn-circle red btn-sm']]
        ])->label(false);?>

        <?=$form->field($model, 'hot', [
          'options' => ['class' => 'list-separated profile-stat']
        ])->widget(CheckboxInput::className())->label(false);?>


        <?=$form->field($model, 'categories', [
          'options' => ['class' => 'list-separated profile-stat']
        ])->checkboxList($model->getCategories('%s<span></span>'), [
          'class' => 'md-checkbox-list', 
          'encode' => false , 
          'itemOptions' => ['labelOptions' => ['class'=>'mt-checkbox', 'style' => 'display: block']]
        ])->label('Categories');?>

        <?=$form->field($model, 'status')->dropdownList($model->getStatusList(), ['id' => 'status'])->label('Status');?>

        <?=$form->field($model, 'published_at', [    
          'inputOptions' => ['id' => 'published_at', 'class' => 'form-control'],
        ])->widget(DateTimePicker::className(), [
          'clientOptions' => [
            'autoclose' => true,
            'format' => 'yyyy-mm-dd hh:00',
            'minuteStep' => 1,
            'startDate' => date('Y-m-d H:i'),
            'minView' => '1'
          ]
        ])->label('Thời gian');?>
        <hr/>
        <div class="profile-stat">
            <?=Html::submitButton(Yii::t('app', 'save'), ['class' => 'btn green']);?>
            <?=Html::resetButton(Yii::t('app', 'cancel'), ['class' => 'btn default']);?>
        </div>
        <!-- END MENU -->
      </div>
      <!-- END PORTLET MAIN -->
    </div>
    <!-- END BEGIN PROFILE SIDEBAR -->
    <!-- BEGIN PROFILE CONTENT -->
    <div class="profile-content">
      <div class="row">
        <div class="col-md-12">
          <div class="portlet light ">
            <div class="portlet-title tabbable-line">
              <ul class="nav nav-tabs">
                <li class="active">
                  <a href="#tab_1_1" data-toggle="tab"><?=Yii::t('app', 'main_content');?></a>
                </li>
              </ul>
            </div>
            <div class="portlet-body">
              <div class="tab-content">
                <div class="tab-pane active" id="tab_1_1">
                  <?=$form->field($model, 'title')->textInput();?>
                  <?=$form->field($model, 'excerpt')->textInput();?>
                  <?=$form->field($model, 'meta_title')->textInput();?>
                  <?=$form->field($model, 'meta_keyword')->textInput();?>
                  <?=$form->field($model, 'meta_description')->textInput();?>
                  <?=$form->field($model, 'author')->textInput();?>
                  <?=$form->field($model, 'content')->widget(TinyMce::className(), ['options' => ['rows' => 30]]);?>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- END PROFILE CONTENT -->
  </div>
</div>
<?php ActiveForm::end()?>

<?php
$script = <<< JS
$('#status').on('change', function() {
  changeStatus($(this).val());
});
changeStatus($('#status').val());

function changeStatus(val) {
  if (val == 6) {
    $('#published_at').closest('.form-group').show();
  } else {
    $('#published_at').closest('.form-group').hide();
  }
}
JS;
$this->registerJs($script);
?>