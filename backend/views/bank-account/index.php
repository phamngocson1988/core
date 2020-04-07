<?php
use yii\helpers\Url;
use yii\widgets\LinkPager;
use yii\widgets\ActiveForm;
use common\components\helpers\CommonHelper;

$canManageAccount = Yii::$app->user->can('manager');
$numColumn = 5;
if ($canManageAccount) $numColumn++;
?>
<!-- BEGIN PAGE BAR -->
<div class="page-bar">
  <ul class="page-breadcrumb">
    <li>
      <a href="/"><?=Yii::t('app', 'home')?></a>
      <i class="fa fa-circle"></i>
    </li>
    <li>
      <a href="<?=Url::to(['bank/index']);?>">Danh sách ngân hàng</a>
      <i class="fa fa-circle"></i>
    </li>
    <li>
      <span>Quản lý tài khoản</span>
    </li>
  </ul>
</div>
<!-- END PAGE BAR -->
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title">Quản lý tài khoản</h1>
<!-- END PAGE TITLE-->
<div class="row">
  <div class="col-md-12">
    <!-- BEGIN EXAMPLE TABLE PORTLET-->
    <div class="portlet light">
      <div class="portlet-title">
        <div class="actions">
          <div class="btn-group btn-group-devided">
            <a class="btn green" href="<?=Url::to(['bank-account/create']);?>"><?=Yii::t('app', 'add_new');?></a>
          </div>
        </div>
      </div>
      <div class="portlet-body">
        <div class="row margin-bottom-10">
          <?php $form = ActiveForm::begin(['method' => 'GET', 'action' => ['bank-account/index']]);?>
            <?=$form->field($search, 'bank_id', [
              'options' => ['class' => 'form-group col-md-4 col-lg-3'],
              'inputOptions' => ['class' => 'form-control', 'name' => 'bank_id']
            ])->dropDownList($search->fetchBank(), ['prompt' => 'Chọn ngân hàng'])->label('Ngân hàng');?>
            <?=$form->field($search, 'country', [
              'options' => ['class' => 'form-group col-md-4 col-lg-3'],
              'inputOptions' => ['class' => 'form-control', 'name' => 'country']
            ])->dropDownList($search->fetchCountry(), ['prompt' => 'Chọn quốc gia'])->label('Quốc gia');?>
            <?=$form->field($search, 'account_name', [
              'options' => ['class' => 'form-group col-md-4 col-lg-3'],
              'inputOptions' => ['class' => 'form-control', 'name' => 'account_name']
            ])->textInput()->label('Tên tài khoản');?>
            <?=$form->field($search, 'account_number', [
              'options' => ['class' => 'form-group col-md-4 col-lg-3'],
              'inputOptions' => ['class' => 'form-control', 'name' => 'account_number']
            ])->textInput()->label('Số tài khoản');?>
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
              <th> Mã ngân hàng </th>
              <th> Tên ngân hàng </th>
              <th> Quốc gia </th>
              <th> Tên tài khoản </th>
              <th> Số tài khoản </th>
              <th class="dt-center <?=($canManageAccount) ? '' : 'hide';?>"> <?=Yii::t('app', 'actions');?> </th>
            </tr>
          </thead>
          <tbody>
              <?php if (!$models) :?>
              <tr><td colspan="<?=$numColumn;?>"><?=Yii::t('app', 'no_data_found');?></td></tr>
              <?php endif;?>
              <?php foreach ($models as $model) :?>
              <tr>
                <td class="center"><?=$model->bank->code;?></td>
                <td class="center"><?=$model->bank->name;?></td>
                <td class="center"><?=CommonHelper::getCountry($model->bank->country);?></td>
                <td class="center"><?=$model->account_name;?></td>
                <td class="center"><?=$model->account_number;?></td>
                <td class="center <?=($canManageAccount) ? '' : 'hide';?>">
                  <a class="btn btn-sm green tooltips" href="<?=Url::to(['bank-account/edit', 'id' => $model->id]);?>" data-container="body" data-original-title="Chỉnh sửa"><i class="fa fa-pencil"></i> Chỉnh sửa</a>

                  <a href='<?=Url::to(['bank-account/assign-role', 'id' => $model->id]);?>' data-target="#assign-role" class="btn btn-sm blue tooltips" data-pjax="0" data-container="body" data-original-title="Gán quyền quản lý" data-toggle="modal" ><i class="fa fa-rocket"></i> Gán quyền quản lý</a>
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

<div class="modal fade" id="assign-role" tabindex="-1" role="basic" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>

<?php
$script = <<< JS
$(document).on('submit', 'body #assign-role-form', function(e) {
  e.preventDefault();
  e.stopImmediatePropagation();
  var form = $(this);
  form.unbind('submit');
  $.ajax({
    url: form.attr('action'),
    type: form.attr('method'),
    dataType : 'json',
    data: form.serialize(),
    success: function (result, textStatus, jqXHR) {
      if (!result.status)
       alert(result.errors);
      else 
        location.reload();
    },
  });
  return false;
});
JS;
$this->registerJs($script);
?>