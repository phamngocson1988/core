<?php 
use website\widgets\LinkPager;
use yii\helpers\Url;
$this->registerMetaTag(['property' => 'og:image', 'content' => $category->getImageUrl('150x150')], 'og:image');
$this->registerMetaTag(['property' => 'og:title', 'content' => $category->getMetaTitle()], 'og:title');
$this->registerMetaTag(['property' => 'og:description', 'content' => $category->getMetaDescription()], 'og:description');
$cateColors = ['bg-primary', 'bg-danger', 'bg-warning', 'bg-info'];
?>
<?=\website\widgets\PostBannerHeader::widget();?>
<!--Main content START -->
<section class="position-relative page-news">
  <div class="container" data-sticky-container="">
  <div class="row">
    <!-- Main Post START -->
    <div class="col-lg-9">
      <!-- Title -->
      <div class="mb-4">
        <h2 class="m-0"></i>Today's top highlights</h2>
        <p>Latest breaking news, pictures, videos, and special reports</p>
      </div>
      <div class="row gy-4">
          <?php foreach ($models as $model) : ?>
            <!-- Card item START -->
            <div class="col-sm-6">
                <div class="card">
                    <!-- Card img -->
                    <div class="position-relative post-image-container-overflow">
                        <img class="card-img" src="<?=$model->getImageUrl(null, '/images/thumb-demo.jpg');?>" alt="<?=$model->title;?>">
                        <div class="card-img-overlay d-flex align-items-start flex-column p-3">
                            <!-- Card overlay bottom -->
                            <div class="w-100 mt-auto">
                            <!-- Card category -->
                            <?php foreach ($model->categories as $category) : ?>
                            <?php 
                                $backgrouds = array_rand($cateColors, 1);
                                $backgroud = $cateColors[$backgrouds];
                            ?>
                            <a href="<?=Url::to(['post/category', 'id' => $category->id, 'slug' => $category->slug]);?>" class="badge <?=$backgroud;?> mb-2"><i class="fas fa-circle me-2 small fw-bold"></i><?=$category->name;?></a>
                            <?php endforeach;?>
                            </div>
                        </div>
                    </div>
                    <div class="card-body px-0 pt-3">
                    <h4 class="card-title"><a href="<?=Url::to(['post/view', 'id' => $model->id, 'slug' => $model->slug]);?>" class="btn-link text-reset fw-bold"><?=$model->title;?></a></h4>
                    <p class="card-text"><?=$model->getExcerpt();?></p>
                    <!-- Card info -->
                    <ul class="nav nav-divider align-items-center d-none d-sm-inline-block">
                        <li class="nav-item">
                        <span class="ms-3">By <a href="javascript:;" class="stretched-link text-reset btn-link"><?=$model->getCreatorName();?></a></span>
                        </li>
                        <li class="nav-item"><?=$model->getCreatedAt(true, 'F j, Y');?></li>
                    </ul>
                    </div>
                </div>
            </div>
            <!-- Card item END -->
          <?php endforeach;?>
        <!-- Load more START -->
        <!-- <div class="col-12 text-center mt-5">
          <button type="button" class="btn btn-primary-soft">Load more post  <img src="/images/icon/more.svg"/></button>
        </div> -->
        <div class="col-12 text-center mt-5">
            <nav aria-label="Page navigation" class="mt-2 mb-5">
                <?=LinkPager::widget(['pagination' => $pages]);?>
            </nav>
        </div>

        <!-- Load more END -->
      </div>
    </div>
    <!-- Main Post END -->
    <!-- Sidebar START -->
    <div class="col-lg-3 mt-5 mt-lg-0">
      <?=\website\widgets\PostCategorySidebar::widget();?>
      <?=\website\widgets\HotNewsSidebar::widget();?>
    </div>
    <!-- Row end -->
  </div>
</section>