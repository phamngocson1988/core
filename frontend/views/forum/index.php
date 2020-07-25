<?php 
use yii\helpers\Url;
use common\components\helpers\TimeElapsed;
?>
<main>
  <div class="forum-container container">
    <div class="forum-main">
      <?=\frontend\widgets\ForumTopContributorWidget::widget();?>
      <section class="section-forum-heading">
        <h1 class="heading-title">Forums</h1>
        <div class="heading-button"><a class="btn btn-primary" href="<?=Url::to(['forum/create']);?>">Start new topic</a></div>
      </section>
      <section class="section-forums">
        <?php foreach ($sections as $section) : ?>
        <?=\frontend\widgets\ForumOverviewWidget::widget(['section' => $section]);?>
        <?php endforeach;?>
      </section>
    </div>
    <?=\frontend\widgets\ForumSidebarWidget::widget();?>
  </div>
</main>
<?php 
$script = <<< JS
JS;
$this->registerJs($script);
?>
