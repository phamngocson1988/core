<?php
use yii\widgets\LinkPager;
use yii\widgets\Pjax;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
?>

<style>
.hide-text {
    white-space: nowrap;
    width: 100%;
    max-width: 500px;
    text-overflow: ellipsis;
    overflow: hidden;
}
</style>
<!-- BEGIN PAGE BAR -->
<div class="page-bar">
  <ul class="page-breadcrumb">
    <li>
      <a href="/"><?=Yii::t('app', 'home')?></a>
      <i class="fa fa-circle"></i>
    </li>
    <li>
      <span>Giá dành cho nhân viên bán lẻ</span>
    </li>
  </ul>
</div>
<!-- END PAGE BAR -->
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title">Giá dành cho nhân viên bán lẻ</h1>
<!-- END PAGE TITLE-->
<div class="row">
  <div class="col-md-12">
    <!-- BEGIN EXAMPLE TABLE PORTLET-->
    <div class="portlet light bordered">
      <div class="portlet-title">
        <div class="caption font-dark">
          <i class="icon-settings font-dark"></i>
          <span class="caption-subject bold uppercase"> Giá dành cho nhân viên bán lẻ</span>
        </div>
        <div class="actions">
          <div class="btn-group btn-group-devided">
            <a class="btn green" href="#add_new_modal" data-toggle="modal"><?=Yii::t('app', 'add_new')?></a>
          </div>
        </div>
      </div>
      <div class="portlet-body">
        <?php Pjax::begin(); ?>
        <table class="table table-striped table-bordered table-hover table-checkable">
          <thead>
            <tr>
              <th> STT </th>
              <th> Tên game </th>
              <th> Giá tiền </th>
              <th class="dt-center"> Tác vụ </th>
            </tr>
          </thead>
          <tbody>
              <?php if (!$models) :?>
              <tr><td colspan="4"><?=Yii::t('app', 'no_data_found');?></td></tr>
              <?php endif;?>
              <?php foreach ($models as $key => $model) :?>
              <tr>
                <td><?=$key + $pages->offset + 1;?></td>
                <td style="vertical-align: middle;"><?=$model->game->title;?></td>
                <td style="vertical-align: middle;"><?=number_format($model->price);?></td>
                <td style="vertical-align: middle;">
                  <a href='#edit<?=$model->game_id;?>' class="btn btn-xs grey-salsa tooltips" data-pjax="0" data-container="body" data-original-title="Chỉnh sửa" data-toggle="modal"><i class="fa fa-pencil"></i></a>
                  <div class="modal fade" id="edit<?=$model->game_id;?>" tabindex="-1" role="basic" aria-hidden="true">
                    <div class="modal-dialog">
                      <div class="modal-content">
                        <div class="modal-header">
                          <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                          <h4 class="modal-title">Cập nhật giá cho game <?=$model->game->title;?></h4>
                        </div>
                        <?php $editForm = ActiveForm::begin([
                          'options' => ['class' => 'form-horizontal form-row-seperated edit-form'],
                          'action' => Url::to(['reseller/edit-price', 'game_id' => $model->game_id, 'reseller_id' => $model->reseller_id])
                        ]);?>
                        <div class="modal-body"> 
                          <div class="row">
                            <div class="col-md-12">
                              <?=$editForm->field($model, 'price', [
                                'options' => ['style' => 'margin: 10px', 'class' => 'form-group']
                              ])->textInput()->label(sprintf("%s (%s %s)", "Giá tiền trên 1 gói game", $model->game->pack, $model->game->unit_name));?>
                            </div>
                          </div>
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn dark btn-outline" data-dismiss="modal">Close</button>
                          <button type="submit" class="btn green">Save</button>
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
        <?=LinkPager::widget(['pagination' => $pages])?>
        <?php Pjax::end(); ?>
      </div>
    </div>
    <!-- END EXAMPLE TABLE PORTLET-->
  </div>
</div>
<div class="modal fade" id="add_new_modal" tabindex="-1" role="basic" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
        <h4 class="modal-title">Tạo giá cho nhân viên bán lẻ</h4>
      </div>
      <?php $newForm = ActiveForm::begin([
        'options' => ['class' => 'form-horizontal form-row-seperated edit-form', 'id' => 'add_new_form'],
        'action' => Url::to(['reseller/create-price', 'id' => $id])
      ]);?>
      <?=$newForm->field($newModel, 'reseller_id', [
        'options' => ['tag' => false],
        'template' => '{input}',
        'inputOptions' => ['value' => $id]
      ])->hiddenInput();?>
      <div class="modal-body"> 
        <div class="row">
          <div class="col-md-12">
            <?=$newForm->field($newModel, 'game_id', [
              'options' => ['style' => 'margin: 10px', 'class' => 'form-group'],
            ])->widget(kartik\select2\Select2::classname(), [
              'data' => $games,
            ])->label('Tên game')?>
            <?=$newForm->field($newModel, 'price', [
              'options' => ['style' => 'margin: 10px', 'class' => 'form-group']
            ])->textInput()->label(sprintf("%s", "Giá tiền trên 1 gói"));?>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn dark btn-outline" data-dismiss="modal">Close</button>
        <button type="submit" class="btn green">Save</button>
      </div>
      <?php ActiveForm::end();?>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<?php
$script = <<< JS
// new form
var newForm = new AjaxFormSubmit({element: '#add_new_form'});
newForm.success = function (data, form) {
  location.reload();
};
newForm.error = function (errors) {
  alert(errors);
  return false;
};

// edit forms
var editForm = new AjaxFormSubmit({element: '.edit-form'});
editForm.success = function (data, form) {
  location.reload();
};
editForm.error = function (errors) {
  alert(errors);
  return false;
};

// delete
$('.delete').ajax_action({
  method: 'DELETE',
  confirm: true,
  confirm_text: 'Bạn có muốn gỡ tính năng nhà bán lẻ của người dùng này không?',
  callback: function(data) {
    location.reload();
  },
});
JS;
$this->registerJs($script);
?>