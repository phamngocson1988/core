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
      <span>Quản lý mẫu tin</span>
    </li>
  </ul>
</div>
<!-- END PAGE BAR -->
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title">Quản lý mẫu tin</h1>
<!-- END PAGE TITLE-->
<div class="row">
  <div class="col-md-12">
    <!-- BEGIN EXAMPLE TABLE PORTLET-->
    <div class="portlet light bordered">
      <div class="portlet-title">
        <div class="caption font-dark">
          <i class="icon-settings font-dark"></i>
          <span class="caption-subject bold uppercase"> Quản lý mẫu tin</span>
        </div>
        <div class="actions">
          <div class="btn-group btn-group-devided">
            <a class="btn green" href="#new" data-toggle="modal"><?=Yii::t('app', 'add_new')?></a>
          </div>
        </div>
      </div>
      <div class="portlet-body">
        <?php Pjax::begin(); ?>
        <table class="table table-striped table-bordered table-hover table-checkable">
          <thead>
            <tr>
              <th style="width: 5%;"> <?=Yii::t('app', 'no');?> </th>
              <th style="width: 90%;"> Nội dung </th>
              <th style="width: 5%;" class="dt-center"> <?=Yii::t('app', 'actions');?> </th>
            </tr>
          </thead>
          <tbody>
              <?php if (!$models) :?>
              <tr><td colspan="3"><?=Yii::t('app', 'no_data_found');?></td></tr>
              <?php endif;?>
              <?php foreach ($models as $model) :?>
              <tr>
                <td style="vertical-align: middle;">#<?=$model->id;?></td>
                <td style="vertical-align: middle;"><?=$model->content;?></td>
                <td style="vertical-align: middle;">
                  <a href='#edit<?=$model->id;?>' class="btn btn-xs grey-salsa tooltips" data-pjax="0" data-container="body" data-original-title="Chỉnh sửa" data-toggle="modal"><i class="fa fa-pencil"></i></a>
                  <a href='<?=Url::to(['order-complain/delete', 'id' => $model->id]);?>' class="btn btn-xs grey-salsa tooltips delete" data-pjax="0" data-container="body" data-original-title="Xoá"><i class="fa fa-trash"></i></a>
                  <div class="modal fade" id="edit<?=$model->id;?>" tabindex="-1" role="basic" aria-hidden="true">
                    <div class="modal-dialog">
                      <div class="modal-content">
                        <div class="modal-header">
                          <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                          <h4 class="modal-title">Chỉnh sửa mẫu nội dung phản hồi khách hàng</h4>
                        </div>
                        <?php $editForm = ActiveForm::begin(['options' => ['class' => 'form-horizontal form-row-seperated edit-form', 'id' => 'edit-form-' . $model->id], 'action' => Url::to(['order-complain/edit', 'id' => $model->id])]);?>
                        <div class="modal-body"> 
                          <div class="row">
                            <div class="col-md-12">
                              <?=$editForm->field($model, 'content', [
                                'options' => ['style' => 'margin: 10px'],
                                'inputOptions' => ['class' => 'form-control', 'id' => 'content' . $model->id]
                              ])->widget(\common\widgets\TinyMce::className(), ['options' => ['rows' => 10]])->label('Nội dung');?>
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

<div class="modal fade" id="new" tabindex="-1" role="basic" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
        <h4 class="modal-title">Tạo mới mẫu nội dung phản hồi khách hàng</h4>
      </div>
      <?php $newForm = ActiveForm::begin(['options' => ['class' => 'form-horizontal form-row-seperated', 'id' => 'new-form'], 'action' => Url::to(['order-complain/create'])]);?>
      <div class="modal-body"> 
        <div class="row">
          <div class="col-md-12">
            <?=$newForm->field($template, 'content', [
              'options' => ['style' => 'margin: 10px'],
              'inputOptions' => ['class' => 'form-control', 'id' => 'new-content']
              ])->widget(\common\widgets\TinyMce::className(), ['options' => ['rows' => 10]])->label('Nội dung');?>
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
var newForm = new AjaxFormSubmit({element: '#new-form'});
newForm.success = function (data, form) {
  window.location.href = "###REDIRECT###";
};
newForm.error = function (errors) {
  alert(errors);
  return false;
};

var editForm = new AjaxFormSubmit({element: '.edit-form'});
editForm.success = function (data, form) {
  window.location.href = "###REDIRECT###";
};
editForm.error = function (errors) {
  alert(errors);
  return false;
}

// delete
$('.delete').ajax_action({
  method: 'DELETE',
  confirm: true,
  confirm_text: 'Bạn có muốn xóa mẫu tin này không?',
  callback: function(data) {
    window.location.href = "###REDIRECT###";
  },
});
JS;
$redirect = Url::to(['order-complain/index']);
$script = str_replace('###REDIRECT###', $redirect, $script);
$this->registerJs($script);
?>