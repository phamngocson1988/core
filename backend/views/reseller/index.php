<?php
use yii\widgets\LinkPager;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use backend\models\User;
use yii\web\JsExpression;

// saler team
$adminTeamIds = Yii::$app->authManager->getUserIdsByRole('admin');
$salerTeamIds = Yii::$app->authManager->getUserIdsByRole('saler');
$salerTeamManagerIds = Yii::$app->authManager->getUserIdsByRole('saler_manager');
$salerTeamIds = array_merge($salerTeamIds, $salerTeamManagerIds, $adminTeamIds);
$salerTeamIds = array_unique($salerTeamIds);
$salerTeamObjects = User::findAll($salerTeamIds);
$salerTeams = ArrayHelper::map($salerTeamObjects, 'id', 'email');
?>

<!-- BEGIN PAGE BAR -->
<div class="page-bar">
  <ul class="page-breadcrumb">
    <li>
      <a href="/"><?=Yii::t('app', 'home')?></a>
      <i class="fa fa-circle"></i>
    </li>
    <li>
      <span>Reseller</span>
    </li>
  </ul>
</div>
<!-- END PAGE BAR -->
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title">Reseller</h1>
<!-- END PAGE TITLE-->
<div class="row">
  <div class="col-md-12">
    <!-- BEGIN EXAMPLE TABLE PORTLET-->
    <div class="portlet light bordered">
      <div class="portlet-title">
        <div class="caption font-dark">
          <i class="icon-settings font-dark"></i>
          <span class="caption-subject bold uppercase"> Reseller</span>
        </div>
        <div class="actions">
        </div>
      </div>
      <div class="portlet-body">
        <?php $form = ActiveForm::begin(['method' => 'GET', 'action' => Url::to(['reseller/index'])]);?>
        <div class="row margin-bottom-10">
          <?php $customer = $search->getCustomer();?>
            <?=$form->field($search, 'user_id', [
              'options' => ['class' => 'form-group col-md-4 col-lg-3'],
            ])->widget(kartik\select2\Select2::classname(), [
              'initValueText' => ($search->user_id) ? sprintf("%s - %s", $customer->username, $customer->email) : '',
              'options' => ['class' => 'form-control', 'name' => 'user_id'],
              'pluginOptions' => [
                'placeholder' => 'Tìm reseller',
                'allowClear' => true,
                'minimumInputLength' => 3,
                'ajax' => [
                    'url' => Url::to(['user/suggestion']),
                    'dataType' => 'json',
                    'processResults' => new JsExpression('function (data) {return {results: data.data.items};}')
                ]
              ]
          ])->label('Tìm reseller')?>

          <?=$form->field($search, 'manager_id', [
            'options' => ['class' => 'form-group col-md-4 col-lg-3'],
            'inputOptions' => ['class' => 'form-control', 'name' => 'manager_id']
          ])->dropdownList($salerTeams, ['prompt' => 'Chọn quản lý'])->label('Người quản lý');?>

          <?=$form->field($search, 'phone', [
            'options' => ['class' => 'form-group col-md-4 col-lg-3'],
            'inputOptions' => ['class' => 'form-control', 'name' => 'phone']
          ])->textInput()->label('Điện thoại');?>

          <div class="form-group col-md-4 col-lg-3">
            <button type="submit" class="btn btn-success table-group-action-submit" style="margin-top: 25px;">
              <i class="fa fa-check"></i> <?=Yii::t('app', 'search')?>
            </button>
          </div>
        </div>
        <?php ActiveForm::end()?>
        <div class="table-responsive">
          <table class="table table-striped table-bordered table-hover table-checkable hidden" id="order-table">
            <thead>
              <tr>
                <th col-tag="id"> STT </th>
                <th col-tag="name"> Tên </th>
                <th col-tag="username"> Tên đăng nhập </th>
                <th col-tag="email"> Email </th>
                <th col-tag="phone"> Phone </th>
                <th col-tag="level"> Level </th>
                <th col-tag="manager"> Quản lý </th>
                <th col-tag="action" class="dt-center"> Tác vụ </th>
              </tr>
            </thead>
            <tbody>
                <?php if (!$models) :?>
                <tr><td colspan="8" id="no-data"><?=Yii::t('app', 'no_data_found');?></td></tr>
                <?php endif;?>
                <?php foreach ($models as $key => $model) :?>
                <tr>
                  <td col-tag="id"><?=$key + $pages->offset + 1;?></td>
                  <td col-tag="name"><?=$model->user->name;?></td>
                  <td col-tag="username"><?=$model->user->username;?></td>
                  <td col-tag="email"><?=$model->user->email;?></td>
                  <td col-tag="phone"><?=$model->user->phone;?></td>
                  <td col-tag="level"><?=$model->user->getResellerLabel();?></td>
                  <td col-tag="manager"><?=($model->manager) ? $model->manager->name : '';?></td>
                  <td col-tag="action">
                    <?php if (Yii::$app->user->can('admin')) : ?>
                    <a class="btn btn-sm blue tooltips" target="_blank" href="<?=Url::to(['user/edit', 'id' => $model->user_id]);?>" data-container="body" data-original-title="Chỉnh sửa"><i class="fa fa-pencil"></i></a>
                    <?php endif;?>

                    <a href="<?=Url::to(['reseller/delete', 'id' => $model->user_id]);?>" class="btn btn-sm purple link-action tooltips action-link" data-container="body" data-original-title="Bỏ tư cách nhà bán lẻ"><i class="fa fa-times"></i></a>
                    <?php if ($model->user->reseller_level != User::RESELLER_LEVEL_3) : ?>
                    <a href="<?=Url::to(['reseller/upgrade', 'id' => $model->user_id]);?>" class="btn btn-sm red link-action tooltips action-link" data-container="body" data-original-title="Nâng cấp nhà bán lẻ này"><i class="fa fa-arrow-up"></i></a>
                    <?php endif; ?>
                    <?php if ($model->user->reseller_level != User::RESELLER_LEVEL_1) : ?>
                    <a href="<?=Url::to(['reseller/downgrade', 'id' => $model->user_id]);?>" class="btn btn-sm default link-action tooltips action-link" data-container="body" data-original-title="Giảm cấp nhà bán lẻ này"><i class="fa fa-arrow-down"></i></a>
                    <?php endif; ?>

                    <a href='#assign<?=$model->user_id;?>' class="btn btn-sm default tooltips" data-container="body" data-original-title="Chọn nhân viên quản lý"data-toggle="modal" ><i class="fa fa-exchange"></i></a>
                    <div class="modal fade" id="assign<?=$model->user_id;?>" tabindex="-1" role="basic" aria-hidden="true">
                      <div class="modal-dialog">
                        <div class="modal-content">
                          <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                            <h4 class="modal-title">Chọn nhân viên saler</h4>
                          </div>
                          <?= Html::beginForm(['reseller/assign', 'id' => $model->user_id], 'POST', ['class' => 'assign-form']); ?>
                          <div class="modal-body"> 
                            <div class="row">
                              <div class="col-md-12">
                                <?= kartik\select2\Select2::widget([
                                  'name' => 'manager_id',
                                  'data' => $salerTeams,
                                  'options' => ['placeholder' => 'Select user ...', 'class' => 'form-control'],
                                ]); ?>
                              </div>
                            </div>
                          </div>
                          <div class="modal-footer">
                            <button type="submit" class="btn btn-default" data-toggle="modal"><i class="fa fa-plus"></i> Chọn</button>
                            <button type="button" class="btn dark btn-outline" data-dismiss="modal">Close</button>
                          </div>
                          <?= Html::endForm(); ?>
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
        <?=LinkPager::widget(['pagination' => $pages])?>
      </div>
    </div>
    <!-- END EXAMPLE TABLE PORTLET-->
  </div>
</div>
<?php
$hiddenColumns = [];
if (Yii::$app->user->isRole('admin')) $hiddenColumns = [];
elseif (Yii::$app->user->isRole('saler')) array_push($hiddenColumns, 'manager', 'action');
$hiddenColumnString = implode(',', $hiddenColumns);

$script = <<< JS
var hiddenColumns = '$hiddenColumnString';
initTable('#order-table', '#no-data', hiddenColumns);

// delete
$('.action-link').ajax_action({
  method: 'POST',
  confirm: true,
  confirm_text: 'Bạn có muốn thực hiện hành động này?',
  callback: function(data) {
    location.reload();
  },
});

var sendForm = new AjaxFormSubmit({element: '.assign-form'});
sendForm.success = function (data, form) {
  location.reload();
}
JS;
$this->registerJs($script);
?>