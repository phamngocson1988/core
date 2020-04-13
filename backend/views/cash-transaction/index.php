<?php
use yii\helpers\Url;
use yii\widgets\LinkPager;
use yii\widgets\ActiveForm;
use common\components\helpers\CommonHelper;
use backend\components\datetimepicker\DateTimePicker;
?>
<!-- BEGIN PAGE BAR -->
<div class="page-bar">
  <ul class="page-breadcrumb">
    <li>
      <a href="/"><?=Yii::t('app', 'home')?></a>
      <i class="fa fa-circle"></i>
    </li>
    <li>
      <a href="<?=Url::to(['cash/index']);?>">Quỹ tiền mặt</a>
      <i class="fa fa-circle"></i>
    </li>
    <li>
      <span>Các giao dịch tiền mặt</span>
    </li>
  </ul>
</div>
<!-- END PAGE BAR -->
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title">Các giao dịch tiền mặt</h1>
<!-- END PAGE TITLE-->
<div class="row">
  <div class="col-md-12">
    <!-- BEGIN EXAMPLE TABLE PORTLET-->
    <div class="portlet light">
      <div class="portlet-title">
      </div>
      <div class="portlet-body">
        <div class="row margin-bottom-10">
          <?php $form = ActiveForm::begin(['method' => 'GET', 'action' => ['cash-transaction/index']]);?>
            <?=$form->field($search, 'bank_id', [
              'options' => ['class' => 'form-group col-md-4 col-lg-3'],
              'inputOptions' => ['class' => 'form-control', 'name' => 'bank_id', 'onchange' => 'js:$(this).closest("form").submit();']
            ])->dropDownList($search->fetchBank(), ['prompt' => 'Chọn quỹ tiền mặt'])->label('Quỹ tiền mặt');?>
            <?=$form->field($search, 'bank_account_id', [
              'options' => ['class' => 'form-group col-md-4 col-lg-3'],
              'inputOptions' => ['class' => 'form-control', 'name' => 'bank_account_id']
            ])->dropDownList($search->fetchBankAccount(), ['prompt' => 'Chọn tài khoản'])->label('Tài khoản');?>
            <?= $form->field($search, 'from_date', [
              'options' => ['class' => 'form-group col-md-4 col-lg-3'],
              'inputOptions' => ['class' => 'form-control', 'name' => 'from_date', 'id' => 'from_date']
            ])->widget(DateTimePicker::className(), [
              'clientOptions' => [
                'autoclose' => true,
                'format' => 'yyyy-mm-dd',
                'endDate' => date('Y-m-d'),
                'minView' => '2'
              ],
            ])->label('Ngày tạo từ');?>
            <?=$form->field($search, 'to_date', [
              'options' => ['class' => 'form-group col-md-4 col-lg-3'],
              'inputOptions' => ['class' => 'form-control', 'name' => 'to_date', 'id' => 'to_date']
            ])->widget(DateTimePicker::className(), [
                'clientOptions' => [
                  'autoclose' => true,
                  'format' => 'yyyy-mm-dd',
                  'todayBtn' => true,
                  'minuteStep' => 1,
                  'endDate' => date('Y-m-d'),
                  'minView' => '2'
                ],
            ])->label('Ngày tạo đến');?>
            <div class="form-group col-md-4 col-lg-3">
              <button type="submit" class="btn btn-success table-group-action-submit"
                style="margin-top:
                25px;">
              <i class="fa fa-check"></i> <?=Yii::t('app', 'search');?>
              </button>
            </div>
          <?php ActiveForm::end()?>
        </div>
      </div>
      <div class="table-responsive">
        <table class="table table-striped table-bordered table-hover table-checkable">
          <thead>
            <tr>
              <th> Ngày khởi tạo </th>
              <th> Tài khoản </th>
              <th> Loại giao dịch </th>
              <th> Số tiền </th>
              <th> Trạng thái </th>
              <th class="dt-center"> <?=Yii::t('app', 'actions');?> </th>
            </tr>
          </thead>
          <tbody>
              <?php if (!$models) :?>
              <tr><td colspan="6"><?=Yii::t('app', 'no_data_found');?></td></tr>
              <?php endif;?>
              <?php foreach ($models as $model) :?>
              <tr>
                <td><?=$model->created_at;?></td>
                <td><?=$model->bankAccount->account_name;?></td>
                <td>
                  <?php if ($model->isTypeIn()) : ?>
                    <span class="label label-info"> Nạp tiền </span>
                  <?php else :?>
                    <span class="label label-warning"> Chuyển tiền </span>
                  <?php endif;?>
                </td>
                <td><?=sprintf("%s (%s)", number_format(abs($model->amount)), $model->currency);?></td>
                <td>
                  <?php if ($model->isPending()) : ?>
                    <span class="label label-default"> Giao dịch tạm </span>
                  <?php else :?>
                    <span class="label label-primary"> Đã hoàn thành </span>
                  <?php endif;?>
                </td>
                <td>
                  <?php if ($model->isPending()) : ?>
                  <a class="btn btn-sm grey-salsa tooltips delete-transaction" href="<?=Url::to(['cash-transaction/delete', 'id' => $model->id]);?>" data-container="body" data-original-title="Xóa giao dịch tạm"><i class="fa fa-times"></i> Xóa</a>
                  <a class="btn btn-sm green tooltips complete-transaction" href="<?=Url::to(['cash-transaction/complete', 'id' => $model->id]);?>" data-container="body" data-original-title="Xác nhận giao dịch tạm"><i class="fa fa-check"></i> Xác nhận</a>
                  <?php endif;?>
                  <a class="btn btn-sm blue tooltips" href='#view<?=$model->id;?>' data-container="body" data-original-title="Chi tiết giao dịch" data-toggle="modal"><i class="fa fa-eye"></i> Xem</a>

                  <div class="modal fade" id="view<?=$model->id;?>" tabindex="-1" role="basic" aria-hidden="true">
                      <div class="modal-dialog">
                        <div class="modal-content">
                          <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                            <h4 class="modal-title">Chi tiết giao dịch</h4>
                          </div>
                          <div class="modal-body"> 
                            <div class="table-responsive">
                              <table class="table table-striped table-bordered table-hover table-checkable">
                                <tbody>
                                  <tr>
                                    <th>Ngày thực hiện</th>
                                    <td><?=$model->created_at;?></td>
                                  </tr>
                                  <tr>
                                    <th>Người thực hiện</th>
                                    <td><?=$model->creator->name;?></td>
                                  </tr>
                                  <tr>
                                    <th>Loại giao dịch</th>
                                    <td>
                                      <?php if ($model->isTypeIn()) : ?>
                                        <span class="label label-info"> Nạp tiền </span>
                                      <?php else :?>
                                        <span class="label label-warning"> Chuyển tiền </span>
                                      <?php endif;?>
                                    </td>
                                  </tr>
                                  <tr>
                                    <th>Số tiền</th>
                                    <td><?=sprintf("%s %s", number_format($model->amount), $model->currency);?></td>
                                  </tr>
                                  <tr>
                                    <th>Tài khoản</th>
                                    <td><?=$model->bankAccount->account_name;?></td>
                                  </tr>
                                  <tr>
                                    <th>Ghi chú</th>
                                    <td><?=nl2br($model->description);?></td>
                                  </tr>
                                  <tr>
                                    <th>Trạng thái</th>
                                    <td>
                                      <?php if ($model->isPending()) : ?>
                                        <span class="label label-default"> Giao dịch tạm </span>
                                      <?php else :?>
                                        <span class="label label-primary"> Đã hoàn thành </span>
                                      <?php endif;?>
                                    </td>
                                  </tr>
                                </tbody>
                              </table>
                            </div>
                          </div>
                          <div class="modal-footer">
                            <button type="button" class="btn dark btn-outline" data-dismiss="modal">Đóng</button>
                          </div>
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
      </div>
    </div>
    <!-- END EXAMPLE TABLE PORTLET-->
  </div>
</div>
<?php
$script = <<< JS
$(".complete-transaction").ajax_action({
  method: 'POST',
  confirm: true,
  confirm_text: 'Bạn có muốn xác nhận hoàn tất giao dịch này không?',
  callback: function(eletement, data) {
    location.reload();
  },
  error: function(element, errors) {
    console.log(errors);
    alert(errors);
  }
});

// delete
$('.delete-transaction').ajax_action({
  method: 'POST',
  confirm: true,
  confirm_text: 'Bạn có muốn xóa giao dịch này không?',
  callback: function(data) {
    location.reload();
  },
  error: function(element, errors) {
    console.log(errors);
    alert(errors);
  }
});
JS;
$this->registerJs($script);
?>