<?php 
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\web\JsExpression;
use yii\helpers\ArrayHelper;
$this->registerCssFile('@web/vendor/assets/global/plugins/bootstrap-select/css/bootstrap-select.css', ['depends' => ['\yii\bootstrap\BootstrapAsset']]);
$this->registerCssFile('@web/vendor/assets/global/plugins/jquery-multi-select/css/multi-select.css', ['depends' => ['\yii\bootstrap\BootstrapAsset']]);
$this->registerCssFile('@web/vendor/assets/global/plugins/select2/css/select2.min.css', ['depends' => ['\yii\bootstrap\BootstrapAsset']]);
$this->registerCssFile('@web/vendor/assets/global/plugins/select2/css/select2-bootstrap.min.css', ['depends' => ['\yii\bootstrap\BootstrapAsset']]);
$this->registerJsFile('@web/vendor/assets/global/plugins/bootstrap-select/js/bootstrap-select.min.js', ['depends' => '\backend\assets\AppAsset']);
$this->registerJsFile('@web/vendor/assets/global/plugins/jquery-multi-select/js/jquery.multi-select.js', ['depends' => '\backend\assets\AppAsset']);
$this->registerJsFile('@web/vendor/assets/global/plugins/select2/js/select2.full.min.js', ['depends' => '\backend\assets\AppAsset']);
$this->registerJsFile('@web/vendor/assets/pages/scripts/components-multi-select.min.js', ['depends' => '\backend\assets\AppAsset']);
?>
<style>
.custom-header {
  background-color: black;
  color: white;
  padding: 5px;
  text-align: center;
}
.ms-container {
  width: 500px
}
</style>
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
                <?=$form->field($model, 'message', [
                  'labelOptions' => ['class' => 'col-md-2 control-label'],
                  'template' => '{label}<div class="col-md-6">{input}{hint}{error}</div>'
                ])->textArea();?>
                <div class="form-group">
                  <label class="col-md-2 control-label">Nhóm liên hệ</label>
                  <div class="col-md-6">
                    <select id="contact-group" class="form-control">
                      <option value="0">Tất cả</option>
                      <?php foreach ($groups as $group) :?>
                      <option value="<?=$group->id;?>" data-contacts="<?=implode(",", (ArrayHelper::getColumn($group->contactGroups, 'contact_id')));?>"><?=$group->name;?></option>
                      <?php endforeach;?>
                    </select>
                  </div>
                </div>

                <div class="form-group">
                  <label class="col-md-2 control-label">Số điện thoại</label>
                  <div class="col-md-6">
                    <select id="phones" class="form-control multi-select" name="phones[]" multiple="multiple">
                      <?php foreach ($contacts as $contact) :?>
                      <option value="<?=$contact->id;?>" data-contacts="<?=implode(",", (ArrayHelper::getColumn($contact->contactGroups, 'group_id')));?>"><?=$contact->phone;?></option>
                      <?php endforeach;?>
                    </select>
                  </div>
                </div>

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

// multiple-select
$('#phones').multiSelect({
  selectableHeader: "<div class='custom-header'>Danh bạ</div>",
  selectionHeader: "<div class='custom-header'>Số đã chọn</div>",
});
$('#contact-group').on('change', function(){
  var notSelected = $('#phones').find('option:not(:selected)');
  if ($(this).val() == '0') {
    notSelected.show();
  } else {
    notSelected.hide();
    var delimeter = ",";
    var dataContacts = String($(this).find(':selected').data('contacts'));
    var contacts = dataContacts.split(",");
    for (var i = 0; i < notSelected.length; i++) {
      var element = notSelected[i];
      var value = $(element).prop('value');
      if (contacts.indexOf(value) == -1) {
        $("#phones").find(element).hide();
      } else {
        $("#phones").find(element).show();
      }
    }
  }
  $('#phones').multiSelect('refresh');
});
JS;
$this->registerJs($script);
?>