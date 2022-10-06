<?php
use yii\widgets\LinkPager;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use backend\models\User;
use yii\web\JsExpression;
use common\components\helpers\FormatConverter;
use common\components\helpers\StringHelper;

$now = strtotime('now');
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
            <a class="btn green" href="<?=Url::to(['reseller-price/create']);?>">Thêm mới</a>
          </div>
        </div>
      </div>
      <div class="portlet-body">
        <?php $form = ActiveForm::begin(['method' => 'GET', 'action' => Url::to(['reseller-price/index'])]);?>
        <div class="row margin-bottom-10">
          <?=$form->field($search, 'reseller_id', [
            'options' => ['class' => 'form-group col-md-6 col-lg-4'],
            'inputOptions' => ['class' => 'form-control', 'name' => 'reseller_id']
          ])->widget(kartik\select2\Select2::classname(), [
            'data' => $search->fetchResellers(),
            'options' => ['class' => 'form-control', 'prompt' => 'Tìm theo reseller'],
            'pluginOptions' => [
              'allowClear' => true
            ],
          ])->label('Reseller')?>

          <?=$form->field($search, 'game_id', [
            'options' => ['class' => 'form-group col-md-6 col-lg-4'],
            'inputOptions' => ['class' => 'form-control', 'name' => 'game_id']
          ])->widget(kartik\select2\Select2::classname(), [
            'data' => $search->fetchGames(),
            'options' => ['class' => 'form-control', 'prompt' => 'Tìm theo game'],
            'pluginOptions' => [
              'allowClear' => true
            ],
          ])->label('Tên game')?>

          <div class="form-group col-md-4 col-lg-3">
            <button type="submit" class="btn btn-success table-group-action-submit" style="margin-top: 25px;">
              <i class="fa fa-check"></i> <?=Yii::t('app', 'search')?>
            </button>
          </div>
        </div>
        <?php ActiveForm::end()?>
        <div class="table-responsive">
          <table class="table table-striped table-bordered table-hover table-checkable" id="price-table">
            <thead>
              <tr>
                <th col-tag="no"> STT </th>
                <th col-tag="reseller_id"> Reseller </th>
                <th col-tag="game_id"> Game </th>
                <th col-tag="game_supplier_price"> Giá bán chuẩn </th>
                <th col-tag="price"> Price </th>
                <th col-tag="reseller_price_amplitude"> Biên độ </th>
                <th col-tag="created_at"> Created At </th>
                <th col-tag="action" class="dt-center"> Tác vụ </th>
              </tr>
            </thead>
            <tbody>
                <?php if (!$models) :?>
                <tr><td colspan="8" id="no-data"><?=Yii::t('app', 'no_data_found');?></td></tr>
                <?php endif;?>
                <?php foreach ($models as $key => $model) :?>
                <tr class="<?=strtotime($model->invalid_at) <= $now ? 'invalid' : 'valid';?>">
                  <td class="center" col-tag="no"><?=$key + $pages->offset + 1;?></td>
                  <td col-tag="reseller_id"><?=$model->user->name;?></td>
                  <td col-tag="game_id"><?=$model->game->title;?></td>
                  <td class="center" col-tag="game_supplier_price"><?=StringHelper::numberFormat($model->game->price1 + $model->game->expected_profit, 1);?></td>
                  <td class="center" col-tag="price"><?=$model->price;?></td>
                  <td class="center" col-tag="reseller_price_amplitude"><?=number_format((int)$model->game->reseller_price_amplitude);?></td>
                  <td class="center" col-tag="created_at"><?=FormatConverter::convertToDate(strtotime($model->created_at), Yii::$app->params['date_time_format']);?></td>
                  <td class="center" col-tag="action">
                    <a href='<?=Url::to(['reseller-price/delete', 'reseller_id' => $model->reseller_id, 'game_id' => $model->game_id]);?>' class="btn btn-xs grey-salsa tooltips" data-pjax="0" data-container="body" data-original-title="Xoá"><i class="fa fa-close"></i></a>
                    <a href='<?=Url::to(['reseller-price/create', 'reseller_id' => $model->reseller_id, 'game_id' => $model->game_id]);?>' class="btn btn-xs grey-salsa tooltips" data-pjax="0" data-container="body" data-original-title="Chỉnh sửa"><i class="fa fa-pencil"></i></a>
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
