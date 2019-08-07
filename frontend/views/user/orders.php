<?php
use yii\widgets\Pjax;
use yii\widgets\LinkPager;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\helpers\Html;
use frontend\models\Game;
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
                  <th>Game</th>
                  <th>Amount</th>
                  <th>Created date</th>
                  <th>Status</th>
                </tr>
              </thead>
              <tbody>
                <?php if (!$models) :?>
                <tr><td colspan="5">No data found</td></tr>
                <?php endif;?>
                <?php foreach ($models as $model) :?>
                <tr>
                  <td><a href="<?=Url::to(['user/detail', 'id' => $model->id]);?>" data-pjax="0"><?=$model->id;?></a></td>
                  <td><?=$model->game_title;?></td>
                  <td>$<?=number_format($model->total_price);?></td>
                  <td><?=$model->created_at;?></td>
                  <td><?=$model->getStatusLabel();?></td>
                </tr>
                <?php endforeach;?>
              </tbody>
              <tfoot>
                <tr>
                  <td style="vertical-align: middle; backgroun-color: #CCC" colspan="2"><strong>Tổng đơn hàng: <?=number_format($filterForm->getCommand()->count());?></strong></td>
                  <td style="vertical-align: middle; backgroun-color: #CCC" colspan="3"><strong>Tổng King Coin: <?=number_format($filterForm->getCommand()->sum('total_price'));?></strong></td>
                  </td>
                </tr>
              </tfoot>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<?php
$script = <<< JS
JS;
$this->registerJs($script);
?>
