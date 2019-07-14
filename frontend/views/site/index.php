<?php
use yii\helpers\Url;

$this->title = 'Home Page';
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
        <a class="prod-img" href="<?=Url::to(['game/view', 'id' => $game->id]);?>">
        <img src="<?=$game->getImageUrl('300x300');?>" alt="">
        </a>
        <a class="prod-title" href="#"><?=$game->title;?></a>
        <div class="prod-price">
          <span><?=number_format($game->pack);?> GEMS</span> for COC
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
    </div>
  </div>
</section>