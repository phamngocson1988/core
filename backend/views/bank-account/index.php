<?php
use yii\helpers\Url;
use yii\widgets\LinkPager;
use yii\widgets\ActiveForm;
use common\components\helpers\CommonHelper;
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
              <th class="dt-center"> <?=Yii::t('app', 'actions');?> </th>
            </tr>
          </thead>
          <tbody>
              <?php if (!$models) :?>
              <tr><td colspan="6"><?=Yii::t('app', 'no_data_found');?></td></tr>
              <?php endif;?>
              <?php foreach ($models as $model) :?>
              <tr>
                <td><?=$model->bank->code;?></td>
                <td><?=$model->bank->name;?></td>
                <td><?=CommonHelper::getCountry($model->bank->country);?></td>
                <td><?=$model->account_name;?></td>
                <td><?=$model->account_number;?></td>
                <td>
                  <a class="btn btn-sm grey-salsa tooltips" href="<?=Url::to(['bank-account/edit', 'id' => $model->id]);?>" data-container="body" data-original-title="Chỉnh sửa"><i class="fa fa-pencil"></i> Chỉnh sửa</a>
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