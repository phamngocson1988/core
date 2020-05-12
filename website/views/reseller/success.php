<?php
use yii\helpers\Url;
?>
<section class="topup-page">
  <div class="container">
    <div class="small-container">
      <div class="row">
        <div class="col col-lg-12 col-md-12 col-sm-12 col-12">
          <div class="affiliate-top no-mar-bot">
            <div class="has-left-border has-shadow no-mar-top">
              Success
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<section class="topup-page">
  <div class="container">
    <div class="small-container">
      <div class="row">
        <div class="col col-lg-12 col-md-12 col-sm-12 col-12">
          <div class="cart-table">
            <table>
              <thead>
                <tr>
                  <th>Order No</th>
                  <th>Game</th>
                  <th>Quantity</th>
                  <th>Total Price</th>
                  <th>Status</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($orders as $model) :?>
                <tr>
                  <td><a href="<?=Url::to(['user/detail', 'id' => $model->id]);?>" class="normal-link"><?=$model->id;?></a></td>
                  <td><?=$model->game_title;?></td>
                  <td><?=$model->quantity;?></td>
                  <td><?=$model->total_price;?></td>
                  <td><?=$model->status;?></td>
                </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
            <div class="cart-coupon" style="text-align: center;">
              <a href="<?=Url::to(['site/index']);?>" class="cus-btn yellow fl-right">Home Page</a>
          </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
