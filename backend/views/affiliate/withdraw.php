<?php
use yii\widgets\LinkPager;
use yii\widgets\Pjax;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use yii\web\JsExpression;
use backend\components\datetimepicker\DateTimePicker;
use backend\models\Order;
use common\models\User;
use common\components\helpers\FormatConverter;
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
      <span>Yêu cầu rút hoa hồng</span>
    </li>
  </ul>
</div>
<!-- END PAGE BAR -->
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title">Yêu cầu rút hoa hồng</h1>
<!-- END PAGE TITLE-->
<div class="row">
  <div class="col-md-12">
    <!-- BEGIN EXAMPLE TABLE PORTLET-->
    <div class="portlet light bordered">
      <div class="portlet-title">
        <div class="caption font-dark">
          <i class="icon-settings font-dark"></i>
          <span class="caption-subject bold uppercase">Yêu cầu rút hoa hồng</span>
        </div>
      </div>
      <div class="portlet-body">
        <?php $form = ActiveForm::begin(['method' => 'GET', 'action' => ['affiliate/withdraw']]);?>
        <div class="row margin-bottom-10">
            <?php $user = $search->getUser();?>
            <?=$form->field($search, 'user_id', [
              'options' => ['class' => 'form-group col-md-4 col-lg-3'],
            ])->widget(kartik\select2\Select2::classname(), [
              'initValueText' => ($search->user_id) ? sprintf("%s - %s", $user->username, $user->email) : '',
              'options' => ['class' => 'form-control', 'name' => 'user_id'],
              'pluginOptions' => [
                'placeholder' => 'Chọn khách hàng',
                'allowClear' => true,
                'minimumInputLength' => 3,
                'ajax' => [
                    'url' => Url::to(['user/suggestion']),
                    'dataType' => 'json',
                    'processResults' => new JsExpression('function (data) {return {results: data.data.items};}')
                ]
              ]
            ])->label('Khách hàng')?>

            <div class="form-group col-md-4 col-lg-3">
              <button type="submit" class="btn btn-success table-group-action-submit" style="margin-top: 25px;">
                <i class="fa fa-check"></i> <?=Yii::t('app', 'search')?>
              </button>
            </div>
        </div>
        <?php ActiveForm::end()?>
        <?php Pjax::begin(); ?>
        <table class="table table-striped table-bordered table-hover table-checkable">
          <thead>
            <tr>
              <th> STT </th>
              <th> Tên </th>
              <th> Email </th>
              <th> Số điện thoại </th>
              <th> Sô tiền </th>
              <th> Ngày gửi yêu cầu </th>
              <th> Người phê duyệt </th>
              <th> Ngày phê duyệt </th>
              <th> Người thực hiện </th>
              <th> Ngày thực hiện </th>
              <th> Ghi chú </th>
              <th> Ảnh đính kèm </th>
              <th class="dt-center"> <?=Yii::t('app', 'actions');?> </th>
            </tr>
          </thead>
          <tbody>
              <?php if (!$models) :?>
              <tr><td colspan="13"><?=Yii::t('app', 'no_data_found');?></td></tr>
              <?php endif;?>
              <?php foreach ($models as $no => $model) :?>
              <tr>
                <td><?=$no + $pages->offset + 1;?></td>
                <td><?=$model->user->name;?></td>
                <td><?=$model->user->email;?></td>
                <td><?=$model->user->phone;?></td>
                <td><?=number_format($model->amount);?></td>
                <td><?=$model->created_at;?></td>
                <td><?=($model->acceptor) ? $model->acceptor->name : '';?></td>
                <td><?=$model->approved_at;?></td>
                <td><?=($model->executor) ? $model->executor->name : '';?></td>
                <td><?=$model->executed_at;?></td>
                <td><?=$model->note;?></td>
                <td><?=$model->evidence;?></td>
                <td>
                  <?php if ($model->isRequest()) : ?>
                  <a href="<?=Url::to(['affiliate/execute-withdraw', 'id' => $model->id, 'action' => 'disapprove']);?>" class="btn btn-sm default link-action tooltips" data-container="body" data-original-title="Không phê duyệt"><i class="fa fa-arrow-down"></i> Disapprove </a>
                  <a href="<?=Url::to(['affiliate/execute-withdraw', 'id' => $model->id, 'action' => 'approve']);?>" class="btn btn-sm yellow link-action tooltips" data-container="body" data-original-title="Phê duyệt"><i class="fa fa-arrow-up"></i> Approve </a>

                  <?php elseif ($model->isApprove()) : ?>
                  <a href="<?=Url::to(['affiliate/execute-withdraw', 'id' => $model->id, 'action' => 'disapprove']);?>" class="btn btn-sm default link-action tooltips" data-container="body" data-original-title="Không phê duyệt"><i class="fa fa-arrow-down"></i> Disapprove </a>
                  <a href="#execute-modal<?=$model->id;?>" class="btn btn-sm yellow" data-toggle="modal"><i class="fa fa-arrow-up"></i> Execute </a>
                  
                  <div class="modal fade" id="execute-modal<?=$model->id;?>" tabindex="-1" role="basic" aria-hidden="true">
                    <div class="modal-dialog">
                      <div class="modal-content">
                        <div class="modal-header">
                          <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                          <h4 class="modal-title">Thực thi yêu cầu rút tiền của khách hàng</h4>
                        </div>
                        <div class="modal-body" style="height: 200px; position: relative; overflow: auto; display: block;"> 
                          <?= Html::beginForm(['affiliate/execute-withdraw', 'id' => $model->id, 'action' => 'execute'], 'POST', ['class' => 'execute-form', 'id' => 'execute-form' . $model->id]); ?>
                            <div class="form-group">
                                <label>Nội dung chi tiết</label>
                                <?= Html::textArea('note', '', ['class' => 'form-control execute-control-note']); ?>
                            </div>
                            <button type="submit" class="btn btn-default" data-toggle="modal"> Gửi</button>
                          <?= Html::endForm(); ?>
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn dark btn-outline" data-dismiss="modal">Close</button>
                        </div>
                      </div>
                      <!-- /.modal-content -->
                    </div>
                    <!-- /.modal-dialog -->
                  </div>

                  <?php elseif ($model->isExecuted()) : ?>
                  <span class="label label-default">Completed</span>
                  <?php endif;?>
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
<?php
$script = <<< JS
$(".link-action").ajax_action({
  confirm: true,
  confirm_text: 'Bạn có chắc muốn thực hiện tác vụ này?',
  callback: function(eletement, data) {
    location.reload();
  }
});

var executeForm = new AjaxFormSubmit({element: '.execute-form'});
executeForm.success = function (data, form) {
  location.reload();
};

executeForm.validate = function(form) {
  var text = $.trim($(form).find('textarea').val());
  if (text == '') {
    return false;
  }
  return true;
}

executeForm.invalid = function(form) {
  alert('Nội dung không được để trống');
}
JS;
$this->registerJs($script);
?>