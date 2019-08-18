<?php
use yii\helpers\Url;
?>
<section class="product-listing">
  <div class="container">
    <div class="row">
      <div class="col col-sm-12">
        <div class="hostest-product-title text-center">
          <img src="/images/text-hot-games.png" alt="">
        </div>
      </div>
    </div>
    <div class="row">
      <?php foreach ($games as $game) :?>
      <div class="col col-lg-20-per col-sm-12 prod-item">
        <a class="prod-img" href="<?=Url::to(['game/view', 'id' => $game->id, 'slug' => $game->slug]);?>">
        <img src="<?=$game->getImageUrl('300x300');?>" alt="">
        </a>
        <a class="prod-title" href="<?=Url::to(['game/view', 'id' => $game->id, 'slug' => $game->slug]);?>"><?=$game->title;?></a>
        <div class="prod-price">
          <?php if ($game->isSoldout()) : ?>
          <span style="color:#ffdd00; font-size:11px;">OUT OF STOCK. COMING BACK SOON!</span>
          <?php else :?>
          <span><?=number_format($game->pack);?></span> <?=$game->unit_name;?>
          <div class="price-usd">Only <span>$<?=number_format($game->price);?></span></div>
          <?php endif;?>
        </div>
        <?php 
        $gameId = $game->id;
        $unit = $game->pack;
        $gamePromotions = array_filter($promotions, function($promotion) use ($gameId) {
          return $promotion->canApplyForGame($gameId);
        });
        usort($gamePromotions, function($p1, $p2) use ($unit) {
          return ($p1->apply($unit) < $p2->apply($unit)) ? 1 : -1;
        });
        $gamePromotion = reset($gamePromotions);
        if ($gamePromotion) :
          $benefit = $gamePromotion->getBenefit();
        ?>
        <div class="prod-code">
          <div class="prod-code-left">
            <p>Nhập mã</p>
            <p><?=$gamePromotion->code;?></p>
          </div>
          <div class="prod-code-right">
            <p>+<?=$gamePromotion->apply($game->pack);?> GEMS</p>
            <p>cho HFKEJK</p>
          </div>
        </div>
        <?php endif;?>
      </div>
      <?php endforeach;?>
    </div>
    <div class="prod-listing-viewmore">
      <a href="<?=Url::to(['game/index']);?>" class="main-btn">See More</a>
      <!-- <a href="#" class="main-btn" data-toggle="modal" data-target="#joinModal">join now</a> -->
    </div>
  </div>
</section>
<div class="modal" id="topupModal">
  <div class="modal-dialog">
      <div class="modal-content">
          <div class="modal-wrap">
              <button type="button" class="close" data-dismiss="modal">×</button>
              <!-- <img src="images/close-icon.png" alt=""> -->
              <a href="">
                  <img class="btn-modal-topup" src="/images/btn-topup-now.png" alt="">
              </a>
              <img class="bg-modal" src="/images/bg-popup-topup-now.png" alt="">
          </div>

      </div>
  </div>
</div>
<div class="modal" id="joinModal">
  <div class="modal-dialog">
      <div class="modal-content">
          <div class="modal-wrap">
              <button type="button" class="close" data-dismiss="modal">×</button>
              <!-- <img src="images/close-icon.png" alt=""> -->
              <a href="">
                  <img class="btn-modal-join" src="/images/btn-join-now.png" alt="">
              </a>
              <img class="bg-modal" src="/images/bg-popup-join-now.png" alt="">
          </div>

      </div>
  </div>
</div>
<div class="modal" id="walletModal">
  <div class="modal-dialog">
      <div class="modal-content">
          <div class="modal-wrap">
              <button type="button" class="close" data-dismiss="modal">×</button>
              <!-- <img src="images/close-icon.png" alt=""> -->
              <a href="">
                  <img class="btn-modal-wallet" src="/images/btn-wallet-now.png" alt="">
              </a>
              <img class="bg-modal" src="/images/bg-popup-wallet-now.png" alt="">
          </div>
      </div>
  </div>
</div>