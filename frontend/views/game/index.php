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
          <form method="GET" autocomplete='off'>
            <div class="shop-search-keyword">
              <input type="text" placeholder="Search" name="q" value="<?=$q;?>">
              <input type="submit" value="">
            </div>
            <div class="shop-search-paging">
              <?=LinkPager::widget(['pagination' => $pages, 'maxButtonCount' => 1, 'hideOnSinglePage' => false]);?>
            </div>
            <div class="shop-search-filter">
              <select name="sort" id="fitler">
                <option value="">FILTER</option>
                <option value="asc" <?=($sort == 'asc') ? "selected" : "";?> >A - Z</option>
                <option value="desc" <?=($sort == 'desc') ? "selected" : "";?> >Z - A</option>
              </select>
            </div>
          </form>
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
			        <a class="prod-img" href="<?=Url::to(['game/view', 'id' => $model->id, 'slug' => $model->slug]);?>">
			        <img src="<?=$model->getImageUrl('300x300');?>" alt="">
			        </a>
			        <a class="prod-title" href="#"><?=$model->title;?></a>
			        <div class="prod-price">
			          <span><?=number_format($model->pack);?> <?=strtoupper($model->unit_name);?></span> for <?=$model->getShortTitle();?>
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
<?php
$script = <<< JS
$('#fitler').on('change', function(){
  $(this).closest('form').submit();
});
JS;
$this->registerJs($script);
?>