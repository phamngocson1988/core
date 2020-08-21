<?php
use yii\helpers\Html;
use yii\helpers\Url;
$setting = Yii::$app->settings;
$affBanner = $setting->get('ApplicationSettingForm', 'affiliate_banner');
$affBannerMobile = $setting->get('ApplicationSettingForm', 'affiliate_banner_mobile', $affBanner);
$affBannerLink = $setting->get('ApplicationSettingForm', 'affiliate_banner_link', 'javascript:;');
$referBanner = $setting->get('ApplicationSettingForm', 'refer_banner');
$referBannerMobile = $setting->get('ApplicationSettingForm', 'refer_banner_mobile');
$referBannerLink = $setting->get('ApplicationSettingForm', 'refer_banner_link', 'javascript:;');
$gallery = [
    [
        'title' => $setting->get('GallerySettingForm', 'title1'),
        'content' => $setting->get('GallerySettingForm', 'content1'),
        'link' => $setting->get('GallerySettingForm', 'link1'),
        'href' => $setting->get('GallerySettingForm', 'href1'),
    ],
    [
        'title' => $setting->get('GallerySettingForm', 'title2'),
        'content' => $setting->get('GallerySettingForm', 'content2'),
        'link' => $setting->get('GallerySettingForm', 'link2'),
        'href' => $setting->get('GallerySettingForm', 'href2'),
    ],
    [
        'title' => $setting->get('GallerySettingForm', 'title3'),
        'content' => $setting->get('GallerySettingForm', 'content3'),
        'link' => $setting->get('GallerySettingForm', 'link3'),
        'href' => $setting->get('GallerySettingForm', 'href3'),
    ],
    [
        'title' => $setting->get('GallerySettingForm', 'title4'),
        'content' => $setting->get('GallerySettingForm', 'content4'),
        'link' => $setting->get('GallerySettingForm', 'link4'),
        'href' => $setting->get('GallerySettingForm', 'href4'),
    ],
    [
        'title' => $setting->get('GallerySettingForm', 'title5'),
        'content' => $setting->get('GallerySettingForm', 'content5'),
        'link' => $setting->get('GallerySettingForm', 'link5'),
        'href' => $setting->get('GallerySettingForm', 'href5'),
    ],
    [
        'title' => $setting->get('GallerySettingForm', 'title6'),
        'content' => $setting->get('GallerySettingForm', 'content6'),
        'link' => $setting->get('GallerySettingForm', 'link6'),
        'href' => $setting->get('GallerySettingForm', 'href6'),
    ],
    [
        'title' => $setting->get('GallerySettingForm', 'title7'),
        'content' => $setting->get('GallerySettingForm', 'content7'),
        'link' => $setting->get('GallerySettingForm', 'link7'),
        'href' => $setting->get('GallerySettingForm', 'href7'),
    ],
    [
        'title' => $setting->get('GallerySettingForm', 'title8'),
        'content' => $setting->get('GallerySettingForm', 'content8'),
        'link' => $setting->get('GallerySettingForm', 'link8'),
        'href' => $setting->get('GallerySettingForm', 'href8'),
    ]
];
$gallery = array_filter($gallery, function($data) {
    return $data['link'];
});
$sideGallery = array_slice($gallery, -2);
?>
<!-- Main jumbotron for a primary marketing message or call to action -->
<div class="jumbotron">
  <div class="container">
    <div class="row d-flex justify-content-between align-items-center">
      <div class="col-sm-8 flex-fill">
        <div class="main-slider" data-aos="zoom-in" data-aos-duration="500">
          <?php foreach ($gallery as $data) : ?>
            <a class="hover-img" href="<?=$data['href'];?>">
            <img src="<?=$data['link'];?>" />
            </a>
          <?php endforeach;?>
        </div>
      </div>
      <div class="col-sm-4 flex-fill">
        <div class="main-banner">
          <?php foreach ($sideGallery as $data) : ?>
          <div class="item-banner" data-aos="fade-left" data-aos-duration="800">
            <a class="hover-img" href="<?=$data['href'];?>">
            <img src="<?=$data['link'];?>" />
            </a>
          </div>
          <?php endforeach;?>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="container">
  <div class="card-deck main-card">
    <div class="card py-4 px-4" data-aos="fade-right" data-aos-duration="1000"
      data-aos-anchor-placement="center-bottom">
      <div class="media">
        <div class="media-body align-self-center">
          <h5 class="mt-0 mb-0 text-uppercase text-center">
            <img class="align-self-center mr-3 icon-md" src="/images/icon/credit-card.svg" alt="Fast top-up">
            Fast top-up
          </h5>
        </div>
      </div>
    </div>
    <div class="card py-4 px-4" data-aos="zoom-in" data-aos-duration="1000">
      <div class="media">
        <div class="media-body align-self-center">
          <h5 class="mt-0 mb-0 text-uppercase text-center">
            <img class="align-self-center mr-3 icon-md" src="/images/icon/pig.svg" alt="Cost saving">
            Cost saving
          </h5>
        </div>
      </div>
    </div>
    <div class="card py-4 px-4" data-aos="fade-left" data-aos-duration="1000">
      <div class="media">
        <div class="media-body align-self-center">
          <h5 class="mt-0 mb-0 text-uppercase text-center">
            <img class="align-self-center mr-3 icon-md" src="/images/icon/select.svg" alt="Multiple Choices">
            Multiple Choices
          </h5>
        </div>
      </div>
    </div>
  </div>
