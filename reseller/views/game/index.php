<?php
use reseller\components\helpers\Url;
use yii\widgets\LinkPager;
use common\components\helpers\FormatConverter;

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
            <div class="shop-search-paging t-wrap-btn is-desktop">
              <?=LinkPager::widget(['pagination' => $pages, 'maxButtonCount' => 1, 'hideOnSinglePage' => false]);?>
            </div>
            <div class="shop-search-filter">
              <select name="sort" id="fitler">
                <option value="">FILTER</option>
                <option value="asc" <?=($sort == 'asc') ? "selected" : "";?> >A - Z</option>
                <option value="desc" <?=($sort == 'desc') ? "selected" : "";?> >Z - A</option>
              </select>
            </div>
            <div class="shop-search-paging is-mobile">
              <?=LinkPager::widget(['pagination' => $pages, 'maxButtonCount' => 1, 'hideOnSinglePage' => false]);?>
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
			      <div class="col-6 col-lg-20-per col-md-4 prod-item">
			        <a class="prod-img" href="<?=Url::to(['game/view', 'id' => $model->id, 'slug' => $model->slug]);?>">
			        <img src="<?=$model->getImageUrl('300x300');?>" alt="">
              <?php if (Yii::$app->settings->get('EventForm', 'status')) : ?>
			        <img src="<?=Yii::$app->settings->get('EventForm', 'image');?>" style="position: absolute; right: 15px; top: 0; width: 25%;" alt="">
              <?php endif;?>
			        </a>
			        <a class="prod-title" href="<?=Url::to(['game/view', 'id' => $model->id, 'slug' => $model->slug]);?>"><?=$model->title;?></a>
			        <div class="prod-price">
                <?php if ($model->isSoldout()) : ?>
                <span style="color:#ffdd00; font-size:11px;">OUT OF STOCK. COMING BACK SOON!</span>
                <?php else :?>
                <span><?=number_format($model->pack);?></span> <?=$model->unit_name;?>
                <div class="price-usd">Only <span>$<?=number_format($model->getPrice(), 1);?></span><span class="price-cny">/ CNY <?=FormatConverter::convertCurrencyToCny($model->getPrice(), 1);?></span></div>
                <?php endif;?>
			        </div>
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