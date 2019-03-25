<?php
use yii\widgets\Pjax;
use yii\widgets\LinkPager;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use frontend\models\Game;
$items = $model->items;
$item = reset($items);
$game = Game::findOne($item->game_id);
?>
<section class="section section-lg bg-default text-center">
  <div class="container">
    <div class="row justify-content-sm-center">
      <div class="col-md-12 col-xl-12">
        <h4>Order Item</h3>
        <table class="table-custom table-hover">
          <thead>
            <tr>
              <th>#</th>
              <th>Game</th>
              <th>Quantity</th>
              <th>Amount</th>
              <th>Total</th>
              <th>Unit</th>
              <th>Unit Total</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td><img src="<?=$game->getImageUrl('100x100');?>" class="img-responsive" /></td>
              <td><?=$item->item_title;?></td>
              <td><?=$item->quantity;?></td>
              <td>(K) <?=number_format($item->price);?></td>
              <td>(K) <?=number_format($item->total);?></td>
              <td><?=number_format($item->unit);?> (<?=$item->unit_name;?>)</td>
              <td><?=number_format($item->total_unit);?> (<?=$item->unit_name;?>)</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</section>

<section class="section section-lg bg-default novi-background bg-cover">
  <!-- section wave-->
  <div class="container grid-demonstration">
    <h4 class="text-center">Order Information</h3>
    <div class="row">
      <div class="col-6">
        <div class="box-classic box-bordered box-novi">
          <div class="box-classic-content">
            <table class="table-custom table-hover">
              <tbody>
                <tr>
                  <td>Username</td>
                  <td><?=$item->username;?></td>
                </tr>
                <tr>
                  <td>Password</td>
                  <td><?=$item->password;?></td>
                </tr>
                <tr>
                  <td>Character name</td>
                  <td><?=$item->character_name;?></td>
                </tr>
                <tr>
                  <td>Platform</td>
                  <td><?=$item->platform;?></td>
                </tr>
                <tr>
                  <td>Login method</td>
                  <td><?=$item->login_method;?></td>
                </tr>
                <?php if ($item->recover_code):?>
                <tr>
                  <td>Recover Code</td>
                  <td><?=$item->recover_code;?></td>
                </tr>
                <?php endif;?>
                <?php if ($item->server):?>
                <tr>
                  <td>Server</td>
                  <td><?=$item->server;?></td>
                </tr>
                <?php endif;?>
                <?php if ($item->note):?>
                <tr>
                  <td>Note</td>
                  <td><?=$item->note;?></td>
                </tr>
                <?php endif;?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
      <div class="col-6">
        <div class="box-classic box-bordered box-novi">
          <div class="box-classic-content">
            <table class="table-custom table-hover">
              <tbody>
                <tr>
                  <td>Customer Name</td>
                  <td><?=$model->customer_name;?></td>
                </tr>
                <tr>
                  <td>Customer email</td>
                  <td><?=$model->customer_email;?></td>
                </tr>
                <tr>
                  <td>Customer phone</td>
                  <td><?=$model->customer_phone;?></td>
                </tr>
                <tr>
                  <td>Created at:</td>
                  <td><?=$model->created_at;?></td>
                </tr>
                <tr>
                  <td>Payment method:</td>
                  <td>King Payment Gateway</td>
                </tr>
                <tr>
                  <td>Order status:</td>
                  <td><?=$model->status;?></td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<section class="section section-lg text-center bg-default">
  <div class="container">
    <h4>Order Images</h3>
    <div class="isotope-wrap row row-0 row-md-30 row-fix">
      <div class="col-xl-12">
        <div class="isotope" data-isotope-layout="fitRows" data-isotope-group="gallery">
          <div class="row">
            <div class="col-6 col-md-6 col-lg-6 isotope-item" data-filter="type 1"><a class="gallery-item" href="<?=$game->getImageUrl();?>" data-lightgallery="item">
                <div class="gallery-item-image">
                  <figure><img src="<?=$game->getImageUrl('500x500');?>" alt="" width="570" height="380"/></figure>
                  <div class="caption">
                    <p class="caption-title">Before</p>
                    <p class="caption-text">Your account before doing payment</p>
                  </div>
                </div></a>
            </div>
            <div class="col-6 col-md-6 col-lg-6 isotope-item" data-filter="type 1"><a class="gallery-item" href="<?=$game->getImageUrl();?>" data-lightgallery="item">
                <div class="gallery-item-image">
                  <figure><img src="<?=$game->getImageUrl();?>" alt="" width="570" height="380"/></figure>
                  <div class="caption">
                    <p class="caption-title">After</p>
                    <p class="caption-text">Your account after doing payment</p>
                  </div>
                </div></a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>