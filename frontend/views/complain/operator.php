<?php
use yii\helpers\Url;
use common\components\helpers\TimeElapsed;
use frontend\widgets\LinkPager;
?>
<main>
  <section class="section-module">
    <div class="container">
      <div class="heading-group">
        <h1 class="sec-title"><?=$operator->name;?></h1><a class="btn btn-primary trans" href="<?=Url::to(['operator/view', 'id' => $operator->id, 'slug' => $operator->slug]);?>">BACK TO operators<i class="fas fa-chevron-right"></i></a>
      </div>
      <div class="sec-content">
        <div class="mod-column">
          <div class="row">

            <?php foreach ($complains as $complain) : ?>
            <div class="col-12 col-sm-6 col-md-6 col-lg-4 col-xl-3">
              <div class="block-complaint">
                <div class="complaint-image"><img src="/img/complain/<?=$complain->status;?>.jpg" alt="image"></div>
                <div class="complaint-heading">
                  <p class="complaint-ttl"><?=strtoupper($complain->status);?> CASE</p>
                  <p><?=TimeElapsed::timeElapsed($complain->created_at);?></p>
                </div>
                <div class="complaint-desc"><?=$complain->title;?></div><a class="btn btn-primary" href="<?=Url::to(['complain/view', 'id' => $complain->id]);?>">READ MORE</a>
              </div>
            </div>
            <?php endforeach;?>
          </div>
          <div class="pagination-wrap">
            <?=LinkPager::widget([
              'pagination' => $pages, 
              'maxButtonCount' => 1, 
              'hideOnSinglePage' => false,
              'linkOptions' => ['class' => 'page-link'],
              'pageCssClass' => 'page-item',
            ]);?>
          </div>
        </div>
        <aside class="mod-sidebar">
          <div class="sidebar-col"><a class="btn btn-primary" href="<?=Url::to(['complain/create']);?>">WRITE A COMPLAINT</a></div>
          <div class="sidebar-category sidebar-col">
            <?=\frontend\widgets\ComplainByReasonWidget::widget();?>
          </div>
          <div class="sidebar-delineation"><a class="trans" href="#"><img src="../img/operators/img_01.jpg" alt="image"></a></div>
        </aside>
      </div>
    </div>
  </section>
</main>