<?php
use yii\widgets\LinkPager;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use backend\models\User;
use yii\web\JsExpression;
use common\components\helpers\FormatConverter;
?>

<!-- BEGIN PAGE BAR -->
<div class="page-bar">
  <ul class="page-breadcrumb">
    <li>
      <a href="/"><?=Yii::t('app', 'home')?></a>
      <i class="fa fa-circle"></i>
    </li>
    <li>
      <span>Reseller Price</span>
    </li>
  </ul>
</div>
<!-- END PAGE BAR -->
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title">Reseller Price</h1>
<!-- END PAGE TITLE-->
<div class="row">
  <div class="col-md-12">
    <!-- BEGIN EXAMPLE TABLE PORTLET-->
    <div class="portlet light bordered">
      <div class="portlet-title">
        <div class="caption font-dark">
          <i class="icon-settings font-dark"></i>
          <span class="caption-subject bold uppercase"> Reseller Price</span>
        </div>
        <div class="actions">
          <div class="btn-group btn-group-devided">
            <a class="btn green" href="<?=Url::to(['reseller-price/create']);?>}">Thêm mới</a>
          </div>
        </div>
      </div>
      <div class="portlet-body">
        <?php $form = ActiveForm::begin(['method' => 'GET', 'action' => Url::to(['reseller-price/index'])]);?>
        <div class="row margin-bottom-10">
          TODO
        </div>
        <?php ActiveForm::end()?>
        <div class="table-responsive">
          <table class="table table-striped table-bordered table-hover table-checkable hidden" id="price-table">
            <thead>
              <tr>
                <th col-tag="no"> STT </th>
                <th col-tag="reseller_id"> Reseller </th>
                <th col-tag="game_id"> Game </th>
                <th col-tag="price"> Price </th>
                <th col-tag="valid"> Valid </th>
                <th col-tag="action" class="dt-center"> Tác vụ </th>
              </tr>
            </thead>
            <tbody>
                <?php if (!$models) :?>
                <tr><td colspan="6" id="no-data"><?=Yii::t('app', 'no_data_found');?></td></tr>
                <?php endif;?>
                <?php foreach ($models as $key => $model) :?>
                <tr>
                  <td col-tag="no"><?=$key + $pages->offset + 1;?></td>
                  <td col-tag="reseller_id"><?=$model->user->name;?></td>
                  <td col-tag="game_id"><?=$model->game->title;?></td>
                  <td col-tag="price"><?=$model->price;?></td>
                  <td col-tag="valid"><?=FormatConverter::convertToDate(strtotime($model->invalid_at), Yii::$app->params['date_time_format']);?></td>
                  <td col-tag="action">
                  <a href='<?=Url::to(['reseller-price/delete', 'supplier_id' => $model->supplier_id, 'game_id' => $model->game_id]);?>' class="btn btn-xs grey-salsa tooltips" data-pjax="0" data-container="body" data-original-title="Xoá"><i class="fa fa-close"></i></a>
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
