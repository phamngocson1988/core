<main>
  <section class="section-news-detail">
    <div class="container">
      <div class="sec-content">
        <div class="mod-column">
          <div class="widget-box mb-5">
            <div class="sec-heading"><img class="object-fit" src="<?=$post->getImageUrl('1000x330');?>" alt="image">
              <div class="heading-info text-center">
                <h1 class="heading-title"><?=$post->title;?></h1>
                <div class="heading-name">
                  <div class="heading-avatar"><img src="<?=$post->operator->getImageUrl('50x50');?>" alt="image"></div>
                  <div class="heading-text"><?=$post->operator->name;?> * <?=date('F j, Y', strtotime($post->created_at));?></div>
                </div>
              </div>
            </div>
            <div class="news-article p-3 p-md-5">
              <?=$post->content;?>
            </div>
          </div>
          <?=\frontend\widgets\NewsByOperatorWidget::widget(['operator' => $post->operator]);?>
        </div>
        <div class="mod-sidebar">
          <div class="sidebar-col">
          	<?=\frontend\widgets\TopOperatorWidget::widget();?>
          </div>
          <div class="sidebar-delineation"><a class="trans" href="#"><img src="../img/operators/img_01.jpg" alt="image"></a></div>
        </div>
      </div>
    </div>
  </section>
</main>