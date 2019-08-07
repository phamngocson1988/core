<?php
use yii\widgets\Pjax;
use yii\widgets\LinkPager;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\helpers\Html;
?>
<section class="page-title">
  <div class="container">
    <div class="row">
      <div class="col col-sm-12">
        <div class="page-title-content text-center">
          <img src="/images/text-your-account.png" alt="">
        </div>
        <div class="page-title-sub">
          <p>Manage your account</p>
        </div>
      </div>
    </div>
  </div>
</section>
<section class="profile-page">
  <div class="container-fluid">
    <div class="row">
      <?php require_once(Yii::$app->basePath . '/views/user/_left_menu.php');?>
      <div class="wrap-profile-right col col-lg-8 col-md-9 col-sm-12 col-12">
        <div class="profile-right" style="width: 100%;" id="reward-feed">
          <div class="profit-listing">
            <table class="table-custom table-hover">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Date</th>
                  <th>Type</th>
                  <th>Coin</th>
                  <th>Description</th>
                  <th>Status</th>
                </tr>
              </thead>
              <tbody>
                <?php if (!$models) :?>
                <tr><td colspan="6">No data found</td></tr>
                <?php endif;?>
                <?php foreach ($models as $no => $model) :?>
                <tr>
                  <td>#<?=$model->id?></td>
                  <td><?=$model->payment_at;?></td>
                  <td><?=$model->getTypeLabel();?></td>
                  <td><?=number_format($model->coin);?></td>
                  <td><?=$model->description;?></td>
                  <td><?=$model->status;?></td>
                </tr>
                <?php endforeach;?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>