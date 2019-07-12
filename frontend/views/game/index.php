<?php
use yii\helpers\Url;

$this->title = 'Shop';
?>
<section class="page-title">
  <div class="container">
    <div class="row">
      <div class="col col-sm-12">
        <div class="page-title-content text-center">
          <img src="/images/text-shop.png" alt="">
        </div>
      </div>
    </div>
  </div>
</section>
<section class="shop-search">
  <div class="container">
    <div class="row">
      <div class="col col-sm-12">
        <div class="shop-search-box">
          <form action="#" class="">
            <input type="text" placeholder="Search">
            <input type="submit" value="">
          </form>
          <div class="shop-search-paging">
            <a href="#"><i class="fas fa-caret-left"></i></a>
            <a class="current-page" href="#">2</a>
            <a href="#"><i class="fas fa-caret-right"></i></a>
            <span>of 10</span>
          </div>
          <div class="shop-search-filter">
            <select name="" id="">
              <option value="">FILTER</option>
              <option value="">A - Z</option>
              <option value="">Z - A</option>
            </select>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<section class="shop-page">
  <div class="container">
    <div class="row">
      <div class="col col-lg-12 col-md-12 col-sm-12 col-12">
        <div class="product-listing">
          <div class="row">
            <?php foreach ($models as $model) :?>
			      <div class="col col-lg-20-per col-sm-12 prod-item">
			        <a class="prod-img" href="<?=Url::to(['game/view', 'id' => $model->id]);?>">
			        <img src="<?=$model->getImageUrl('300x300');?>" alt="">
			        </a>
			        <a class="prod-title" href="#"><?=$model->title;?></a>
			        <div class="prod-price">
			          <span><?=number_format($model->pack);?> GEMS</span> for COC
			        </div>
			        <?php 
			        $gameId = $model->id;
			        $unit = $model->pack;
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




            <div class="col col-lg-20-per col-sm-12 prod-item">
              <a class="prod-img" href="#">
              <img src="/uploads/game1.jpg" alt="">
              </a>
              <a class="prod-title" href="#">PlayerUnknown's Battlegrounds Name...</a>
              <div class="prod-price">
                <span>14,000 GEMS</span> for COC
              </div>
              <div class="prod-code">
                <div class="prod-code-left">
                  <p>Nhập mã</p>
                  <p>5TS8798</p>
                </div>
                <div class="prod-code-right">
                  <p>-80 GEMS</p>
                  <p>cho HFKEJK</p>
                </div>
              </div>
            </div>
            <div class="col col-lg-20-per col-sm-12 prod-item">
              <a class="prod-img" href="#">
              <img src="/uploads/game2.jpg" alt="">
              </a>
              <a class="prod-title" href="#">PlayerUnknown's Battlegrounds Name...</a>
              <div class="prod-price">
                <span>14,000 GEMS</span> for COC
              </div>
              <div class="prod-code">
                <div class="prod-code-left">
                  <p>Nhập mã</p>
                  <p>5TS8798</p>
                </div>
                <div class="prod-code-right">
                  <p>-80 GEMS</p>
                  <p>cho HFKEJK</p>
                </div>
              </div>
            </div>
            <div class="col col-lg-20-per col-sm-12 prod-item">
              <a class="prod-img" href="#">
              <img src="/uploads/game3.jpg" alt="">
              </a>
              <a class="prod-title" href="#">PlayerUnknown's Battlegrounds Name...</a>
              <div class="prod-price">
                <span>14,000 GEMS</span> for COC
              </div>
              <div class="prod-code">
                <div class="prod-code-left">
                  <p>Nhập mã</p>
                  <p>5TS8798</p>
                </div>
                <div class="prod-code-right">
                  <p>-80 GEMS</p>
                  <p>cho HFKEJK</p>
                </div>
              </div>
            </div>
            <div class="col col-lg-20-per col-sm-12 prod-item">
              <a class="prod-img" href="#">
              <img src="/uploads/game4.jpg" alt="">
              </a>
              <a class="prod-title" href="#">PlayerUnknown's Battlegrounds Name...</a>
              <div class="prod-price">
                <span>14,000 GEMS</span> for COC
              </div>
              <div class="prod-code">
                <div class="prod-code-left">
                  <p>Nhập mã</p>
                  <p>5TS8798</p>
                </div>
                <div class="prod-code-right">
                  <p>-80 GEMS</p>
                  <p>cho HFKEJK</p>
                </div>
              </div>
            </div>
            
          </div>
        </div>
      </div>
    </div>
  </div>
</section>