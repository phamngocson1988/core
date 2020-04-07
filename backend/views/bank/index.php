<?php
use yii\helpers\Url;
use yii\widgets\LinkPager;
use yii\widgets\ActiveForm;
use common\components\helpers\CommonHelper;
$canManageBank = Yii::$app->user->can('manager');
$numColumn = 5;
if ($canManageBank) $numColumn++;
?>
<!-- BEGIN PAGE BAR -->
<div class="page-bar">
  <ul class="page-breadcrumb">
    <li>
      <a href="/"><?=Yii::t('app', 'home')?></a>
      <i class="fa fa-circle"></i>
    </li>
    <li>
      <span>Quản lý ngân hàng</span>
    </li>
  </ul>
</div>
<!-- END PAGE BAR -->
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title">Quản lý ngân hàng</h1>
<!-- END PAGE TITLE-->
<div class="row">
  <div class="col-md-12">
    <!-- BEGIN EXAMPLE TABLE PORTLET-->
    <div class="portlet light">
      <div class="portlet-title">
        <div class="actions">
          <div class="btn-group btn-group-devided">
            <a class="btn green" href="<?=Url::to(['bank/create']);?>"><?=Yii::t('app', 'add_new');?></a>
          </div>
        </div>
      </div>
      <div class="portlet-body">
        <div class="row margin-bottom-10">
          <?php $form = ActiveForm::begin(['method' => 'GET', 'action' => ['bank/index']]);?>
          <?=$form->field($search, 'name', [
              'options' => ['class' => 'form-group col-md-4 col-lg-3'],
              'inputOptions' => ['class' => 'form-control', 'name' => 'name']
            ])->textInput()->label('Tên ngân hàng');?>
            <?=$form->field($search, 'code', [
              'options' => ['class' => 'form-group col-md-4 col-lg-3'],
              'inputOptions' => ['class' => 'form-control', 'name' => 'code']
            ])->textInput()->label('Mã ngân hàng');?>
            <?=$form->field($search, 'country', [
              'options' => ['class' => 'form-group col-md-4 col-lg-3'],
              'inputOptions' => ['class' => 'form-control', 'name' => 'country']
            ])->dropDownList($search->fetchCountry(), ['prompt' => 'Chọn quốc gia'])->label('Quốc gia');?>
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
              <th> Tiền tệ </th>
              <th> Phí chuyển khoản </th>
              <th class="dt-center <?=($canManageBank) ? '' : 'hide';?>"> <?=Yii::t('app', 'actions');?> </th>
            </tr>
          </thead>
          <tbody>
              <?php if (!$models) :?>
              <tr><td colspan="<?=$numColumn;?>"><?=Yii::t('app', 'no_data_found');?></td></tr>
              <?php endif;?>
              <?php foreach ($models as $model) :?>
              <tr>
                <td class="center"><?=$model->code;?></td>
                <td class="center"><?=$model->name;?></td>
                <td class="center"><?=CommonHelper::getCountry($model->country);?></td>
                <td class="center"><?=$model->currency;?></td>
                <td class="center"><?=number_format($model->transfer_cost);?></td>
                <td class="center <?=($canManageBank) ? '' : 'hide';?>">
                  <a class="btn btn-sm grey-salsa tooltips" href="<?=Url::to(['bank/edit', 'id' => $model->id]);?>" data-container="body" data-original-title="Chỉnh sửa"><i class="fa fa-pencil"></i> Chỉnh sửa</a>
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