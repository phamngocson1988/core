<?php
use yii\helpers\Url;
use frontend\widgets\LinkPager;
use common\components\helpers\TimeElapsed;
?>
<main>
  <section class="section-module">
    <div class="container">
      <div class="heading-group">
        <h2 class="sec-title">COMPLAINTS</h2>
        <?php if (!Yii::$app->user->isGuest) : ?>
        <a class="btn btn-primary trans" href="<?=Url::to(['complain/create']);?>"><?=Yii::t('app', 'leave_complain');?><i class="fas fa-chevron-right"></i></a>
        <?php endif;?>
      </div>
      <div class="row">
        <div class="col-md-12 col-lg-12 col-lrg-12">
          <div class="row">
            <?php foreach ($complains as $complain) : ?>
            <div class="col-12 col-sm-6 col-md-4 col-lg-4 col-xl-3">
              <div class="block-complaint">
                <div class="complaint-image"><img src="<?=$complain->getIcon();?>" alt="image"></div>
                <div class="complaint-heading">
                  <p class="complaint-ttl"><?=strtoupper($complain->status);?> CASE</p>
                  <p><?=TimeElapsed::timeElapsed($complain->created_at);?></p>
                </div>
                <div class="complaint-desc"><?=sprintf("%s - %s", $complain->operator->name, $complain->reason->title);?></div><a class="btn btn-primary" href="<?=Url::to(['complain/view', 'id' => $complain->id, 'slug' => $complain->slug]);?>">READ MORE</a>
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
      </div>
    </div>
  </section>
</main>
<?php 
$script = <<< JS
$("[name=bonus_type]").on('change', function(){
  $(this).closest('form').submit();
});
$("#commonSearchForm input").on('blur', function() {
  $(this).closest('form').submit();
})
JS;
$this->registerJs($script);
?>
