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
      <a href="javascript:void(0)">Gọi điện thoại</a>
      <i class="fa fa-circle"></i>
    </li>
  </ul>
</div>
<!-- END PAGE BAR -->
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title">Gọi điện thoại</h1>
<!-- END PAGE TITLE-->
<div class="row">
  <div class="col-md-12">
    <?php $form = ActiveForm::begin([
    	'action' => Url::to(['contact/start-call']),
    	'options' => ['class' => 'form-horizontal form-row-seperated', 'id' => 'request-form']
    ]);?>
    <div class="portlet">
      <div class="portlet-title">
        <div class="caption">Gọi điện thoại</div>
        <div class="actions btn-set">
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
                <?=$form->field($model, 'phone', [
                  'labelOptions' => ['class' => 'col-md-2 control-label'],
                  'template' => '{label}<div class="col-md-6">{input}{hint}{error}</div>'
                ])->widget(kartik\select2\Select2::classname(), [
                  'initValueText' => '',
                  'options' => ['class' => 'form-control'],
                  'pluginOptions' => [
                    'placeholder' => 'Nhập số điện thoại',
                    'allowClear' => true,
                    'minimumInputLength' => 3,
                    'ajax' => [
                        'url' => Url::to(['contact/suggestion']),
                        'dataType' => 'json',
                        'processResults' => new JsExpression('function (data) {return {results: data.data.items};}')
                    ]
                  ]
                ])->label('Số điện thoại')?>

                <?=$form->field($model, 'dialer_id', [
                  'labelOptions' => ['class' => 'col-md-2 control-label'],
                  'template' => '{label}<div class="col-md-6">{input}{hint}{error}</div>'
                ])->dropDownList($dialers);?>
              </div>
              <div class="form-actions">
                <div class="row">
                  <div class="col-md-offset-3 col-md-9">
                    <button type="submit" class="btn green" id="call">Gọi điện</button>
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
<div class="modal fade bs-modal-lg" id="calling" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
        <h4 class="modal-title">Gọi điện thoại</h4>
      </div>
      <div class="modal-body">
        <div class="row margin-bottom-10">
          <?php $form = ActiveForm::begin([
			    	'action' => Url::to(['contact/end-call']),
			    	'options' => ['id' => 'calling-form']
			    ]);?>
			    	<input type="hidden" name="id" id="record_id" value="">
            <div class="form-group col-md-12">
              <label>Thời gian: </label> <span id='counting'>0</span> (s)
            </div>
            <div class="form-group col-md-12">
	        		<button type="submit" class="btn red">Kết thúc</button>
	        	</div>
			    <?php ActiveForm::end()?>
        </div>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>

<?php
$script = <<< JS
var newForm = new AjaxFormSubmit({element: '#request-form'});
var seconds = 0;
var timer;
newForm.success = function (data, form) {
	$('#record_id').val(data.id);
	$('#calling').modal({backdrop: 'static'});
	timer = setInterval(incrementSeconds, 1000);
};
newForm.error = function(errors) {
	alert(errors)
}

var callingForm = new AjaxFormSubmit({element: '#calling-form'});
callingForm.success = function (data, form) {
	$('#calling').modal('hide');
	seconds = 0;
  $('#counting').html(seconds);
	clearInterval(timer);
};

function incrementSeconds() {
  seconds += 1;
  $('#counting').html(seconds);
}
JS;
$this->registerJs($script);
?>