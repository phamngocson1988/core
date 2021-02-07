<?php
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use common\widgets\ImageInputWidget;
use common\widgets\TinyMce;
use backend\components\datetimepicker\DateTimePicker;

$this->registerCssFile('vendor/assets/global/plugins/bootstrap-select/css/bootstrap-select.css', ['depends' => ['\yii\bootstrap\BootstrapAsset']]);
$this->registerJsFile('vendor/assets/global/plugins/bootstrap-select/js/bootstrap-select.min.js', ['depends' => '\backend\assets\AppAsset']);
$this->registerJsFile('vendor/assets/pages/scripts/components-bootstrap-select.min.js', ['depends' => '\backend\assets\AppAsset']);

?>
<!-- BEGIN PAGE BAR -->
<div class="page-bar">
  <ul class="page-breadcrumb">
    <li>
      <a href="/"><?=Yii::t('app', 'home')?></a>
      <i class="fa fa-circle"></i>
    </li>
    <li>
      <a href="<?=Url::to(['paygate/index'])?>">Danh sách hoá đơn nhận tiền</a>
      <i class="fa fa-circle"></i>
    </li>
    <li>
      <span>Tạo hoá đơn nhận tiền</span>
    </li>
  </ul>
</div>
<!-- END PAGE BAR -->
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title">Tạo hoá đơn nhận tiền</h1>
<!-- END PAGE TITLE-->
<div class="row">
  <div class="col-md-12">
    <?php $form = ActiveForm::begin(['options' => ['class' => 'form-horizontal form-row-seperated', 'id' => 'create-payment-reality-form']]);?>
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

                  <?=$form->field($model, 'paygate', [
                    'labelOptions' => ['class' => 'col-md-2 control-label'],
                    'inputOptions' => ['class' => 'form-control input-large'],
                    'template' => '{label}<div class="col-md-6">{input}{hint}{error}</div>'
                  ])->widget(kartik\select2\Select2::classname(), [
                    'data' => $model->fetchPaygate(),
                    'pluginOptions' => ['tags' => true]
                    ])->label('Cổng thanh toán')?>

                  <?=$form->field($model, 'payer', [
                    'labelOptions' => ['class' => 'col-md-2 control-label'],
                    'template' => '{label}<div class="col-md-6">{input}{hint}{error}</div>'
                  ])->textInput()->label('TK người gửi');?>
                  <?= $form->field($model, 'payment_time', [
                    'labelOptions' => ['class' => 'col-md-2 control-label'],
                    'template' => '{label}<div class="col-md-6">{input}{hint}{error}</div>'
                  ])->widget(DateTimePicker::className(), [
                    'clientOptions' => [
                        'autoclose' => true,
                        'format' => 'yyyy-mm-dd hh:ii',
                        'minuteStep' => 1,
                        'endDate' => date('Y-m-d H:i'),
                        'minView' => '0'
                    ],
                  ])->label('TG nhận hoá đơn');?>
                    
                  <?=$form->field($model, 'payment_id', [
                    'labelOptions' => ['class' => 'col-md-2 control-label'],
                    'inputOptions' => ['class' => 'slug form-control'],
                    'template' => '{label}<div class="col-md-6">{input}{hint}{error}</div>'
                  ])->textInput()->label('Mã tham chiếu người nhận');?>
                  <?=$form->field($model, 'payment_note', [
                    'labelOptions' => ['class' => 'col-md-2 control-label'],
                    'inputOptions' => ['class' => 'slug form-control'],
                    'template' => '{label}<div class="col-md-6">{input}{hint}{error}</div>'
                  ])->textInput()->label('Ghi chú từ khách hàng');?>
                  <?=$form->field($model, 'total_amount', [
                    'labelOptions' => ['class' => 'col-md-2 control-label'],
                    'inputOptions' => ['class' => 'slug form-control'],
                    'template' => '{label}<div class="col-md-6">{input}{hint}{error}</div>'
                  ])->textInput()->label('Thực nhận (tiền tệ)');?>
                  <?=$form->field($model, 'currency', [
                    'labelOptions' => ['class' => 'col-md-2 control-label'],
                    'inputOptions' => ['class' => 'slug form-control'],
                    'template' => '{label}<div class="col-md-6">{input}{hint}{error}</div>'
                  ])->dropdownList($model->fetchCurrency())->label('Tiền tệ');?>
                  <?=$form->field($model, 'note', [
                    'labelOptions' => ['class' => 'col-md-2 control-label'],
                    'inputOptions' => ['class' => 'slug form-control'],
                    'template' => '{label}<div class="col-md-6">{input}{hint}{error}</div>'
                  ])->textInput()->label('Ghi chú nhận tiền');?>
                  <?=$form->field($model, 'evidence', [
                    'options' => ['tag' => false],       
                  ])->hiddenInput()->label(false);?>

                  <div class="form-group field-createpostform-image_id">
                    <label class="col-md-2 control-label" for="createpostform-image_id">Hình ảnh</label>
                    <div class="col-md-10">
                      <div class="fileinput-preview thumbnail" style="width: 150px; height: 150px;">
                        <img id="evidence-image" src="<?=$model->evidence ? $model->evidence : '/images/noimage.png';?>" width="150" height="150" alt="" alter-src="<?=$model->evidence ? $model->evidence : '/images/noimage.png';?>">
                        <input type="file" id="uploadEvidence" name="uploadEvidence" style="display: none" accept="image/*"/>
                        <?=$form->field($model, 'evidence', ['options' => ['tag' => false]])->hiddenInput(['id' => 'evidence-input'])->label(false);?>
                      </div>
                      <span id="uploadElement" class="btn default" onclick="javascript:;">Chọn hình ảnh</span>
                      <span id="cancelButtonId" class="btn red">Loại bỏ ảnh</span>
                      <div class="help-block"></div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <?php ActiveForm::end()?>
  </div>
</div>

<!-- Image upload --> 
<?php
$imageJs = <<< JS
var upload = new AjaxUploadFile({
  trigger_element: '#uploadElement', 
  file_element: '#uploadEvidence',
  file_options: {resize: '500xauto'},
  max: 1
});
upload.callback = function(result) {
  result.forEach(function(element) {
    console.log(element);
    $('#evidence-input').val(element.src);
    $('#evidence-image').attr('src', element.src);
  });
};

$('#cancelButtonId').on('click', function(e){
  e.preventDefault();
  var alter = $('#evidence-image').attr('alter-src');
  $('#evidence-image').attr('src', alter);
  $('#evidence-input').val('');
  return false;
});

// remove enter event from form
$('#create-payment-reality-form').on('keyup keypress', function(e) {
  var keyCode = e.keyCode || e.which;
  if (keyCode === 13) { 
    e.preventDefault();
    return false;
  }
});
JS;
$this->registerJs($imageJs);
?>
<!-- end image upload -->