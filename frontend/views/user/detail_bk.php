<!-- Old -->
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
              <th>Total price</th>
              <th>Total game</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td><img src="<?=$game->getImageUrl('100x100');?>" class="img-responsive" /></td>
              <td><?=$model->game_title;?></td>
              <td><?=number_format($model->total_price);?></td>
              <td><?=number_format($model->total_unit);?></td>
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
                <?php if ($model->total_discount):?>
                <tr>
                  <td>Sub total:</td>
                  <td><?=number_format($model->sub_total_price);?></td>
                </tr>
                <tr>
                  <td>Discount:</td>
                  <td><?=number_format($model->total_discount);?></td>
                </tr>
                <?php endif;?>
                <tr>
                  <td>Total:</td>
                  <td><?=number_format($model->total_price);?></td>
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
                      <!-- <a href="<?=Url::to(['user/dislike', 'key' => $model->auth_key]);?>" class="button button-icon-alternate button-icon-left button-xs button-default-outline button-shadow" id='dislike'><span class="icon novi-icon mdi mdi-thumb-down-outline"></span>Dislike</a> -->
                      <button class="button button-icon-alternate button-icon-left button-xs button-default-outline button-shadow" type="button" data-toggle="modal" data-target="#show-modal"><span class="icon novi-icon mdi mdi-thumb-down-outline"></span>Dislike</button>
                      
                    </div>
                  </td>
                </tr>
                <?php endif;?>
                <?php if ($model->isPendingOrder() || $model->isVerifyingOrder()) :?>
                <tr id="request">
                  <td colspan="2">
                    <div class="group-md button-group">
                      <?php if ($model->request_cancel) :?>
                      <button class="button button-icon-alternate button-icon-left button-xs button-default-outline button-shadow" type="button" ><span class="icon novi-icon mdi mdi-close"></span>Request was sent</button>
                      <?php else:?>
                      <button class="button button-icon-alternate button-icon-left button-xs button-default-outline button-shadow" type="button" data-toggle="modal" data-target="#cancel-modal"><span class="icon novi-icon mdi mdi-close"></span>Cancel order</button>
                      <?php endif;?>
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
                  <td><?=$model->username;?></td>
                </tr>
                <tr>
                  <td>Password</td>
                  <td><?=$model->password;?></td>
                </tr>
                <tr>
                  <td>Character name</td>
                  <td><?=$model->character_name;?></td>
                </tr>
                <tr>
                  <td>Platform</td>
                  <td><?=$model->platform;?></td>
                </tr>
                <tr>
                  <td>Login method</td>
                  <td><?=$model->login_method;?></td>
                </tr>
                <?php if ($model->recover_code):?>
                <tr>
                  <td>Recover Code</td>
                  <td><?=$model->recover_code;?></td>
                </tr>
                <?php endif;?>
                <?php if ($model->server):?>
                <tr>
                  <td>Server</td>
                  <td><?=$model->server;?></td>
                </tr>
                <?php endif;?>
                <?php if ($model->note):?>
                <tr>
                  <td>Note</td>
                  <td><?=$model->note;?></td>
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