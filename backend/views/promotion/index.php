<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\widgets\Pjax;
use common\models\Promotion;
use yii\widgets\LinkPager;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\PromotionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Promotions';
$this->params['breadcrumbs'][] = $this->title;
?>
<!-- BEGIN PAGE BAR -->
<div class="page-bar">
  <ul class="page-breadcrumb">
    <li>
      <a href="/"><?=Yii::t('app', 'home')?></a>
      <i class="fa fa-circle"></i>
    </li>
    <li>
      <span>Quản lý khuyến mãi</span>
    </li>
  </ul>
</div>
<!-- END PAGE BAR -->
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title">Quản lý khuyến mãi</h1>
<!-- END PAGE TITLE-->
<div class="row">
  <div class="col-md-12">
    <!-- BEGIN EXAMPLE TABLE PORTLET-->
    <div class="portlet light bordered">
      <div class="portlet-title">
        <div class="caption font-dark">
          <i class="icon-settings font-dark"></i>
          <span class="caption-subject bold uppercase"> Quản lý khuyến mãi</span>
        </div>
        <div class="actions">
          <div class="btn-group btn-group-devided">
            <?= Html::a(Yii::t('app', 'add_new'), ['promotion/create'], ['class' => 'btn green']) ?>
          </div>
        </div>
      </div>

      <div class="portlet-body">
        <div class="row margin-bottom-10">
        </div>
        <?php Pjax::begin(); ?>
        <table class="table table-striped table-bordered table-hover table-checkable" data-sortable="true" data-url="<?=Url::to(['order/index']);?>">
          <thead>
            <tr>
              <th style="width: 5%;"> <?=Yii::t('app', 'no');?> </th>
              <th style="width: 20%;"> Tiêu đề </th>
              <th style="width: 10%;"> Mã khuyến mãi </th>
              <th style="width: 20%;"> Ngày áp dụng </th>
              <th style="width: 10%;"> Loại giảm giá </th>
              <th style="width: 20%;"> Ngữ cảnh áp dụng </th>
              <th style="width: 10%;"> <?=Yii::t('app', 'status');?> </th>
              <th style="width: 5%;" class="dt-center"> <?=Yii::t('app', 'actions');?> </th>
            </tr>
          </thead>
          <tbody>
              <?php if (!$models) :?>
              <tr><td colspan="8"><?=Yii::t('app', 'no_data_found');?></td></tr>
              <?php endif;?>
              <?php foreach ($models as $model) :?>
              <tr>
                <td style="vertical-align: middle;">#<?=$model->id;?></td>
                <td style="vertical-align: middle;"><?=$model->title;?></td>
                <td style="vertical-align: middle;"><?=$model->code;?></td>
                <td style="vertical-align: middle;"><?=sprintf("%s - %s", $model->from_date, $model->to_date);?></td>
                <td style="vertical-align: middle;"><?=sprintf("%s (%s)", $model->value, $model->value_type);?></td>
                <td style="vertical-align: middle;"><?php
                if ($model->object_type == 'coin') :
                  echo 'Giảm khi mua game';
                elseif ($model->object_type == 'money') :
                  echo 'Giảm khi mua coin';
                endif;
                ?></td>
                <td style="vertical-align: middle;"><?php
                if ($model->status == 'Y') :
                  echo 'Đã kích hoạt';
                elseif ($model->status == 'N') :
                  echo 'Chưa kích hoạt';
                endif;
                ?></td>
                <td style="vertical-align: middle;">
                  <a href='<?=Url::to(['promotion/edit', 'id' => $model->id, 'ref' => $ref]);?>' class="btn btn-xs grey-salsa tooltips" data-pjax="0" data-container="body" data-original-title="Chỉnh sửa"><i class="fa fa-pencil"></i></a>
                </td>
              </tr>
              <?php endforeach;?>
          </tbody>
        </table>
        <?=LinkPager::widget(['pagination' => $pages])?>
        <?php Pjax::end(); ?>
      </div>
    </div>
  </div>
</div>