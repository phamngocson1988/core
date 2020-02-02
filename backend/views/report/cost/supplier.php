<?php
use yii\widgets\LinkPager;
?>
<!-- BEGIN PAGE BAR -->
<div class="page-bar">
  <ul class="page-breadcrumb">
    <li>
      <a href="/"><?=Yii::t('app', 'home')?></a>
      <i class="fa fa-circle"></i>
    </li>
    <li>
      <span>Thống kê & báo cáo</span>
      <i class="fa fa-circle"></i>
    </li>
    <li>
      <span>Thống kê chi phí lợi nhuận</span>
      <i class="fa fa-circle"></i>
    </li>
    <li>
      <span>Theo nhà cung cấp</span>
    </li>
  </ul>
</div>
<!-- END PAGE BAR -->
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title">Theo nhà cung cấp</h1>
<!-- END PAGE TITLE-->
<div class="row">
  <div class="col-md-12">
    <!-- BEGIN EXAMPLE TABLE PORTLET-->
    <div class="portlet light bordered">
      <div class="portlet-title">
        <div class="caption font-dark">
          <i class="icon-settings font-dark"></i>
          <span class="caption-subject bold uppercase"> Thống kê theo nhà cung cấp</span>
        </div>
      </div>
      <div class="portlet-body">
        <div class="table-responsive">
          <table class="table table-striped table-bordered table-hover table-checkable">
            <thead>
              <tr>
                <th> ID </th>
                <th> Nhà cung cấp </th>
                <th> Số dư khả dụng </th>
              </tr>
            </thead>
            <tbody>
                <?php if (!$models) :?>
                <tr><td colspan="3"><?=Yii::t('app', 'no_data_found');?></td></tr>
                <?php endif;?>
                <?php foreach ($models as $no => $model) :?>
                <?php $user = $model->user;?>
                <tr>
                  <td><?=$model->user_id;?></td>
                  <td><?=$user->name;?></td>
                  <td><?=number_format($model->walletTotal());?></td>
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
