<?php
use yii\widgets\Pjax;
use yii\widgets\LinkPager;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\helpers\Html;
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

<section class="section section-lg bg-default novi-background bg-cover" id="content">
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
                <?php if ($model->isProcessingOrder()) :?>
                <tr>
                  <td colspan="2" style="align:center">
                    <div style="align:center">
                      <a href="<?=Url::to(['user/confirm', 'key' => $model->auth_key]);?>" id="complete" class="button button-default-outline button-nina button-block button-blog">Confirm Delivery</a>
                    </div>
                  </td>
                </tr>
                <?php endif;?>
                <?php if (!$model->isRating()) :?>
                <tr id="rating">
                  <td colspan="2">
                    <div class="group-md button-group">
                      <a href="<?=Url::to(['user/like', 'key' => $model->auth_key]);?>" class="button button-icon-alternate button-icon-left button-xs button-secondary button-shadow" id='like'><span class="icon novi-icon mdi mdi-thumb-up-outline"></span>Like</a>
                      <a href="<?=Url::to(['user/dislike', 'key' => $model->auth_key]);?>" class="button button-icon-alternate button-icon-left button-xs button-default-outline button-shadow" id='dislike'><span class="icon novi-icon mdi mdi-thumb-down-outline"></span>Dislike</a>
                    </div>
                  </td>
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
            <div class="col-6 col-md-6 col-lg-6 isotope-item" data-filter="type 1"><a class="gallery-item" href="<?=$item->getImageBeforeUrl('/images/bg-05.jpg');?>" data-lightgallery="item">
                <div class="gallery-item-image">
                  <figure><img src="<?=$item->getImageBeforeUrl('/images/bg-05.jpg');?>" alt="" width="570" height="380" class="img-responsive"/></figure>
                  <div class="caption">
                    <p class="caption-title">Before</p>
                    <p class="caption-text">Your account before doing payment</p>
                  </div>
                </div></a>
            </div>
            <div class="col-6 col-md-6 col-lg-6 isotope-item" data-filter="type 1"><a class="gallery-item" href="<?=$item->getImageAfterUrl('/images/bg-05.jpg');?>" data-lightgallery="item">
                <div class="gallery-item-image">
                  <figure><img src="<?=$item->getImageAfterUrl('/images/bg-05.jpg');?>" alt="" width="570" height="380" class="img-responsive"/></figure>
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

<section class="section-lg text-center bg-default" id="complains">
  <!-- section wave-->
  <div class="container">
    <h4 class="text-center">Order Complains</h3>
  </div>
  <!-- Style switcher-->
  <?php if ($model->complains) : ?>
  <div class="style-switcher" data-container="">
    <div class="style-switcher-container">
      <div class="style-switcher-toggle-wrap"> 
      </div>
      <section class="section section-lg novi-background bg-cover text-center text-lg-left bg-gray-darker">
        <div class="container">
          <div class="time-line-vertical">
            <?php foreach ($model->complains as $complain) : ?>
            <div class="time-line-vertical-element">
              <div class="unit unit-sm flex-column flex-md-row unit-spacing-xxl">
                <div class="unit-left">
                  <div class="time-line-time">
                    <time class="wow fadeInLeft" data-wow-delay=".6s" datetime="2018"><?=$complain->sender->name;?> (<?=$complain->created_at;?>)</time>
                  </div>
                </div>
                <div class="unit-body">
                  <div class="time-line-content wow fadeInRight" data-wow-delay=".6s">
                    <p><?=$complain->content;?></p>
                  </div>
                </div>
              </div>
            </div>
            <?php endforeach;?>
          </div>
        </div>
      </section>
    </div>
  </div>
  <?php endif;?>
  <?php if (!$model->isCompletedOrder() && !$model->isDeletedOrder()) :?>
  <?= Html::beginForm(['user/send-complain'], 'POST', ['id' => 'send-complain']); ?>
  <div class="container">
    <div class="row row-fix justify-content-sm-center">
      <div class="row row-fix row-20">
        <div class="col-md-12">
          <div class="form-wrap form-wrap-validation field-cartitem-server has-success">
            <label class="form-label-outside">Content</label>
            <?= Html::textArea('content', '', ['class' => 'form-input']); ?>
          </div>
          <?= Html::hiddenInput('order_id', $model->id); ?>
        </div>
        <div class="col-lg-12 offset-custom-1">
          <div class="form-button text-md-right">
            <?= Html::submitButton('Send complain', ['class' => 'button button-secondary button-nina', 'id' => 'send-form']) ?>
          </div>
        </div>
      </div>
    </div>
  </div>
  <?= Html::endForm(); ?>
  <?php endif;?>
</section>

<?php
$script = <<< JS
var complainForm = new AjaxFormSubmit({element: 'form#send-complain'});
complainForm.success = function (data, form) {
  location.reload();
}
complainForm.error = function (errors) {
  console.log(errors);
}
$('#complete').ajax_action({
  method: 'POST',
  callback: function(data) {
    location.reload();
  },
});
$('#like,#dislike').ajax_action({
  method: 'POST',
  callback: function(data) {
    $('#rating').remove();
    alert('Thank for your rating');
  },
});
JS;
$this->registerJs($script);
?>
