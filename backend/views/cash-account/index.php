<?php
use yii\helpers\Url;
use yii\widgets\LinkPager;
use yii\widgets\ActiveForm;
use common\components\helpers\CommonHelper;
use yii\helpers\ArrayHelper;

$canManageAccount = Yii::$app->user->can('manager');
?>
<!-- BEGIN PAGE BAR -->
<div class="page-bar">
  <ul class="page-breadcrumb">
    <li>
      <a href="/"><?=Yii::t('app', 'home')?></a>
      <i class="fa fa-circle"></i>
    </li>
    <li>
      <a href="<?=Url::to(['bank/index']);?>">Quản lý tiền mặt</a>
      <i class="fa fa-circle"></i>
    </li>
    <li>
      <span>Các tài khoản tiền mặt</span>
    </li>
  </ul>
</div>
<!-- END PAGE BAR -->
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title">Các tài khoản tiền mặt</h1>
<!-- END PAGE TITLE-->
<div class="row">
  <div class="col-md-12">
    <!-- BEGIN EXAMPLE TABLE PORTLET-->
    <div class="portlet light">
      <div class="portlet-title">
        <?php if ($canManageAccount) : ?>
        <div class="actions">
          <div class="btn-group btn-group-devided">
            <a class="btn green" href="<?=Url::to(['cash-account/create']);?>"><?=Yii::t('app', 'add_new');?></a>
          </div>
        </div>
        <?php endif;?>
      </div>
      <div class="portlet-body">
        <div class="row margin-bottom-10">
          <?php $form = ActiveForm::begin(['method' => 'GET', 'action' => ['cash-account/index']]);?>
            <?=$form->field($search, 'bank_id', [
              'options' => ['class' => 'form-group col-md-4 col-lg-3'],
              'inputOptions' => ['class' => 'form-control', 'name' => 'bank_id']
            ])->dropDownList($search->fetchBank(), ['prompt' => 'Chọn quỹ tiền mặt'])->label('Quỹ tiền mặt');?>
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
              <th> Tên tài khoản </th>
              <th> Quỹ tiền mặt </th>
              <th> Số tiền hiện có </th>
              <th class="dt-center"> <?=Yii::t('app', 'actions');?> </th>
            </tr>
          </thead>
          <tbody>
              <?php if (!$models) :?>
              <tr><td colspan="5"><?=Yii::t('app', 'no_data_found');?></td></tr>
              <?php endif;?>
              <?php foreach ($models as $model) :?>
              <?php $amount = ArrayHelper::getValue($report, $model->id, 0);?>
              <tr>
                <td><?=$model->account_name;?></td>
                <td><?=$model->bank->name;?></td>
                <td>
                  <?=number_format($amount);?>
                </td>
                <td>
                  <?php if ($model->isRoot()) : ?>
                  <a class="btn btn-sm blue tooltips" href="<?=Url::to(['cash-transaction/topup-root', 'id' => $model->id]);?>" data-container="body" data-original-title="Nạp tiền vào <?=$model->account_name;?>"><i class="fa fa-arrow-up"></i> Nạp tiền</a>
                  <?php else : ?>
                  <?php if ($canManageAccount) : ?>
                  <a class="btn btn-sm blue tooltips" href="<?=Url::to(['cash-transaction/topup', 'id' => $model->id]);?>" data-container="body" data-original-title="Nạp tiền vào <?=$model->account_name;?>"><i class="fa fa-arrow-up"></i> Nạp tiền</a>
                  <?php endif;?>
                  <?php if ($amount > 0) : ?>
                  <a class="btn btn-sm purple tooltips" href="<?=Url::to(['cash-transaction/move', 'id' => $model->id]);?>" data-container="body" data-original-title="Chuyển tiền đến tài khoản khác"><i class="fa fa-arrow-right"></i> Chuyển cho nhân viên khác</a>
                  <a class="btn btn-sm green tooltips return-all" href="<?=Url::to(['cash-transaction/return-all-to-root', 'id' => $model->id]);?>" data-container="body" data-original-title="Hoàn trả toàn bộ về quỹ"><i class="fa fa-trash"></i> Hoàn trả toàn bộ</a>
                  <a class="btn btn-sm yellow tooltips" href="<?=Url::to(['cash-transaction/return-apart-to-root', 'id' => $model->id]);?>" data-container="body" data-original-title="Hoàn trả toàn bộ về quỹ"><i class="fa fa-trash"></i> Hoàn trả một phần</a>
                  <?php endif; ?>
                  <?php endif; ?>
                </td>
              </tr>
              <?php endforeach;?>
          </tbody>
        </table>
        <?=LinkPager::widget(['pagination' => $pages]);?>
      </div>
    </div>
    <!-- END EXAMPLE TABLE PORTLET-->
  </div>
</div>
<?php
$script = <<< JS
$(".return-all").ajax_action({
  method: 'POST',
  confirm: true,
  confirm_text: 'Bạn có muốn hoàn trả toàn bộ tiền mặt của tài khoản này về quỹ tiền mặt?',
  callback: function(eletement, data) {
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