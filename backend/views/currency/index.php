<?php
use yii\widgets\LinkPager;
use yii\widgets\Pjax;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\web\JsExpression;
use common\components\helpers\StringHelper;
?>
<!-- BEGIN PAGE BAR -->
<div class="page-bar">
  <ul class="page-breadcrumb">
    <li>
      <a href="/"><?=Yii::t('app', 'home')?></a>
      <i class="fa fa-circle"></i>
    </li>
    <li>
      <span>Quản lý tiền tệ</span>
    </li>
  </ul>
</div>
<!-- END PAGE BAR -->
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title">Quản lý tiền tệ</h1>
<!-- END PAGE TITLE-->
<div class="row">
  <div class="col-md-12">
    <!-- BEGIN EXAMPLE TABLE PORTLET-->
    <div class="portlet light bordered">
      <div class="portlet-title">
        <div class="caption font-dark">
          <i class="icon-settings font-dark"></i>
          <span class="caption-subject bold uppercase"> Quản lý tiền tệ</span>
        </div>
        <div class="actions">
          <div class="btn-group btn-group-devided">
            <a class="btn green" href="<?=Url::to(['currency/create'])?>"><?=Yii::t('app', 'add_new')?></a>
          </div>
        </div>
      </div>
      <div class="portlet-body">
        <div class="row margin-bottom-10">
        </div>
        <div class="table-responsive">
          <table class="table table-striped table-bordered table-hover table-checkable" id="currency-table">
            <thead>
              <tr>
                <th col-tag="code"> Mã tiền tệ </th>
                <th col-tag="name"> Tên tiền tệ </th>
                <th col-tag="exchange_rate"> Tỉ giá với Kcoin </th>
                <th col-tag="status"> Trạng thái </th>
                <th col-tag="action" class="dt-center"> <?=Yii::t('app', 'actions');?> </th>
              </tr>
            </thead>
            <tbody>
                <?php if (!$models) :?>
                <tr><td colspan="5" id="no-data"><?=Yii::t('app', 'no_data_found');?></td></tr>
                <?php endif;?>
                <?php foreach ($models as $model) :?>
                <tr>
                  <td col-tag="code"><a href='<?=Url::to(['currency/edit', 'code' => $model->code]);?>'><?=$model->code;?></a></td>
                  <td col-tag="name"><?=$model->name;?></td>
                  <td col-tag="exchange_rate"><?=StringHelper::numberFormat($model->exchange_rate, 2);?></td>
                  <td col-tag="status">
                    <?php if ($model->isDisactive()) :?>
                    <span class="label label-default">Tạm ngưng</span>
                    <?php endif;?>
                    <?php if ($model->isActive()) :?>
                    <span class="label label-success">Hoạt động</span>
                    <?php endif;?>
                  </td>
                  <td col-tag="action">
                    <?php if (!$model->isFix()) : ?>
                    <a href='<?=Url::to(['currency/edit', 'code' => $model->code]);?>' class="btn btn-xs grey-salsa tooltips" data-pjax="0" data-container="body" data-original-title="Chỉnh sửa"><i class="fa fa-pencil"></i></a>
                    <?php endif;?>
                  </td>
                </tr>
                <?php endforeach;?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
    <!-- END EXAMPLE TABLE PORTLET-->
  </div>
</div>