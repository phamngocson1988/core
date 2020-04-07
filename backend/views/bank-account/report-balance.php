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
      <span>Thống kê số dư tài khoản</span>
    </li>
  </ul>
</div>
<!-- END PAGE BAR -->
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title">Thống kê số dư tài khoản</h1>
<!-- END PAGE TITLE-->
<div class="row">
  <div class="col-md-12">
    <!-- BEGIN EXAMPLE TABLE PORTLET-->
    <div class="portlet light">
      <div class="table-responsive">
        <table class="table table-striped table-bordered table-hover table-checkable">
          <thead>
            <tr>
              <th> Mã ngân hàng </th>
              <th> Tên ngân hàng </th>
              <th> Quốc gia </th>
              <th> Tên tài khoản </th>
              <th> Số tài khoản </th>
              <th> Số dư hiện tại </th>
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
                <td><?=$model->bankAccount->account_name;?></td>
                <td><?=$model->bankAccount->account_number;?></td>
                <td><?=number_format($model->amount);?></td>
              </tr>
              <?php endforeach;?>
          </tbody>
        </table>
      </div>
    </div>
    <!-- END EXAMPLE TABLE PORTLET-->
  </div>
</div>