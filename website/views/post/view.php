<?php 
use yii\helpers\Url;
$this->registerMetaTag(['property' => 'og:image', 'content' => $model->getImageUrl('150x150')], 'og:image');
$this->registerMetaTag(['property' => 'og:title', 'content' => $model->getMetaTitle()], 'og:title');
$this->registerMetaTag(['property' => 'og:description', 'content' => $model->getMetaDescription()], 'og:description');
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
      <div class="mb-2">
        <div class="w-100 mt-auto">
          <?php foreach ($model->categories as $category) : ?>
          <?php 
            $backgrouds = array_rand($cateColors, 1);
            $backgroud = $cateColors[$backgrouds];
            ?>
          <a href="<?=Url::to(['post/category', 'id' => $category->id, 'slug' => $category->slug]);?>" class="badge <?=$backgroud;?> mb-2"><i class="fas fa-circle me-2 small fw-bold"></i><?=$category->name;?></a>
          <?php endforeach;?>
          <!-- Card category -->
        </div>
      </div>
      <div class="row gy-4">
        <!-- Card item START -->
        <div class="col-sm-12">
          <div class="card">
            <!-- Card img -->
            <h1 class="post-title"><?=$model->title;?></h1>
            <div class="post-description"><?=$model->excerpt;?></div>
            <!-- Card info -->
            <ul class="nav nav-divider align-items-center mb-3 py-2">
              <li class="nav-item">
                <span class="ms-3">By <a href="#" class="stretched-link text-reset btn-link"><?=sprintf('%s - Kinggems.us', $model->getCreatorName());?></a></span>
              </li>
              <li class="nav-item"><?=$model->getCreatedAt(true, 'F j, Y');?></li>
            </ul>
            <!-- table of contents -->
            <?php if ($model->table_index) : ?>
            <div class="section-nav-wrapper">
              <nav class="section-nav">
                <div class="tableofcontents" data-toggle="collapse" data-target="#section-collapse"><img style="margin-right: 10px;" width="20" src="/images/icon/content.png"/>Mục lục</div>
                <div id="section-collapse" class="show">
                  <?=$model->table_index;?>
                </div>
              </nav>
            </div>
            <?php endif;?>
            <!-- end table of contents -->
            <div class="card-body px-0 pt-3">
              <div class="post-content">
                <?=$model->content;?>
              </div>

              <!-- Rating Stars Box -->
              <?=\website\widgets\PostRatingWidget::widget(['post_id' => $model->id]);?>
              
              <hr/>
              <?=\website\widgets\PostSocial::widget(['post_id' => $model->id, 'view_count' => $model->view_count]);?>
            </div>
          </div>
        </div>
        <!-- Card item END -->
        
      </div>
      <?=\website\widgets\RelatedPost::widget(['post_id' => $model->id]);?>
      <?=\website\widgets\PostCommentWidget::widget(['post_id' => $model->id]);?>
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

<?php
$script = <<< JS
$("#section-collapse").find("li:has(> ul,ol)").each(function(index){
  let id = 'section-collapse-item' + index;
  let control = '<span data-toggle="collapse" data-target="#'+id+'" class="panel-heading"></span>';
  $(this).find('ul,ol').attr('id', id).addClass('show');
  $(this).prepend(control);
})
JS;
$this->registerJs($script);
?>