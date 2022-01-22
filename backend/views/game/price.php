<?php
use yii\widgets\LinkPager;
use yii\widgets\Pjax;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use backend\models\User;
use common\components\helpers\FormatConverter;
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
      <a href="<?=Url::to(['game/index']);?>">Game</a>
      <i class="fa fa-circle"></i>
    </li>
    <li>
      <span>Tổng hợp giá</span>
    </li>
  </ul>
</div>
<!-- END PAGE BAR -->
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title">Tổng hợp giá</h1>
<!-- END PAGE TITLE-->
<div class="row">
  <div class="col-md-12">
    <!-- BEGIN EXAMPLE TABLE PORTLET-->
    <div class="portlet light bordered">
      <div class="portlet-title">
        <div class="caption font-dark">
          <i class="icon-settings font-dark"></i>
          <span class="caption-subject bold uppercase"> Tổng hợp giá</span>
        </div>
        <div class="actions">
        </div>
      </div>
      <div class="portlet-body">
        <div class="row margin-bottom-10">
          <form method="GET">
            <div class="form-group col-md-4">
              <label>Tìm kiếm theo tên game: </label> <input type="search" class="form-control" placeholder="Nhập tên game" name="q" value="<?=$q;?>">
            </div>
            <div class="form-group col-md-3">
              <button type="submit" class="btn btn-success table-group-action-submit" style="margin-top: 25px;">
                <i class="fa fa-check"></i> Tìm kiếm
              </button>
            </div>
          </form>
        </div>
        <?php Pjax::begin(); ?>
        <table class="table table-striped table-bordered table-hover table-checkable">
          <thead>
            <tr>
              <th> STT </th>
              <th> Tên game </th>
              <th> Giá bán lẻ </th>
              <th> Giá Gold </th>
              <th> Giá Diamond </th>
              <th> Giá Platium </th>
            </tr>
          </thead>
          <tbody>
              <?php if (!$models) :?>
              <tr><td colspan="6"><?=Yii::t('app', 'no_data_found');?></td></tr>
              <?php endif;?>
              <?php foreach ($models as $key => $model) :?>
              <tr>
                <td><?=$key + $pages->offset + 1;?></td>
                <td style="vertical-align: middle;"><?=$model->title;?></td>
                <td style="vertical-align: middle;">$<?=$model->getPrice();?> | CNY <?=FormatConverter::convertCurrencyToCny($model->getPrice());?></td>
                <td style="vertical-align: middle;">$<?=$model->getResellerPrice(User::RESELLER_LEVEL_1);?> | CNY <?=FormatConverter::convertCurrencyToCny($model->getResellerPrice(User::RESELLER_LEVEL_1));?></td>
                <td style="vertical-align: middle;">$<?=$model->getResellerPrice(User::RESELLER_LEVEL_2);?> | CNY <?=FormatConverter::convertCurrencyToCny($model->getResellerPrice(User::RESELLER_LEVEL_2));?></td>
                <td style="vertical-align: middle;">$<?=$model->getResellerPrice(User::RESELLER_LEVEL_3);?> | CNY <?=FormatConverter::convertCurrencyToCny($model->getResellerPrice(User::RESELLER_LEVEL_3));?></td>
              </tr>
              <?php endforeach;?>
          </tbody>
        </table>
        <?=LinkPager::widget(['pagination' => $pages])?>
        <?php Pjax::end(); ?>
      </div>
    </div>
    <!-- END EXAMPLE TABLE PORTLET-->
  </div>
</div>