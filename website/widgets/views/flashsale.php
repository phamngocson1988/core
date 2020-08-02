<?php 
use yii\helpers\Url;
use yii\helpers\Html;
?>
<div class="container mt-5" id="flashsale">
  <div class="d-flex justify-content-between align-items-center mb-5">
    <h2 class="block-title text-uppercase flash-sales flex-fill">Flash sales <span class="num" id="flashsale-hour">10</span><span
      class="num" id="flashsale-minute">56</span><span class="num" id="flashsale-second">08</span>
    </h2>
    <div class="flex-fill">
      <a href="#" class="link-dark font-weight-bold link-view-all">View all <img class="icon-sm"
        src="/images/icon/next.svg" /></a>
    </div>
  </div>
  <div class="post-wrapper post-slider" data-aos="fade-up" data-aos-duration="800">
    <?php foreach ($flashsaleGames as $flashsaleGame) :?>
    <?php $game = $flashsaleGame->game;?>
    <?php if (!$game) :;?>
    <?php continue;?>
    <?php endif;?>
    <?php $viewUrl = Url::to(['game/view', 'id' => $game->id, 'slug' => $game->slug]);?>
    <?php $percent = round($flashsaleGame->remain * 100 / $flashsaleGame->limit);?>
    <div class="post-item card">
      <div class="post-thumb">
        <a href="<?=$viewUrl;?>" class="hover-img">
          <img src="<?=$game->getImageUrl('300x300');?>" />
        </a>
        <?php if ($game->getSavedPrice()) : ?>
        <span class="tag save">save <?=number_format($game->getSavedPrice());?>%</span>
        <?php endif;?>
        <?php if ($game->promotion_info) : ?>
        <span class="tag promotion"><?=$game->promotion_info;?></span>
        <?php endif;?>
        <?php if ($game->isBackToStock()) : ?>
        <span class="tag bts">back to stock</span>
        <?php endif;?>
      </div>
      <div class="post-content">
        <h4 class="post-title">
          <a href="<?=$viewUrl;?>"><?=Html::encode($game->title);?></a>
        </h4>
        <?php if ($game->hasCategory()) : ?>
        <div class="tags">
          <img src="/images/icon/tag.svg" />
          <?php foreach ($game->categories as $category) : ?>
          <span class="badge badge-primary"><?=$category->name;?></span>
          <?php endforeach; ?>
        </div>
        <?php endif;?>
        <div class="d-flex justify-content-between align-items-center py-2">
          <div class="flex-fill value">
            <span class="num"><?=number_format($game->pack);?></span>
            <br />
            <span class="text"><?=Html::encode($game->unit_name);?></span>
          </div>
          <div class="flex-fill price">
            <strike>$<?=number_format($game->getOriginalPrice());?></strike> <span class="num">$<?=number_format($game->getPrice());?></span>
          </div>
        </div>
        <div class="d-flex justify-content-between align-items-center">
          <div class="flex-fill status">
            <hr>
            <img class="icon-fire" src="/images/icon/fire.svg" />
            <?php if ($game->isSoldout()) :?>
            <span class="text" style="color: gray">out stock</span>
            <?php else : ?>
            <span class="text">in stock</span>
            <?php endif;?>
          </div>
          <div class="flex-fill">
            <a href="<?=$viewUrl;?>" class="main-btn">
            <span>BUY NOW</span>
            </a>
          </div>
        </div>
        <div class="progress mt-2">
          <div class="text">
            <img class="icon-fire" src="./images/icon/miscellaneous.svg" />
            only <?=number_format($flashsaleGame->remain);?> left
          </div>
          <div class="progress-bar" role="progressbar" style="width: <?=$percent;?>%;" aria-valuenow="<?=$percent;?>" aria-valuemin="0"
            aria-valuemax="100"></div>
        </div>
      </div>
    </div>
    <?php endforeach;?>
  </div>
  <!-- END POST SLIDER -->
</div>
<?php
$toDate = $flashsale->start_to;
$script = <<< JS
// Set the date we're counting down to
var countDownDate = new Date("$toDate").getTime();

// Update the count down every 1 second
var x = setInterval(function() {

  // Get today's date and time
  var now = new Date().getTime();

  // Find the distance between now and the count down date
  var distance = countDownDate - now;

  // Time calculations for days, hours, minutes and seconds
  var days = Math.floor(distance / (1000 * 60 * 60 * 24));
  var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60)) + days * 24;
  var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
  var seconds = Math.floor((distance % (1000 * 60)) / 1000);

  // Display the result in the element with id="demo"
  $('#flashsale-hour').html(hours);
  $('#flashsale-minute').html(minutes);
  $('#flashsale-second').html(seconds);

  // If the count down is finished, write some text
  if (distance < 0) {
    clearInterval(x);
    $("#flashsale").remove();
  }
}, 1000);
JS;
$this->registerJs($script);
?>