<?php 
use yii\helpers\Url;
use yii\helpers\Html;
use website\models\OrderFile;
?>
<div class="modal-header d-block">
  <h2 class="modal-title text-center w-100 text-red text-uppercase">Order details</h2>
  <p class="text-center d-block">Order ID: #<?=$order->id;?></p>
  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
    <span aria-hidden="true">&times;</span>
  </button>
</div>
<div class="modal-body">
  <div class="row">
    <div class="col-md-6 border-right">
      <div><span class="list-item">Game:</span><b><?=$order->game_title;?></b></div>
      <div><span class="list-item">Version:</span><b>Global</b></div>
      <div><span class="list-item">Pack:</span><b class="text-red"><?=sprintf("%s %s", number_format($order->total_unit), $order->unit_name);?></b></div>
      <div><span class="list-item">Quantity:</span><b><?=number_format($order->quantity, 1);?></b></div>
      <div><span class="list-item">Total Unit:</span><b class="text-red"><?=sprintf("%s %s", $order->total_unit, $order->unit_name);?></b></div>
      <hr />
      <div><span class="list-item">Payment method:</span><b class="text-red"><?=$order->payment_method;?></b></div>
      <div><span class="list-item">Sub Price:</span><b class="text-red"><?=sprintf("%s %s", number_format($order->sub_total_price, 1), $order->currency);?></b></div>
      <div><span class="list-item">Transfer fee:</span><b class="text-red"><?=sprintf("%s %s", number_format($order->total_fee, 1), $order->currency);?></b></div>
      <div><span class="list-item">Total Price:</span><b class="text-red"><?=sprintf("%s %s", number_format($order->total_price, 1), $order->currency);?></b></div>
    </div>
    <div class="col-md-6">
      <?php if ($order->raw) : ?>
      <?=nl2br($order->raw);?>
      <?php else : ?>
      <div><span class="list-item">Login method:</span><b><?=$order->login_method;?></b></div>
      <div><span class="list-item">Character name:</span><b><?=$order->character_name;?></b></div>
      <div><span class="list-item">Account login:</span><b><?=$order->username;?></b></div>
      <div><span class="list-item">Account password:</span><b><?=$order->password;?></b></div>
      <div><span class="list-item">Server:</span><b><?=$order->server;?></b></div>
      <div><span class="list-item">Recovery code:</span><b><?=$order->recover_code;?></b></div>
      <?php endif;?>
    </div>
    <?php if ($order->isCompletedOrder() || $order->isConfirmedOrder()) : ?>
    <div class="col-md-12 mt-4">
      <h3 class="text-center text-uppercase">delivery status</h3>
      <p class="text-center"><?=$order->getPercent();?>% Completed</p>
      <div class="row bf-at">
        <?php $files = $order->getEvidencesByType(OrderFile::TYPE_EVIDENCE_BEFORE);?>
        <?php foreach ($files as $file) : ?>
        <div class="col-md-6 mb-4">
          <a href="<?=Url::to(['order/files', 'id' => $order->id]);?>" data-order="<?=$order->id;?>" data-toggle="modal" data-target="#img-modal">
            <img class="btn-modal-img" src="<?=$file->getUrl();?>" />
          </a>
        </div>
        <?php endforeach ;?>
      </div>
      <p class="text-center">
        <?php if ($order->isCompletedOrder()) : ?>
        <a role="button" id="confirm-order-button" href="<?=Url::to(['order/confirm', 'id' => $order->id]);?>" class="btn btn-comfirm text-uppercase">comfirm delivery</a>
        <?php elseif ($order->isConfirmedOrder()) : ?>
        <button type="button" class="btn text-uppercase">comfirm delivery</button>
        <?php endif;?>
      </p>
      <p class="text-center mb-0">
        <b>Rate this order!</b>
      </p>
      <!-- Rating Stars Box -->
      <div class='rating-stars text-center'>
        <ul id='stars'>
          <li class='star <?=((int)$order->rating >= 1) ? "hover" : "";?>' title='Poor' data-value='1'>
            <a href="<?=Url::to(['order/survey', 'id' => $order->id, 'star' => 1]);?>" data-toggle="modal" data-target="#modalSurvey" >
              <span class="icon-star"></span>
            </a>
          </li>
          <li class='star <?=((int)$order->rating >= 2) ? "hover" : "";?>' title='Fair' data-value='2'>
            <a href="<?=Url::to(['order/survey', 'id' => $order->id, 'rating' => 2]);?>" data-toggle="modal" data-target="#modalSurvey" >
              <span class="icon-star"></span>
            </a>
          </li>
          <li class='star <?=((int)$order->rating >= 3) ? "hover" : "";?>' title='Good' data-value='3'>
            <a href="<?=Url::to(['order/survey', 'id' => $order->id, 'rating' => 3]);?>" data-toggle="modal" data-target="#modalSurvey" >
              <span class="icon-star"></span>
            </a>
          </li>
          <li class='star <?=((int)$order->rating >= 4) ? "hover" : "";?>' title='Excellent' data-value='4'>
            <a href="<?=Url::to(['order/survey', 'id' => $order->id, 'rating' => 4]);?>" data-toggle="modal" data-target="#modalSurvey" >
              <span class="icon-star"></span>
            </a>
          </li>
          <li class='star <?=((int)$order->rating >= 5) ? "hover" : "";?>' title='WOW!!!' data-value='5'>
            <a href="<?=Url::to(['order/survey', 'id' => $order->id, 'rating' => 5]);?>" data-toggle="modal" data-target="#modalSurvey" >
              <span class="icon-star"></span>
            </a>
          </li>
        </ul>
      </div>
    </div>
    <?php endif;?>
  </div>
  <h4 class="text-uppercase text-right chat-admin-title">Chat ADMIN</h4>
  <div class="row rounded-lg overflow-hidden shadow">
    <!-- Chat Box-->
    <div class="col-12 px-0">
      <div class="px-4 py-5 chat-box bg-white complain-list" data-url="<?=Url::to(['order-complain/list', 'id' => $order->id]);?>">

      </div>

      <!-- Typing area -->
      <?= Html::beginForm(Url::to(['order/send-complain', 'id' => $order->id]), 'POST', ['id' => 'send-complain-form', 'class' => 'bg-light']); ?>
        <div class="input-group">
        <!--   <div contentEditable="true" placeholder="Type a message" aria-describedby="button-addon2"
            class="form-control rounded-0 border-0 py-4 bg-light">
            Anh nhà ở đâu thế <img class="icon-md" src="/images/post-item01.jpg"> bla bla
          </div> -->
          <textarea contentEditable="true" placeholder="Type a message" aria-describedby="button-addon2"
            class="form-control rounded-0 border-0 py-4 bg-light" name="content" rows="3"></textarea>
          <!-- <div class="input-group-append">
            <input class="d-none" type="file" id="FileUpload"/>
            <button onclick='$("#FileUpload").click()' id="button-addon2" type="file" class="btn btn-link">
              <img class="icon-sm" src="/images/icon/attach.svg" />
            </button>
          </div> -->
          <div class="input-group-append">
            <button id="send-complain-button" type="button" class="btn btn-link">
              <img class="icon-sm" src="/images/icon/send.svg" />
            </button>
          </div>
        </div>
      <?= Html::endForm(); ?>

    </div>
  </div>
  <!-- END CHATBOX -->
</div>