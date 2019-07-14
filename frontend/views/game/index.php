<?php
use yii\helpers\Url;
use yii\widgets\LinkPager;

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
          <form method="GET">
            <input type="text" placeholder="Search" name="q" value="<?=$q;?>">
            <input type="submit" value="">
          </form>
          <?php
          $current_page = $pages->getPage() + 1;
          $total_page = $pages->getPageCount();
          $next_page = $current_page + 1; 
          $prev_page = $current_page - 1; 
          $link_next_page = ($next_page > $total_page) ? 'javascript:void;' : Url::current([$pages->pageParam => $next_page]);
          $link_prev_page = ($prev_page < 1) ? 'javascript:void;' : Url::current([$pages->pageParam => $prev_page]);
          ?>
          <div class="shop-search-paging">
            <a href="<?=$link_prev_page;?>"><i class="fas fa-caret-left"></i></a>
            <a class="current-page" href="javascript:void;"><?=$current_page;?></a>
            <a href="<?=$link_next_page;?>"><i class="fas fa-caret-right"></i></a>
            <span>of <?=$total_page;?></span>
          </div>
          <div class="shop-search-filter">
            <select name="sort">
              <option value="">FILTER</option>
              <option value="asc"><a href="<?=Url::current(['sort' => 'asc']);?>">A - Z</a></option>
              <option value="desc"><a href="<?=Url::current(['sort' => 'desc']);?>">Z - A</a></option>
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
			            <p>+<?=$gamePromotion->apply($model->pack);?> GEMS</p>
			            <p>cho HFKEJK</p>
			          </div>
			        </div>
			        <?php endif;?>
			      </div>
			      <?php endforeach;?>




            
          </div>
        </div>
      </div>
    </div>
  </div>
</section>