</div>
<?php if ($affBanner) : ?>
<div class="container mt-5">
  <div class="card-deck main-card">
    <div class="card" data-aos="zoom-in-down">
      <div class="media">
        <a href="<?=$affBannerLink;?>"><img class="desktop-ads" src="<?=$affBanner;?>"/></a>
        <a href="<?=$affBannerLink;?>"><img class="mobile-ads" src="<?=$affBannerMobile;?>"/></a>
      </div>
    </div>
  </div>
</div>
<?php endif;?>
<?=\website\widgets\FlashsaleWidget::widget();?>
<!-- END container -->
<div class="container mt-5">
  <div class="d-flex justify-content-between align-items-center" data-aos="fade-right" data-aos-duration="500">
    <h2 class="block-title text-uppercase flex-fill">Hot deals</h2>
    <div class="flex-fill">
      <a href="<?=Url::to(['game/hot-deal']);?>" class="link-dark font-weight-bold link-view-all">View all <img class="icon-sm"
        src="/images/icon/next.svg" /></a>
    </div>
  </div>
  <p class="mb-5">Shop our most popular products for this season.</p>
  <div class="post-wrapper post-slider" data-aos="fade-up" data-aos-duration="800">
    <?php foreach ($hotGames as $game) :?>
    <?php $viewUrl = Url::to(['game/view', 'id' => $game->id, 'slug' => $game->slug]);?>
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
        <?php if ($game->event_info) : ?>
        <span class="tag bts"><?=$game->event_info;?></span>
        <?php endif;?>
      </div>
      <div class="post-content">
        <h4 class="post-title">
          <a href="<?=$viewUrl;?>"><?=Html::encode($game->title);?></a>
        </h4>
        <?php if ($game->hasCategory()) : ?>
        <div class="tags">
          <img src="/images/icon/tag.svg" />
          <?php $categories = array_slice($game->categories, 0, 3);?>
          <?php foreach ($categories as $category) : ?>
          <span class="badge badge-primary"><?=$category->name;?></span>
          <?php endforeach; ?>
          <?php if (count($game->categories) > 3) :?>
          <span class="badge badge-primary">...</span>
          <?php endif;?>
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
            <a href="<?=$viewUrl;?>" class="main-btn <?=$game->isSoldout() ? 'disable' : '';?>">
            <span>BUY NOW</span>
            </a>
          </div>
        </div>
      </div>
    </div>
    <?php endforeach;?>
  </div>
  <!-- END POST SLIDER -->
</div>
<!-- END container -->
<?php if ($referBanner) : ?>
<div class="container mt-5">
  <div class="card-deck main-card">
    <div class="card">
      <div class="media">
        <a href="<?=$referBannerLink;?>"><img class="desktop-ads" src="<?=$referBanner;?>"/></a>
        <a href="<?=$referBannerLink;?>"><img class="mobile-ads" src="<?=$referBannerMobile;?>"/></a>
      </div>
    </div>
  </div>
