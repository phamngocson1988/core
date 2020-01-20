<?php
use yii\widgets\LinkPager;
use yii\widgets\Pjax;
use yii\helpers\Url;
use backend\models\Order;
use backend\components\datepicker\DatePicker;
use yii\widgets\ActiveForm;
use backend\behaviors\OrderSupplierBehavior;
$this->registerCssFile('vendor/assets/global/plugins/bootstrap-select/css/bootstrap-select.css', ['depends' => ['\yii\bootstrap\BootstrapAsset']]);
$this->registerJsFile('vendor/assets/global/plugins/bootstrap-select/js/bootstrap-select.min.js', ['depends' => '\backend\assets\AppAsset']);
$this->registerJsFile('vendor/assets/pages/scripts/components-bootstrap-select.min.js', ['depends' => '\backend\assets\AppAsset']);
$user = Yii::$app->user;
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
      <span>Đơn hàng có feedback</span>
    </li>
  </ul>
</div>
<!-- END PAGE BAR -->
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title">Đơn hàng có feedback</h1>
<!-- END PAGE TITLE-->
<div class="row">
  <div class="col-md-12">
    <!-- BEGIN EXAMPLE TABLE PORTLET-->
    <div class="portlet light bordered">
      <div class="portlet-title">
        <div class="caption font-dark">
          <i class="icon-settings font-dark"></i>
          <span class="caption-subject bold uppercase"> Đơn hàng có feedback</span>
        </div>
        <div class="actions">
          <a role="button" class="btn btn-warning" href="<?=Url::current(['mode'=>'export']);?>"><i class="fa fa-file-excel-o"></i> Export</a>
        </div>
      </div>
      <div class="portlet-body">
        <?php $form = ActiveForm::begin(['method' => 'GET', 'action' => ['order/feedback-order']]);?>
        <div class="row margin-bottom-10">
            <?=$form->field($search, 'rating', [
              'options' => ['class' => 'form-group col-md-4 col-lg-3'],
              'inputOptions' => ['multiple' => 'true', 'class' => 'bs-select form-control', 'name' => 'rating[]']
            ])->dropDownList(['1' => 'Like', '-1' => 'Dislike'])->label('Loại feedback');?>

            <?=$form->field($search, 'created_at_start', [
              'options' => ['class' => 'form-group col-md-4 col-lg-3'],
              'inputOptions' => ['class' => 'form-control', 'name' => 'created_at_start', 'id' => 'created_at_start']
            ])->widget(DatePicker::className(), [
                'clientOptions' => [
                  'autoclose' => true,
                  'format' => 'yyyy-mm-dd',
                ],
            ])->label('Ngày tạo từ');?>

            <?=$form->field($search, 'created_at_end', [
              'options' => ['class' => 'form-group col-md-4 col-lg-3'],
              'inputOptions' => ['class' => 'form-control', 'name' => 'created_at_end', 'id' => 'created_at_end']
            ])->widget(DatePicker::className(), [
                'clientOptions' => [
                  'autoclose' => true,
                  'format' => 'yyyy-mm-dd',
                ],
            ])->label('Ngày tạo đến');?>

            <div class="form-group col-md-4 col-lg-3">
              <button type="submit" class="btn btn-success table-group-action-submit" style="margin-top: 25px;">
                <i class="fa fa-check"></i> <?=Yii::t('app', 'search')?>
              </button>
            </div>
        </div>
        <?php ActiveForm::end()?>
        <?php Pjax::begin(); ?>
        <div class="table-responsive">
          <table class="table table-bordered">
            <thead>
              <tr>
                <th> Mã đơn hàng </th>
                <th> Ngày tạo </th>
                <th class="hidden-xs"> Người bán hàng </th>
                <th class="hidden-xs"> Nhân viên đơn hàng </th>
                <th <?=$user->can('orderteam') ? 'class="hide"' : '';?>> Nhà cung cấp </th>
                <th> Khách hàng</th>
                <th class="hidden-xs"> Số điện thoại</th>
                <th> Feedback </th>
                <th> Comment </th>
                <th> Trạng thái </th>
              </tr>
            </thead>
            <tbody>
                <?php if (!$models) :?>
                <tr><td colspan="9"><?=Yii::t('app', 'no_data_found');?></td></tr>
                <?php endif;?>
                <?php foreach ($models as $no => $model) :?>
                <?php $model->attachBehavior('supplier', new OrderSupplierBehavior);?>
                <tr>
                  <td style="vertical-align: middle; max-width:none"><a href='<?=Url::to(['order/view', 'id' => $model->id, 'ref' => $ref]);?>'>#<?=$model->id;?></a></td>
                  <td><?=$model->created_at;?></td>
                  <td class="hidden-xs"><?=($model->saler) ? $model->saler->name : '';?></td>
                  <td class="hidden-xs"><?=($model->orderteam) ? $model->orderteam->name : '';?></td>
                  <td <?=$user->can('orderteam') ? 'class="hide"' : '';?>>
                    <?php
                    if ($model->supplier) {
                      echo $model->supplier->user->name;
                    } 
                    ?>
                  </td>
                  <td><?=$model->customer_email;?></td>
                  <td class="hidden-xs"><?=$model->customer_phone;?></td>
                  <td>
                    <?php if ($model->rating == 1) : ?>
                      <span class="label label-primary">Like</span>
                    <?php elseif ($model->rating == -1) :?>
                      <span class="label label-default">Dislike</span>
                    <?php endif;?>
                  </td>
                  <td><?=$model->comment_rating;?></td>
                  <td><?=$model->getStatusLabel();?></td>
                </tr>
                <?php endforeach;?>
            </tbody>
          </table>
        </div>
        <?=LinkPager::widget(['pagination' => $pages])?>
        <?php Pjax::end(); ?>
      </div>
    </div>
    <!-- END EXAMPLE TABLE PORTLET-->
  </div>
</div>