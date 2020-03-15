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
      <a href="<?=Url::to(['user/index']);?>">Quản lý nhân viên</a>
      <i class="fa fa-circle"></i>
    </li>
    <li>
      <span>Quản lý đăng nhập</span>
    </li>
  </ul>
</div>
<!-- END PAGE BAR -->
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title">Quản lý đăng nhập</h1>
<!-- END PAGE TITLE-->
<div class="row">
  <div class="col-md-12">
    <!-- BEGIN EXAMPLE TABLE PORTLET-->
    <div class="portlet light">
      <div class="portlet-body">
        <div class="row margin-bottom-10">
          <?php $form = ActiveForm::begin(['method' => 'GET', 'action' => ['user/login']]);?>
            <?=$form->field($search, 'user_id', [
              'options' => ['class' => 'form-group col-md-4 col-lg-3'],
            ])->widget(kartik\select2\Select2::classname(), [
              'initValueText' => ($search->user_id) ? sprintf("%s - %s", $search->getUser()->username, $search->getUser()->email) : '',
              'options' => ['class' => 'form-control', 'name' => 'user_id'],
              'pluginOptions' => [
                'placeholder' => 'Chọn nhân viên',
                'allowClear' => true,
                'minimumInputLength' => 3,
                'ajax' => [
                    'url' => Url::to(['user/suggestion']),
                    'dataType' => 'json',
                    'processResults' => new JsExpression('function (data) {return {results: data.data.items};}')
                ]
              ]
            ])->label('Nhân viên');?>

            <?=$form->field($search, 'date_from', [
              'options' => ['class' => 'form-group col-md-4 col-lg-3'],
              'inputOptions' => ['class' => 'form-control', 'name' => 'date_from']
            ])->widget(DatePicker::className(), [
                'clientOptions' => [
                    'autoclose' => true,
                    'format' => 'yyyy-mm-dd'
                ]
            ])->label('Tìm từ ngày');?>
            <?=$form->field($search, 'date_to', [
              'options' => ['class' => 'form-group col-md-4 col-lg-3'],
              'inputOptions' => ['class' => 'form-control', 'name' => 'date_to']
            ])->widget(DatePicker::className(), [
                'clientOptions' => [
                    'autoclose' => true,
                    'format' => 'yyyy-mm-dd'
                ]
            ])->label('Tìm đến ngày');?>

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
              <th> Ngày đăng nhập </th>
              <th> Nhân viên </th>
              <th> Quyền hạn khi đăng nhập </th>
              <th> IP </th>
            </tr>
          </thead>
          <tbody>
            <?php if ($models) : ?>
            <?php foreach ($models as $model) : ?>
            <tr>
              <td><?=$model->created_at;?></td>
              <td><?=sprintf("%s (MS: %s)", $model->user->name, $model->user_id);?></td>
              <td><?=$model->role;?></td>
              <td><?=$model->ip;?></td>
            </tr>
            <?php endforeach;?>
            <?php else : ?>
            <tr>
              <td colspan="3"><?=Yii::t('app', 'no_data_found');?></td>
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