<?php
use yii\widgets\LinkPager;
use yii\widgets\Pjax;
use backend\components\datetimepicker\DateTimePicker;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\web\JsExpression;
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
      <span>Lịch sử giá game</span>
    </li>
  </ul>
</div>
<!-- END PAGE BAR -->
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title">Lịch sử giá game</h1>
<!-- END PAGE TITLE-->
<div class="row">
  <div class="col-md-12">
    <!-- BEGIN EXAMPLE TABLE PORTLET-->
    <div class="portlet light bordered">
      <div class="portlet-title">
        <div class="caption font-dark">
          <i class="icon-settings font-dark"></i>
          <span class="caption-subject bold uppercase">Lịch sử giá game</span>
        </div>
        <div class="actions">
        </div>
      </div>
      <div class="portlet-body">
        <?php $form = ActiveForm::begin(['method' => 'GET', 'action' => ['game/log']]);?>
        <div class="row margin-bottom-10">
          <?php $game = $search->getGame();?>   
          <?=$form->field($search, 'game_id', [
            'options' => ['class' => 'form-group col-md-4 col-lg-3'],
          ])->widget(kartik\select2\Select2::classname(), [
            'initValueText' => ($game) ? $game->title : '',
            'options' => ['class' => 'form-control', 'name' => 'game_id'],
            'pluginOptions' => [
              'placeholder' => 'Chọn game',
              'allowClear' => true,
              'minimumInputLength' => 3,
              'ajax' => [
                  'url' => Url::to(['game/suggestion']),
                  'dataType' => 'json',
                  'processResults' => new JsExpression('function (data) {return {results: data.data.items};}')
              ]
            ]
          ])->label('Tên game')?>
           <?=$form->field($search, 'date_range', [
              'options' => ['class' => 'form-group col-md-4 col-lg-3'],
              'inputOptions' => ['class' => 'form-control', 'name' => 'date_range']
            ])->dropDownList($search->getDateRange())->label('Khoảng thời gian');?>
          <div class="form-group col-md-4 col-lg-3">
            <button type="submit" class="btn btn-success table-group-action-submit" style="margin-top: 25px;">
              <i class="fa fa-check"></i> <?=Yii::t('app', 'search')?>
            </button>
          </div>
        </div>
        <?php ActiveForm::end()?>
        <?php Pjax::begin(); ?>
        <table class="table table-striped table-bordered table-hover table-checkable">
          <thead>
            <tr>
              <th>Tên game</th>
              <th>Ngày thay đổi</th>
              <th>Người thực hiện</th>
              <th>Giá cũ</th>
              <th>Giá đã thay đổi</th>
              <th>Tăng giảm</th>
            </tr>
          </thead>
          <tbody>
              <?php if (!$models) :?>
              <tr><td colspan="6"><?=Yii::t('app', 'no_data_found');?></td></tr>
              <?php endif;?>
              <?php foreach ($models as $no => $model) :?>
              <tr>
                <td style="vertical-align: middle;"><?=$model->game->title;?></td>
                <td style="vertical-align: middle;"><?=$model->updated_at;?></td>
                <td style="vertical-align: middle;"><?=sprintf("%s (#%s)", $model->user->name, $model->user->id);?></td>
                <td style="vertical-align: middle;">$<?=$model->old_price;?> | CNY <?=FormatConverter::convertCurrencyToCny($model->old_price);?></td>
                <td style="vertical-align: middle;">$<?=$model->new_price;?> | CNY <?=FormatConverter::convertCurrencyToCny($model->new_price);?></td>
                <td style="vertical-align: middle;">
                  <?php if ($model->change < 0) : ?>
                  <span style="color:red"><?=$model->change;?> | CNY <?=FormatConverter::convertCurrencyToCny($model->change);?></span>
                  <?php else : ?>
                  <span style="color:blue"><?=$model->change;?> | CNY <?=FormatConverter::convertCurrencyToCny($model->change);?></span>
                  <?php endif;?>
                </td>
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