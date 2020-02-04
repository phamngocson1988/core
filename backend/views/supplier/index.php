<?php
use yii\widgets\LinkPager;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use backend\models\User;
use yii\web\JsExpression;
?>

<!-- BEGIN PAGE BAR -->
<div class="page-bar">
  <ul class="page-breadcrumb">
    <li>
      <a href="/"><?=Yii::t('app', 'home')?></a>
      <i class="fa fa-circle"></i>
    </li>
    <li>
      <span>Nhà cung cấp</span>
    </li>
  </ul>
</div>
<!-- END PAGE BAR -->
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title">Nhà cung cấp</h1>
<!-- END PAGE TITLE-->
<div class="row">
  <div class="col-md-12">
    <!-- BEGIN EXAMPLE TABLE PORTLET-->
    <div class="portlet light bordered">
      <div class="portlet-title">
        <div class="caption font-dark">
          <i class="icon-settings font-dark"></i>
          <span class="caption-subject bold uppercase"> Nhà cung cấp</span>
        </div>
        <div class="actions">
        </div>
      </div>
      <div class="portlet-body">
        <?php $form = ActiveForm::begin(['method' => 'GET', 'action' => Url::to(['supplier/index'])]);?>
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
          ])->label('Tìm nhà cung cấp')?>

          <?=$form->field($search, 'status', [
            'options' => ['class' => 'form-group col-md-4 col-lg-3'],
            'inputOptions' => ['class' => 'form-control', 'name' => 'status']
          ])->dropdownList([], ['prompt' => 'Chọn trạng thái'])->label('Chọn trạng thái');?>

          <div class="form-group col-md-4 col-lg-3">
            <button type="submit" class="btn btn-success table-group-action-submit" style="margin-top: 25px;">
              <i class="fa fa-check"></i> <?=Yii::t('app', 'search')?>
            </button>
          </div>
        </div>
        <?php ActiveForm::end()?>
        <table class="table table-striped table-bordered table-hover table-checkable">
          <thead>
            <tr>
              <th> STT </th>
              <th> Tên </th>
              <th> Tên đăng nhập </th>
              <th> Email </th>
              <th> Phone </th>
              <th class="dt-center"> Tác vụ </th>
            </tr>
          </thead>
          <tbody>
              <?php if (!$models) :?>
              <tr><td colspan="8"><?=Yii::t('app', 'no_data_found');?></td></tr>
              <?php endif;?>
              <?php foreach ($models as $key => $model) :?>
              <tr>
                <td><?=$key + $pages->offset + 1;?></td>
                <td style="vertical-align: middle;"><?=$model->user->name;?></td>
                <td style="vertical-align: middle;"><?=$model->user->username;?></td>
                <td style="vertical-align: middle;"><?=$model->user->email;?></td>
                <td style="vertical-align: middle;"><?=$model->user->phone;?></td>
                <td style="vertical-align: middle;">
                
                  <?php if (Yii::$app->user->can('accounting')) : ?>
                  <a href="<?=Url::to(['supplier/remove', 'id' => $model->user_id]);?>" class="btn btn-sm red link-action tooltips action-link" data-container="body" data-original-title="Bỏ tư cách nhà cung cấp"><i class="fa fa-trash"></i></a>

                  <?php if ($model->isEnabled()) : ?>
                  <a href="<?=Url::to(['supplier/disable', 'id' => $model->user_id]);?>" class="btn btn-sm green link-action tooltips action-link" data-container="body" data-original-title="Tạm ngưng nhà cung cấp"><i class="fa fa-power-off"></i></a>
                  <?php else :?>
                  <a href="<?=Url::to(['supplier/enable', 'id' => $model->user_id]);?>" class="btn btn-sm default link-action tooltips action-link" data-container="body" data-original-title="Kích hoạt nhà cung cấp"><i class="fa fa-power-off"></i></a>
                  <?php endif;?>
                  <?php endif;?>

                  <a href="<?=Url::to(['supplier/game', 'id' => $model->user_id]);?>" class="btn btn-sm blue tooltips" data-container="body" data-original-title="Danh sách game"><i class="fa fa-list"></i></a>
                </td>
              </tr>
              <?php endforeach;?>
          </tbody>
        </table>
        <?=LinkPager::widget(['pagination' => $pages])?>
      </div>
    </div>
    <!-- END EXAMPLE TABLE PORTLET-->
  </div>
</div>
<?php
$script = <<< JS

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