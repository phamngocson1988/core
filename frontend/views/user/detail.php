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
        <div class="profile-right">
          <div class="profile-info-left">
            <p class="profile-name">
              ORDER DETAIL <span>#<?=$model->id;?></span>
            </p>
            <div class="left-coin">
              <span>
                <p>Game:</p>
                <p>Platform:</p>
                <p>Character Name:</p>
                <p>Account Login:</p>
                <p>Account Password</p>
                <p>Server</p>
                <p>Login Method</p>
                <p>Recovery Code</p>
                <p>Special note to seller</p>
              </span>
              <span class="red">
                <p><?=$model->game_title;?></p>
                <p><?=$model->platform;?></p>
                <p><?=$model->character_name;?></p>
                <p><?=$model->username;?></p>
                <p><?=$model->password;?></p>
                <p><?=$model->server;?></p>
                <p><?=$model->login_method;?></p>
                <p><?=$model->recover_code;?></p>
                <p><?=$model->note;?></p>
              </span>
            </div>
          </div>
          <div class="profile-info-right">
            <p class="profile-name">DELIVERY STATUS<span><?=$model->doing_unit;?>/<?=$model->total_unit;?> <?=$model->getStatusLabel(null);?></span> </p>
            <div class="img-wrap-info-right">
              <?php foreach ($model->files as $file) :?>
              <div class="img-info-right">
                <a href="<?=$file->getUrl();?>" class="fancybox" rel="gallery"><img src="<?=$file->getUrl();?>" width="280px" height="175px" ></a>
              </div>
              <?php endforeach;?>
            </div>
            <div class="text-delivery">
              <div class="wrap-text">
                <p><?= date('F j, Y, H:i A \(P\)', strtotime($model->updated_at));?></p>
                <p>Deliveried 1</p>
                <p><?=$model->getStatusLabel(null);?>.</p>
              </div>
              
              <?php if ($model->isPendingOrder() || $model->isVerifyingOrder()) :?>
                <?php if ($model->request_cancel) :?>
                <a class="btn-product-detail-add-to-cart" href="javascript:;">Request was sent</a>
                <?php else:?>
                <a class="btn-product-detail-add-to-cart" href="javascript:;" data-toggle="modal" data-target="#cancel-modal">Cancel order</a>
                <?php endif;?>
              <?php endif;?>
              <?php if ($model->isProcessingOrder()) :?>
              <a href="<?=Url::to(['user/confirm', 'key' => $model->auth_key]);?>" id="complete" class="btn-product-detail-add-to-cart">Confirm Delivery</a>
              <?php endif;?>
            </div>
            <div class="tag-rate has-shadow">
              <svg id="star" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                width="30" height="30" viewBox="0 0 30 30">
                <image  width="30" height="30"
                  xlink:href="data:img/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAMAAAAM7l6QAAAABGdBTUEAALGPC/xhBQAAACBjSFJNAAB6JgAAgIQAAPoAAACA6AAAdTAAAOpgAAA6mAAAF3CculE8AAAA/1BMVEX/3QD/////3QD/3QD/3QD/3QD/3QD/3QD/3QD/3QD/3QD/3QD/3QD/3QD/3QD/3QD/3QD/3QD/3QD/3QD/3QD/3QD/3QD/3QD/3QD/3QD/3QD/3QD/3QD/3QD/3QD/3QD/3QD/3QD/3QD/3QD/3QD/3QD/3QD/3QD/3QD/3QD/3QD/3QD/3QD/3QD/3QD/3QD/3QD/3QD/3QD/3QD/3QD/3QD/3QD/3QD/3QD/3QD/3QD/3QD/3QD/3QD/3QD/3QD/3QD/3QD/3QD/3QD/3QD/3QD/3QD/3QD/3QD/3QD/3QD/3QD/3QD/3QD/3QD/3QD/3QD/3QD/3QD/3QAAAABiC+9QAAAAU3RSTlMAADTa4DvR6h0pdIzF2yAya4XOECoLCAd/jQpW3u7r4Wrc8OMuobMNfjbi9E0MsccckyExouT8BD+Jp5uP6CxEd0o5jgHCGq/mCQJ9D13pWEndZuKjeAIAAAABYktHRFTkA4ilAAAACXBIWXMAAAsSAAALEgHS3X78AAAAB3RJTUUH4wgIAxgpay8ezgAAAPxJREFUKM990elaAiEUBmDQcszct9QanVwyTS3NFtdyyd3UuP97cYBR5pD4/Tqcl4cHDgjZ43BeXGJ7gCIXIZqa3cTMlZI9lK+V7KXsU7GfsAQUHOQcUnCYs+80R4iVKOBYnOUmceAkb8RTJt/e6WkWjRyj8Y6eMRA5G3R/TrPI7VJrLm9eraDShyJ7mHFaHw/vLpX/41NFjKVak/X5BUzNA7UuDbUB+VXiJuSaxG+Q3z8Af8pXqwBuydwG7LS6na5V9Ozct5oJMcIvG3+zjj4wSzzkIxwJHrNf/ZnQGuPilK5m8yMv2HZ+EG0u6XolDl//ljdIMN7u0n+s2ANWG4iIp1uJLgAAAABJRU5ErkJggg==" />
              </svg>
              <p class="text-rate"><span>Rate this order!</span> How would you rate this buyer?</p>

              <a href="<?=Url::to(['user/like', 'key' => $model->auth_key]);?>" id='like' <?php if ($model->rating == 1): ?>class="red"<?php endif;?> >
                <svg id="like" xmlns="http://www.w3.org/2000/svg" width="31" height="31"
                  viewBox="0 0 31 31">
                  <circle id="Ellipse_5_copy_4" data-name="Ellipse 5 copy 4" class="cls-1"
                    cx="15.5" cy="15.5" r="15" />
                  <path class="cls-2"
                    d="M1644.95,943.7a13.285,13.285,0,0,1-1.87.063,5.478,5.478,0,0,1-1.33-.192,1.04,1.04,0,0,1-.72-0.691c-0.51-1.039-1.11-2.045-1.71-3.039-0.65-1.076-1.45-2.07-2.09-3.148-0.53-.9-0.68-2.148-1.45-2.9-0.73-.708-1.78,0-2.2.678a3.455,3.455,0,0,0,.29,3.358,6.034,6.034,0,0,1,.87,1.848,4.6,4.6,0,0,1-.03,1.612,2.566,2.566,0,0,1-.07.271c0.17-.091.33-0.182,0.49-0.273a16.357,16.357,0,0,0-5.06-.091,2.673,2.673,0,0,0-2.34,1.673,2.317,2.317,0,0,0,.91,2.2v-0.552a2.877,2.877,0,0,0-.84,1.27,2.265,2.265,0,0,0,1.13,2.475c-0.05-.178-0.1-0.356-0.14-0.535a2.007,2.007,0,0,0,.45,3.06l-0.15-.534a1.836,1.836,0,0,0,.05,1.631,3.289,3.289,0,0,0,2.46,1.363,20.941,20.941,0,0,0,4.62.22,0.391,0.391,0,0,0,0-.781,25.535,25.535,0,0,1-3.61-.086,5.576,5.576,0,0,1-2.21-.558,1.242,1.242,0,0,1-.69-1.016,1.1,1.1,0,0,1,.05-0.311,0.177,0.177,0,1,0,.02-0.068,0.4,0.4,0,0,0-.14-0.534c-0.81-.466-0.63-1.332-0.17-1.991a0.4,0.4,0,0,0-.14-0.535,1.434,1.434,0,0,1-.69-1.79,2.241,2.241,0,0,1,.56-0.728,0.377,0.377,0,0,0,0-.552,1.36,1.36,0,0,1-.45-1.907,3.338,3.338,0,0,1,2.27-.75,17.189,17.189,0,0,1,3.9.178,0.405,0.405,0,0,0,.49-0.273,5.4,5.4,0,0,0,.09-2.3c-0.21-1.4-1.44-2.318-1.44-3.783a1.427,1.427,0,0,1,.82-1.364c0.4-.163.59,0.354,0.72,0.632,0.25,0.513.43,1.055,0.66,1.572a9.742,9.742,0,0,0,1.03,1.622c0.72,1.023,1.37,2.1,1.99,3.184,0.28,0.483.56,0.97,0.82,1.462a5.834,5.834,0,0,0,.58,1.047,3.435,3.435,0,0,0,2.4.7,12.309,12.309,0,0,0,1.87-.063c0.5-.056.5-0.838,0-0.781h0Z"
                    transform="translate(-1620.69 -928.094)" />
                </svg>
              </a>

              <a href="javascript:;" data-toggle="modal" data-target="#show-modal" id='dislike' <?php if ($model->rating == -1): ?>class="red"<?php endif;?>>
                <svg id="dislike" xmlns="http://www.w3.org/2000/svg" width="31" height="31"
                  viewBox="0 0 31 31">
                  <circle id="Ellipse_5_copy_3" data-name="Ellipse 5 copy 3" class="cls-1"
                    cx="15.5" cy="15.5" r="15" />
                  <path class="cls-2"
                    d="M1668.05,943.8a13,13,0,0,1,1.86-.063,5.557,5.557,0,0,1,1.34.191,1.06,1.06,0,0,1,.72.69c0.51,1.039,1.11,2.043,1.71,3.036,0.65,1.075,1.45,2.067,2.09,3.144,0.53,0.9.68,2.145,1.46,2.895,0.72,0.707,1.77,0,2.19-.677a3.418,3.418,0,0,0-.28-3.353,6.039,6.039,0,0,1-.87-1.847,4.359,4.359,0,0,1,.03-1.61,1.281,1.281,0,0,1,.06-0.27c-0.16.09-.33,0.181-0.49,0.272a16.365,16.365,0,0,0,5.07.091,2.671,2.671,0,0,0,2.34-1.671,2.293,2.293,0,0,0-.91-2.2v0.552a2.885,2.885,0,0,0,.84-1.269,2.26,2.26,0,0,0-1.13-2.472c0.05,0.178.1,0.356,0.14,0.534a2,2,0,0,0-.45-3.056l0.15,0.534a1.833,1.833,0,0,0-.05-1.63,3.294,3.294,0,0,0-2.46-1.361,20.88,20.88,0,0,0-4.62-.219,0.39,0.39,0,0,0,0,.78,25.457,25.457,0,0,1,3.61.085,5.576,5.576,0,0,1,2.21.558,1.238,1.238,0,0,1,.69,1.014,1.086,1.086,0,0,1-.05.31,0.171,0.171,0,1,0-.02.069,0.4,0.4,0,0,0,.14.534c0.81,0.465.63,1.33,0.17,1.988a0.388,0.388,0,0,0,.14.534,1.432,1.432,0,0,1,.69,1.788,2.237,2.237,0,0,1-.56.727,0.377,0.377,0,0,0,0,.552,1.348,1.348,0,0,1,.45,1.9,3.347,3.347,0,0,1-2.28.75,17.359,17.359,0,0,1-3.9-.178,0.414,0.414,0,0,0-.49.272,5.391,5.391,0,0,0-.09,2.3c0.21,1.394,1.44,2.314,1.45,3.778a1.434,1.434,0,0,1-.83,1.362c-0.4.162-.59-0.354-0.72-0.632-0.24-.512-0.42-1.053-0.66-1.569a10.266,10.266,0,0,0-1.02-1.62c-0.73-1.023-1.38-2.1-2-3.181-0.28-.482-0.56-0.968-0.82-1.459a5.834,5.834,0,0,0-.58-1.047,3.445,3.445,0,0,0-2.41-.7,12.051,12.051,0,0,0-1.86.063c-0.5.055-.51,0.836,0,0.78h0Z"
                    transform="translate(-1660.69 -928.094)" />
                </svg>
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Modal-->
<div class="modal modal-custom fade" id="show-modal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"></span></button>
        <h4 class="modal-title">Sorry for this disadvantage</h4>
      </div>
      <div class="modal-body">
        <p>What's the problems?</p>
        <?= Html::beginForm(Url::to(['user/dislike', 'key' => $model->auth_key]), 'POST', ['class' => 'rd-mailform rd-mailform-inline rd-mailform-sm', 'id' => 'dislikeForm']); ?>
          <div class="rd-mailform-inline-inner">
            <div class="form-wrap">
              <input class="form-input" type="text" name="comment_rating" placeholder="Leave your complain">
            </div>
            <button class="button form-button button-sm button-secondary button-nina" type="submit">Send</button>
          </div>
        <?= Html::endForm(); ?>
      </div>
    </div>
  </div>