</div>
<?php endif;?>
<div class="container mt-5">
  <div class="row">
    <div class="col-md-6">
      <div class="d-flex justify-content-between align-items-center" data-aos="fade-right" data-aos-duration="500">
        <h2 class="block-title text-uppercase flex-fill">Top Grossing
        </h2>
        <div class="flex-fill">
          <a href="<?=Url::to(['game/top-grossing']);?>" class="link-dark font-weight-bold link-view-all">View all <img class="icon-sm"
            src="/images/icon/next.svg" /></a>
        </div>
      </div>
      <p class="mb-5">Shop our most popular products for this season.</p>
      <div class="post-wrapper top-grossing" data-aos="fade-up" data-aos-duration="800">
        <?php foreach ($grossingGames as $game) : ?>
        <?php $viewUrl = Url::to(['game/view', 'id' => $game->id, 'slug' => $game->slug]);?>
        <div class="post-item card">
          <div class="d-flex">
            <div class="post-thumb">
              <a href="<?=$viewUrl;?>" class="hover-img"><img src="<?=$game->getImageUrl('300x300');?>" /></a>
              <?php if ($game->getSavedPrice()) : ?>
              <span class="tag save">save <?=number_format($game->getSavedPrice());?>%</span>
              <?php endif;?>
              <?php if ($game->promotion_info) : ?>
              <span class="tag promotion"><?=$game->promotion_info;?></span>
              <?php endif;?>
              <?php if ($game->event_info) : ?>
              <span class="tag bts"><?=$game->event_info;?></span>
              <?php endif;?>
            </div>
            <div class="post-content">
              <h4 class="post-title">
                <a href="<?=$viewUrl;?>"><?=$game->title;?></a>
              </h4>
              <?php if ($game->hasCategory()) : ?>
              <div class="tags">
                <img src="/images/icon/tag.svg" />
                <?php $categories = array_slice($game->categories, 0, 3);?>
                <?php foreach ($categories as $category) : ?>
                <span class="badge badge-primary"><?=$category->name;?></span>
                <?php endforeach; ?>
                <?php if (count($game->categories) > 3) :?>
                <span class="badge badge-primary">...</span>
                <?php endif;?>
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
                  <a href="<?=$viewUrl;?>" class="main-btn <?=$game->isSoldout() ? 'disable' : '';?>"><span>BUY NOW</span></a>
                </div>
              </div>
            </div>
          </div>
        </div>
        <?php endforeach;?>
      </div>
      <!-- END POST SLIDER -->
    </div>
    <div class="col-md-6">
      <div class="d-flex justify-content-between align-items-center" data-aos="fade-right" data-aos-duration="500">
        <h2 class="block-title text-uppercase flex-fill">NEW TRENDING
        </h2>
        <div class="flex-fill">
          <a href="<?=Url::to(['game/new-trending']);?>" class="link-dark font-weight-bold link-view-all">View all <img class="icon-sm"
            src="/images/icon/next.svg" /></a>
        </div>
      </div>
      <p class="mb-5">Shop our most popular products for this season.</p>
      <div class="post-wrapper top-grossing" data-aos="fade-up" data-aos-duration="800">
        <?php foreach ($trendGames as $game) : ?>
        <?php $viewUrl = Url::to(['game/view', 'id' => $game->id, 'slug' => $game->slug]);?>
        <div class="post-item card">
          <div class="d-flex">
            <div class="post-thumb">
              <a href="<?=$viewUrl;?>" class="hover-img"><img src="<?=$game->getImageUrl('300x300');?>" /></a>
              <?php if ($game->getSavedPrice()) : ?>
              <span class="tag save">save <?=number_format($game->getSavedPrice());?>%</span>
              <?php endif;?>
              <?php if ($game->promotion_info) : ?>
              <span class="tag promotion"><?=$game->promotion_info;?></span>
              <?php endif;?>
              <?php if ($game->event_info) : ?>
              <span class="tag bts"><?=$game->event_info;?></span>
              <?php endif;?>
            </div>
            <div class="post-content">
              <h4 class="post-title">
                <a href="<?=$viewUrl;?>"><?=$game->title;?></a>
              </h4>
              <?php if ($game->hasCategory()) : ?>
              <div class="tags">
                <img src="/images/icon/tag.svg" />
                <?php $categories = array_slice($game->categories, 0, 3);?>
                <?php foreach ($categories as $category) : ?>
                <span class="badge badge-primary"><?=$category->name;?></span>
                <?php endforeach; ?>
                <?php if (count($game->categories) > 3) :?>
                <span class="badge badge-primary">...</span>
                <?php endif;?>
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
                  <a href="<?=$viewUrl;?>" class="main-btn <?=$game->isSoldout() ? 'disable' : '';?>">
                  <span>BUY NOW</span>
                  </a>
                </div>
              </div>
            </div>
          </div>
        </div>
        <?php endforeach;?>
      </div>
      <!-- END POST SLIDER -->
    </div>
  </div>
</div>
<!-- END container -->
<?php if (count($hotnews)) :?>
<div class="popular-products py-5 mt-5">
  <div class="container">
    <div class="row d-flex justify-content-between align-items-center">
      <div class="col-md-3" data-aos="fade-right" data-aos-duration="500">
        <h4 class="text-uppercase mb-4">what’s hOT</h4>
        <p class="mb-5">Shop our most popular products for this season.</p>
        <a href="#" class="btn">View all</a>
      </div>
      <div class="col-md-9">
        <div class="card-group popular-products-slider" data-aos="fade-left" data-aos-duration="1000">
          <?php foreach ($hotnews as $hotnew) : ?>
          <div class="card">
            <a class="hover-img" href="<?=$hotnew->link;?>" target="_blank">
            <img class="card-img-top" src="<?=$hotnew->getImageUrl('500x500');?>" alt="">
            </a>
            <div class="card-body d-flex justify-content-between align-items-center">
              <p class="mb-0 mr-2 card-text line-clamp-2"><?=$hotnew->title;?>
              </p>
              <a href="<?=$hotnew->link;?>" target="_blank" class="btn">Learn more</a>
            </div>
          </div>
          <?php endforeach;?>
          <!-- End product item -->
        </div>
      </div>
    </div>
  </div>
</div>
<?php endif;?>
<?php
$script = <<< JS
$.fancybox.open({
  src  : '/images/promotion.png',
  type : 'image',
  afterShow: function (instance, current) {
    console.log(instance, current); 
    $(instance.current.image).wrap($("<a />", {
        // set anchor attributes
        href: '#', //or your target link
        target: "_blank" // optional
    }));
  }
});
JS;
$this->registerJs($script);
?>