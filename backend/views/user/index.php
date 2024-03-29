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
use yii\helpers\Html;

// saler team
$salerTeamIds = Yii::$app->authManager->getUserIdsByRole('saler');
$salerTeamManagerIds = Yii::$app->authManager->getUserIdsByRole('saler_manager');
$salerTeamIds = array_merge($salerTeamIds, $salerTeamManagerIds);
$salerTeamIds = array_unique($salerTeamIds);
$salerTeamObjects = User::findAll($salerTeamIds);
$salerTeams = ArrayHelper::map($salerTeamObjects, 'id', 'email');
?>

<!-- BEGIN PAGE BAR -->
<div class="page-bar">
  <ul class="page-breadcrumb">
    <li>
      <a href="/"><?=Yii::t('app', 'home');?></a>
      <i class="fa fa-circle"></i>
    </li>
    <li>
      <span>Quản lý khách hàng</span>
    </li>
  </ul>
</div>
<!-- END PAGE BAR -->
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title">Quản lý khách hàng</h1>
<!-- END PAGE TITLE-->
<div class="row">
  <div class="col-md-12">
    <!-- BEGIN EXAMPLE TABLE PORTLET-->
    <div class="portlet light bordered">
      <div class="portlet-title">
        <div class="caption font-dark">
          <i class="icon-settings font-dark"></i>
          <span class="caption-subject bold uppercase"> Quản lý khách hàng</span>
        </div>
        <div class="actions">
          <?php if (Yii::$app->user->can('sale_manager')) : ?>
          <a role="button" class="btn btn-warning" href="<?=Url::current(['mode' => 'export'])?>"><i class="fa fa-file-excel-o"></i> Export</a>
          <div class="btn-group btn-group-devided">
            <a class="btn green" href="<?=Url::to(['user/create', 'ref' => $ref]);?>"><?=Yii::t('app', 'add_new');?></a>
          </div>
          <?php endif;?>
        </div>
      </div>
      <div class="portlet-body">
        <div class="row margin-bottom-10">
          <?php $form = ActiveForm::begin(['method' => 'GET', 'action' => ['user/index']]);?>
            <?=$form->field($search, 'user_id', [
              'options' => ['class' => 'form-group col-md-4 col-lg-3'],
            ])->widget(kartik\select2\Select2::classname(), [
              'initValueText' => ($search->user_id) ? sprintf("%s - %s", $search->getCustomer()->username, $search->getCustomer()->email) : '',
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
            ])->label('Khách hàng');?>

            <?=$form->field($search, 'phone', [
              'options' => ['class' => 'form-group col-md-4 col-lg-3'],
              'inputOptions' => ['class' => 'form-control', 'name' => 'phone']
            ])->textInput()->label('Số điện thoại');?>

            <?=$form->field($search, 'country_code', [
              'options' => ['class' => 'form-group col-md-4 col-lg-3'],
              'inputOptions' => ['class' => 'form-control', 'name' => 'country_code']
            ])->dropDownList(ArrayHelper::map(Country::fetchAll(), 'country_code', 'country_name'), ['prompt' => 'Quốc gia'])->label('Tên quốc gia');?>

            <?=$form->field($search, 'game_id', [
              'options' => ['class' => 'form-group col-md-4 col-lg-3'],
              'inputOptions' => ['class' => 'form-control', 'name' => 'game_id']
            ])->dropDownList($search->fetchGames(), ['prompt' => 'Tìm theo game'])->label('Tên game');?>

            <?=$form->field($search, 'created_start', [
              'options' => ['class' => 'form-group col-md-4 col-lg-3'],
              'inputOptions' => ['class' => 'form-control', 'name' => 'created_start']
            ])->widget(DatePicker::className(), [
                'clientOptions' => [
                    'autoclose' => true,
                    'format' => 'yyyy-mm-dd'
                ]
            ])->label('Ngày tham gia từ');?>
            <?=$form->field($search, 'created_end', [
              'options' => ['class' => 'form-group col-md-4 col-lg-3'],
              'inputOptions' => ['class' => 'form-control', 'name' => 'created_end']
            ])->widget(DatePicker::className(), [
                'clientOptions' => [
                    'autoclose' => true,
                    'format' => 'yyyy-mm-dd'
                ]
            ])->label('Ngày tham gia đến');?>

            <?=$form->field($search, 'birthday_start', [
              'options' => ['class' => 'form-group col-md-4 col-lg-3'],
              'inputOptions' => ['class' => 'form-control', 'name' => 'birthday_start']
            ])->widget(DatePicker::className(), [
                'clientOptions' => [
                    'autoclose' => true,
                    'format' => 'yyyy-mm-dd'
                ]
            ])->label('Có sinh nhật từ');?>
            <?=$form->field($search, 'birthday_end', [
              'options' => ['class' => 'form-group col-md-4 col-lg-3'],
              'inputOptions' => ['class' => 'form-control', 'name' => 'birthday_end']
            ])->widget(DatePicker::className(), [
                'clientOptions' => [
                    'autoclose' => true,
                    'format' => 'yyyy-mm-dd'
                ]
            ])->label('Có sinh nhật đến');?>

            <?=$form->field($search, 'purchase_start', [
              'options' => ['class' => 'form-group col-md-4 col-lg-3'],
              'inputOptions' => ['class' => 'form-control', 'name' => 'purchase_start']
            ])->widget(DatePicker::className(), [
                'clientOptions' => [
                    'autoclose' => true,
                    'format' => 'yyyy-mm-dd'
                ]
            ])->label('Có đơn hàng từ');?>
            <?=$form->field($search, 'purchase_end', [
              'options' => ['class' => 'form-group col-md-4 col-lg-3'],
              'inputOptions' => ['class' => 'form-control', 'name' => 'purchase_end']
            ])->widget(DatePicker::className(), [
                'clientOptions' => [
                    'autoclose' => true,
                    'format' => 'yyyy-mm-dd'
                ]
            ])->label('Có đơn hàng đến');?>

            <?=$form->field($search, 'total_purchase_start', [
              'options' => ['class' => 'form-group col-md-4 col-lg-3'],
              'inputOptions' => ['class' => 'form-control', 'name' => 'total_purchase_start']
            ])->textInput()->label('Tổng giá trị đơn hàng từ');?>

            <?=$form->field($search, 'total_purchase_end', [
              'options' => ['class' => 'form-group col-md-4 col-lg-3'],
              'inputOptions' => ['class' => 'form-control', 'name' => 'total_purchase_end']
            ])->textInput()->label('Tổng giá trị đơn hàng đến');?>

            <?=$form->field($search, 'saler_id', [
              'options' => ['class' => 'form-group col-md-4 col-lg-3'],
              'inputOptions' => ['class' => 'form-control', 'name' => 'saler_id']
            ])->dropDownList($search->fetchSalers(),  ['prompt' => 'Tìm nhân viên bán hàng'])->label('Nhân viên bán hàng');?>

            <div class="form-group col-md-4 col-lg-3">
              <button type="submit" class="btn btn-success table-group-action-submit"
                style="margin-top:
                25px;">
              <i class="fa fa-check"></i> <?=Yii::t('app', 'search');?>
              </button>
            </div>
          <?php ActiveForm::end()?>
        </div>
        <div class="table-responsive">
        <table class="table table-bordered">
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
              <th class="hidden-xs"> Đơn hàng cuối cùng </th>
              <th class="hidden-xs"> Tổng tiền nạp </th>
              <th class="hidden-xs"> Tổng tiền mua hàng </th>
              <th> Số dư hiện tại</th>
              <th> Reseller/Khách hàng </th>
              <th> Đại lý/người bán </th>
              <th> Nhân viên quản lý </th>
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
              <td class="hidden-xs"><?=($model->order) ? $model->order->created_at : '';?></td>
              <td class="hidden-xs"><?=$model->getWalletTopupAmount();?></td>
              <td class="hidden-xs"><?=$model->getWalletWithdrawAmount();?></td>
              <td><?=$model->getWalletAmount();?></td>
              <td>
                <?php if (Yii::$app->user->can('sale_manager')) : ?>
                <?php if ($model->isReseller()) : ?>
                <a href="<?=Url::to(['user/downgrade-reseller', 'id' => $model->id]);?>" class="btn btn-sm purple link-action tooltips" data-container="body" data-original-title="Bỏ tư cách nhà bán lẻ"><i class="fa fa-times"></i> Reseller </a>
                <?php else : ?>
                <!-- <a href="<?=Url::to(['user/upgrade-reseller', 'id' => $model->id]);?>" class="btn btn-sm default link-action tooltips" data-container="body" data-original-title="Nâng cấp lên nhà bán lẻ"><i class="fa fa-arrow-up"></i> Reseller </a> -->
                <a href='#create-reseller<?=$model->id;?>' class="btn btn-sm default tooltips" data-container="body" data-original-title="Tạo nhà bán lẻ"data-toggle="modal" data-level="up" ><i class="fa fa-arrow-up"></i>Reseller</a>
                <?php endif; ?>

                <?php if ($model->isSupplier()) : ?>
                <a href="<?=Url::to(['supplier/remove', 'id' => $model->id]);?>" class="btn btn-sm blue link-action tooltips" data-container="body" data-original-title="Bỏ tư cách nhà cung cấp"><i class="fa fa-times"></i> Supplier </a>
                <?php else : ?>
                <a href="<?=Url::to(['supplier/create', 'id' => $model->id]);?>" class="btn btn-sm default link-action tooltips" data-container="body" data-original-title="Nâng cấp lên nhà cung cấp"><i class="fa fa-arrow-up"></i> Supplier </a>
                <?php endif; ?>

                <?php endif; ?>
              </td>
              <td></td>
              <td><?=$model->saler ? $model->saler->getName() : '-';?></td>
              <td>
                <?php if (Yii::$app->user->can('sale_manager')) : ?>
                <?php if ($model->isActive()) : ?>
                <a class="btn btn-xs red tooltips link-action" href="<?=Url::to(['user/inactive', 'id' => $model->id]);?>" data-container="body" data-original-title="Inactive this user"><i class="fa fa-times"></i></a>
                <?php endif;?>
                <?php if ($model->isInactive()) : ?>
                <a class="btn btn-xs green tooltips link-action" href="<?=Url::to(['user/active', 'id' => $model->id]);?>" data-container="body" data-original-title="Active this user"><i class="fa fa-check"></i></a>
                <?php endif;?>
                <?php endif;?>
                <?php if (Yii::$app->user->can('sale_manager')) : ?>
                <a class="btn btn-xs default tooltips" href="<?=Url::to(['user/edit', 'id' => $model->id]);?>" data-container="body" data-original-title="Edit user"><i class="fa fa-pencil"></i></a>
                <?php endif;?>

                <!-- trust -->
                <?php if ($model->isTrust()) : ?>
                <a href="<?=Url::to(['user/update-not-trust', 'id' => $model->id]);?>" class="btn btn-sm purple link-action tooltips" data-container="body" data-original-title="Bạn đang tín nhiệm khách hàng này"><i class="fa fa-shield"></i></a>
                <?php else : ?>
                <a href="<?=Url::to(['user/update-trust', 'id' => $model->id]);?>" class="btn btn-sm default link-action tooltips" data-container="body" data-original-title="Khách này chưa được tín nhiệm"><i class="fa fa-shield"></i> </a>
                <?php endif; ?>

                <a href='#assign-saler<?=$model->id;?>' class="btn btn-xs grey-salsa tooltips" data-pjax="0" data-container="body" data-original-title="Gán quyền quản lý cho AM" data-toggle="modal" ><i class="fa fa-headphones" aria-hidden="true"></i></i></a>
                <div class="modal fade" id="assign-saler<?=$model->id;?>" tabindex="-1" role="basic" aria-hidden="true">
                  <div class="modal-dialog">
                    <div class="modal-content">
                      <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                        <h4 class="modal-title">Gửi đơn hàng cho nhân viên AM</h4>
                      </div>
                      <?= Html::beginForm(['user/assign-saler', 'id' => $model->id], 'POST', ['class' => 'assign-form']); ?>
                      <div class="modal-body"> 
                        <div class="row">
                          <div class="col-md-12">
                            <?= kartik\select2\Select2::widget([
                              'name' => 'saler_id',
                              'data' => $salerTeams,
                              'options' => ['placeholder' => 'Select user ...', 'class' => 'form-control'],
                            ]); ?>
                          </div>
                        </div>
                      </div>
                      <div class="modal-footer">
                        <button type="submit" class="btn btn-default" data-toggle="modal"><i class="fa fa-send"></i> Gửi</button>
                        <button type="button" class="btn dark btn-outline" data-dismiss="modal">Close</button>
                      </div>
                      <?= Html::endForm(); ?>
                    </div>
                    <!-- /.modal-content -->
                  </div>
                  <!-- /.modal-dialog -->
                </div>
                <a href="<?=Url::to(['customer-tracker/convert', 'id' => $model->id]);?>" class="btn btn-sm purple tooltips" data-container="body" data-original-title="Kích hoạt customer tracker"><i class="fa fa-certificate"></i></a>
              </td>
            </tr>
            <?php endforeach;?>
            <?php else : ?>
            <tr>
              <td colspan="16"><?=Yii::t('app', 'no_data_found');?></td>
            </tr>
            <?php endif;?>
          </tbody>
        </table>
        </div>
        <?=LinkPager::widget(['pagination' => $pages]);?>
      </div>
    </div>
    <!-- END EXAMPLE TABLE PORTLET-->
  </div>
