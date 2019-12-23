<?php 
use yii\widgets\LinkPager;
use yii\widgets\Pjax;
use backend\components\datepicker\DatePicker;
use common\models\User;
use common\models\Country;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\web\JsExpression;
use backend\behaviors\UserSupplierBehavior;

?>

<!-- BEGIN PAGE BAR -->
<div class="page-bar">
  <ul class="page-breadcrumb">
    <li>
      <a href="/"><?=Yii::t('app', 'home');?></a>
      <i class="fa fa-circle"></i>
    </li>
    <li>
      <a href="<?=Url::to(['user/index']);?>">Quản lý khách hàng</a>
      <i class="fa fa-circle"></i>
    </li>
    <li>
      <span>Chưa có giao dịch</span>
    </li>
  </ul>
</div>
<!-- END PAGE BAR -->
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title">Chưa có giao dịch</h1>
<!-- END PAGE TITLE-->
<div class="row">
  <div class="col-md-12">
    <!-- BEGIN EXAMPLE TABLE PORTLET-->
    <div class="portlet light bordered">
      <div class="portlet-title">
        <div class="caption font-dark">
          <i class="icon-settings font-dark"></i>
          <span class="caption-subject bold uppercase"> Chưa có giao dịch</span>
        </div>
        <div class="actions">
          <?php if (Yii::$app->user->can('admin')) : ?>
          <a role="button" class="btn btn-warning" href="<?=Url::current(['mode' => 'export'])?>"><i class="fa fa-file-excel-o"></i> Export</a>
          <div class="btn-group btn-group-devided">
            <a class="btn green" href="<?=Url::to(['user/create', 'ref' => $ref]);?>"><?=Yii::t('app', 'add_new');?></a>
          </div>
          <?php endif;?>
        </div>
      </div>
      <div class="portlet-body">
        <div class="row margin-bottom-10">
          <?php $form = ActiveForm::begin(['method' => 'GET', 'action' => ['user/no-order']]);?>
            <?=$form->field($search, 'no_purchase_start', [
              'options' => ['class' => 'form-group col-md-4 col-lg-3'],
              'inputOptions' => ['class' => 'form-control', 'name' => 'no_purchase_start']
            ])->widget(DatePicker::className(), [
                'clientOptions' => [
                    'autoclose' => true,
                    'format' => 'yyyy-mm-dd'
                ]
            ])->label('Không có đơn hàng từ');?>

            <?=$form->field($search, 'no_purchase_end', [
              'options' => ['class' => 'form-group col-md-4 col-lg-3'],
              'inputOptions' => ['class' => 'form-control', 'name' => 'no_purchase_end']
            ])->widget(DatePicker::className(), [
                'clientOptions' => [
                    'autoclose' => true,
                    'format' => 'yyyy-mm-dd'
                ]
            ])->label('Không có đơn hàng đến');?>


            <div class="form-group col-md-4 col-lg-3">
              <button type="submit" class="btn btn-success table-group-action-submit"
                style="margin-top:
                25px;">
              <i class="fa fa-check"></i> <?=Yii::t('app', 'search');?>
              </button>
            </div>
          <?php ActiveForm::end()?>
        </div>
        <table class="table table-striped table-bordered table-hover table-checkable">
          <thead>
            <tr>
              <th> ID </th>
              <th> Khách hàng </th>
              <th> Ngày sinh </th>
              <th> Email </th>
              <th> Username </th>
              <th> Số điện thoại </th>
              <th> Ngày đăng ký </th>
              <th> Quốc tịch </th>
              <th> Đơn hàng cuối cùng </th>
              <th> Tổng tiền nạp </th>
              <th> Tổng tiền mua hàng </th>
              <th> Số dư hiện tại</th>
              <th> Reseller/Khách hàng </th>
              <th> Đại lý/người bán </th>
              <th> Tác vụ </th>
            </tr>
          </thead>
          <tbody>
            <?php if ($models) : ?>
            <?php foreach ($models as $model) : ?>
            <?php $model->attachBehavior('supplier', new UserSupplierBehavior);?>
            <tr>
              <td><?=$model->id;?></td>
              <td><?=$model->name;?></td>
              <td><?=$model->birthday;?></td>
              <td><?=$model->email;?></td>
              <td><?=$model->username;?></td>
              <td><?=$model->phone;?></td>
              <td><?=$model->created_at;?></td>
              <td><?=$model->getCountryName();?></td>
              <td><?=($model->order) ? $model->order->created_at : '';?></td>
              <td><?=$model->getWalletTopupAmount();?></td>
              <td><?=$model->getWalletWithdrawAmount();?></td>
              <td><?=$model->getWalletAmount();?></td>
              <td>
                <?php if (Yii::$app->user->can('sale_manager')) : ?>
                <?php if ($model->isReseller()) : ?>
                <a href="<?=Url::to(['user/downgrade-reseller', 'id' => $model->id]);?>" class="btn btn-sm purple link-action tooltips" data-container="body" data-original-title="Bỏ tư cách nhà bán lẻ"><i class="fa fa-times"></i> Reseller </a>
                <?php else : ?>
                <a href="<?=Url::to(['user/upgrade-reseller', 'id' => $model->id]);?>" class="btn btn-sm default link-action tooltips" data-container="body" data-original-title="Nâng cấp lên nhà bán lẻ"><i class="fa fa-arrow-up"></i> Reseller </a>
                <?php endif; ?>

                <?php if ($model->isSupplier()) : ?>
                <a href="<?=Url::to(['supplier/remove', 'id' => $model->id]);?>" class="btn btn-sm blue link-action tooltips" data-container="body" data-original-title="Bỏ tư cách nhà cung cấp"><i class="fa fa-times"></i> Supplier </a>
                <?php else : ?>
                <a href="<?=Url::to(['supplier/create', 'id' => $model->id]);?>" class="btn btn-sm default link-action tooltips" data-container="body" data-original-title="Nâng cấp lên nhà cung cấp"><i class="fa fa-arrow-up"></i> Supplier </a>
                <?php endif; ?>

                <?php endif; ?>
              </td>
              <td></td>
              <td>
                <?php if (Yii::$app->user->can('sale_manager')) : ?>
                <?php if ($model->isActive()) : ?>
                <a class="btn btn-xs red tooltips link-action" href="<?=Url::to(['user/inactive', 'id' => $model->id]);?>" data-container="body" data-original-title="Inactive this user"><i class="fa fa-times"></i></a>
                <?php endif;?>
                <?php if ($model->isInactive()) : ?>
                <a class="btn btn-xs green tooltips link-action" href="<?=Url::to(['user/active', 'id' => $model->id]);?>" data-container="body" data-original-title="Active this user"><i class="fa fa-check"></i></a>
                <?php endif;?>
                <?php endif;?>
                <?php if (Yii::$app->user->can('admin')) : ?>
                <a class="btn btn-xs default tooltips" href="<?=Url::to(['user/edit', 'id' => $model->id]);?>" data-container="body" data-original-title="Edit user"><i class="fa fa-pencil"></i></a>
                <?php endif;?>

                <!-- trust -->
                <?php if ($model->isTrust()) : ?>
                <a href="<?=Url::to(['user/update-not-trust', 'id' => $model->id]);?>" class="btn btn-sm purple link-action tooltips" data-container="body" data-original-title="Bạn đang tín nhiệm khách hàng này"><i class="fa fa-shield"></i></a>
                <?php else : ?>
                <a href="<?=Url::to(['user/update-trust', 'id' => $model->id]);?>" class="btn btn-sm default link-action tooltips" data-container="body" data-original-title="Khách này chưa được tín nhiệm"><i class="fa fa-shield"></i> </a>
                <?php endif; ?>
              </td>
            </tr>
            <?php endforeach;?>
            <?php else : ?>
            <tr>
              <td colspan="15"><?=Yii::t('app', 'no_data_found');?></td>
            </tr>
            <?php endif;?>
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
$(".delete-user").ajax_action({
  confirm: true,
  confirm_text: 'Bạn có chắc muốn thực hiện tác vụ này?',
  callback: function(eletement, data) {
    location.reload();
  }
});
$(".active-user").ajax_action({
  confirm: true,
  confirm_text: 'Bạn có chắc muốn thực hiện tác vụ này?',
  callback: function(eletement, data) {
    location.reload();
  }
});
$(".link-action").ajax_action({
  confirm: true,
  confirm_text: 'Bạn có chắc muốn thực hiện tác vụ này?',
  callback: function(eletement, data) {
    location.reload();
  }
});
JS;
$this->registerJs($script);
?>