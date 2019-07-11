<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use dosamigos\datepicker\DatePicker;
use dosamigos\datepicker\DateRangePicker;
use yii\web\JsExpression;
use common\models\Promotion;
use common\widgets\TinyMce;
?>
<!-- BEGIN PAGE BAR -->
<div class="page-bar">
  <ul class="page-breadcrumb">
    <li>
      <a href="/"><?=Yii::t('app', 'home')?></a>
      <i class="fa fa-circle"></i>
    </li>
    <li>
      <a href="<?=Url::to(['realestate/index'])?>">Quản lý nhà cho thuê</a>
      <i class="fa fa-circle"></i>
    </li>
    <li>
      <span>Quản lý dịch vụ</span>
    </li>
  </ul>
</div>
<!-- END PAGE BAR -->
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title">Quản lý dịch vụ</h1>
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
                  <?=$form->field($realestate, 'title', [
                    'labelOptions' => ['class' => 'col-md-2 control-label'],
                    'inputOptions' => ['class' => 'form-control', 'disabled' => true, 'readonly' => true, 'name' => ''],
                    'template' => '{label}<div class="col-md-6">{input}{hint}{error}</div>'
                  ])->textInput()->label('Tên bất động sản')?>

                  <hr/>
                  <table class="table table-striped table-bordered table-hover table-checkable">
                    <thead>
                      <tr>
                        <th style="width: 10%;"> ID </th>
                        <th style="width: 60%;"> Tên dịch vụ </th>
                        <th style="width: 30%;"> Tác vụ </th>
                      </tr>
                    </thead>
                    <tbody>
                        <?php if (!$realestate->realestateServices) :?>
                        <tr><td colspan="4"><?=Yii::t('app', 'no_data_found');?></td></tr>
                        <?php endif;?>
                        <?php foreach ($realestate->realestateServices as $no => $realestateService) :?>
                        <?php $service = $realestateService->service;?>
                        <tr>
                          <td style="vertical-align: middle;"><?=$service->id;?></td>
                          <td style="vertical-align: middle;"><?=$service->title;?></td>
                          <td style="vertical-align: middle;"><?=number_format($realestateService->price);?></td>
                          <td style="vertical-align: middle;">
                            <a href='#edit<?=$model->id;?>' class="btn btn-xs grey-salsa tooltips" data-pjax="0" data-container="body" data-original-title="Chỉnh sửa"><i class="fa fa-pencil"></i></a>
                            <a href='<?=Url::to(['realestate/delete-service', 'id' => $model->id]);?>' class="btn btn-xs grey-salsa tooltips delete" data-pjax="0" data-container="body" data-original-title="Xoá"><i class="fa fa-trash"></i></a>
                            <div class="modal fade" id="edit<?=$model->id;?>" tabindex="-1" role="basic" aria-hidden="true">
                              <div class="modal-dialog">
                                <div class="modal-content">
                                  <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                    <h4 class="modal-title">Chỉnh sửa nội dung dịch vụ</h4>
                                  </div>
                                  <?php $editForm = ActiveForm::begin(['options' => ['class' => 'form-horizontal form-row-seperated edit-form', 'id' => 'edit-form-' . $model->id], 'action' => Url::to(['realestate/edit-service', 'id' => $model->id])]);?>
                                  <div class="modal-body"> 
                                    <div class="row">
                                      <div class="col-md-12">
                                        <?=$editForm->field($model, 'price', [
                                          'options' => ['style' => 'margin: 10px']
                                        ])->textInput()->label('Giá dịch vụ');?>
                                      </div>
                                    </div>
                                  </div>
                                  <div class="modal-footer">
                                    <button type="button" class="btn dark btn-outline" data-dismiss="modal">Đóng</button>
                                    <button type="submit" class="btn green">Cập nhật</button>
                                  </div>
                                  <?php ActiveForm::end();?>
                                </div>
                                <!-- /.modal-content -->
                              </div>
                              <!-- /.modal-dialog -->
                            </div>
                          </td>
                        </tr>
                        <?php endforeach;?>
                    </tbody>
                  </table>
                  <hr/>
                  <?=$form->field($model, 'service_id', [
                    'labelOptions' => ['class' => 'col-md-2 control-label'],
                    'inputOptions' => ['class' => 'form-control', 'id' => 'benefits'],
                    'template' => '{label}<div class="col-md-6">{input}{hint}{error}</div>',
                  ])->dropDownList(ArrayHelper::map($services, 'id', 'title'), ['prompt' => 'Chọn giá trị áp dụng'])->label('Giá trị áp dụng');?>

                  <?=$form->field($model, 'price', [
                    'labelOptions' => ['class' => 'col-md-2 control-label'],
                    'inputOptions' => ['class' => 'form-control', 'type' => 'number'],
                    'template' => '{label}<div class="col-md-6">{input}{hint}{error}</div>'
                  ])->textInput()->label('Giá dịch vụ')?>

                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    <?php ActiveForm::end()?>
  </div>
</div>

<?php
$script = <<< JS
// edit
var editForm = new AjaxFormSubmit({element: '.edit-form'});
editForm.success = function (data, form) {
  location.reload();
};
editForm.error = function (errors) {
  alert(errors[0]);
  return false;
}

// delete
$('.delete').ajax_action({
  method: 'DELETE',
  confirm: true,
  confirm_text: 'Bạn có muốn xóa dịch vụ này không? Thao tác này sẽ xóa luôn những dịch vụ trong những phòng thuộc nhà cho thuê này.',
  callback: function(data) {
    location.reload();
  },
});
JS;
$this->registerJs($script);
?>