</div>
<?php foreach ($models as $model) : ?>
<div class="modal fade" id="create-reseller<?=$model->id;?>" tabindex="-1" role="basic" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
        <h4 class="modal-title">Tạo nhà bán lẻ</h4>
      </div>
      <?php $createResellerForm = ActiveForm::begin([
        'action' => Url::to(['reseller/create', 'id' => $model->id]),
        'options' => ['class' => 'create-reseller-form']
      ]);?>
      <div class="modal-body"> 
        <div class="row">
          <div class="col-md-12">
            <?=$createResellerForm->field($createResellerService, 'task_code')->textInput()->label('Mã đề xuất');?>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-default" data-toggle="modal"><i class="fa fa-plus"></i> Tạo</button>
        <button type="button" class="btn dark btn-outline" data-dismiss="modal">Close</button>
      </div>
      <?php ActiveForm::end()?>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<?php endforeach;?>
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

var assignForm = new AjaxFormSubmit({element: '.assign-form'});
assignForm.success = function (data, form) {
  location.reload();
}

var createResellerAjax = new AjaxFormSubmit({element: '.create-reseller-form'});
createResellerAjax.success = function (data, form) {
  setTimeout(() => {  
      location.reload();
  }, 1000);
  toastr.success('Bạn đã tạo nhà bán lẻ thành công'); 
};
createResellerAjax.error = function (errors) {
  toastr.error(errors);
  console.log(errors);
  return false;
}
JS;
$this->registerJs($script);
?>