</div>
<div class="modal modal-custom fade" id="cancel-modal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"></span></button>
        <h4 class="modal-title">Do you want to cancel this order?</h4>
      </div>
      <div class="modal-body">
        <p>Your order is in progress. Your cancel request will be sent to our system and be considered by us.</p>
        <?= Html::beginForm(Url::to(['user/cancel', 'key' => $model->auth_key]), 'POST', ['class' => 'rd-mailform rd-mailform-inline rd-mailform-sm', 'id' => 'cancelForm']); ?>
          <div class="rd-mailform-inline-inner">
            <div class="form-wrap">
              <input class="form-input" type="text" name="content" placeholder="Leave your complain">
            </div>
            <button class="button form-button button-sm button-secondary button-nina" type="submit">Send</button>
          </div>
        <?= Html::endForm(); ?>
      </div>
    </div>
  </div>
</div>
<?php
$script = <<< JS
var complainForm = new AjaxFormSubmit({element: 'form#send-complain'});
complainForm.success = function (data, form) {
  location.reload();
}
complainForm.error = function (errors) {
  console.log(errors);
}

var dislikeForm = new AjaxFormSubmit({element: 'form#dislikeForm'});
dislikeForm.success = function (data, form) {
  location.reload();
}
dislikeForm.error = function (errors) {
  console.log(errors);
}

var cancelForm = new AjaxFormSubmit({element: 'form#cancelForm'});
cancelForm.success = function (data, form) {
  location.reload();
}
cancelForm.error = function (errors) {
  console.log(errors);
}

$('#complete').ajax_action({
  method: 'POST',
  callback: function(data) {
    location.reload();
  },
});
$('#like').ajax_action({
  method: 'POST',
  callback: function(data) {
    $('#rating').remove();
    alert('Thank for your rating!');
  },
});
JS;
$this->registerJs($script);
?>
