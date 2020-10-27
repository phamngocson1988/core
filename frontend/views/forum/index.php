<?php 
use yii\helpers\Url;
use common\components\helpers\TimeElapsed;
?>
<main>
  <div class="forum-container container">
    <div class="forum-main">
      <?=\frontend\widgets\ForumTopContributorWidget::widget();?>
      <section class="section-forum-heading">
        <h1 class="heading-title"><?=Yii::t('app', 'Forums');?></h1>
        <?php if (!Yii::$app->user->isGuest) : ?>
        <div class="heading-button"><a class="btn btn-primary" href="<?=Url::to(['forum/create']);?>"><?=Yii::t('app', 'Start new topic');?></a></div>
        <?php endif;?>
      </section>
      <section class="section-forums">
        <?php foreach ($sections as $section) : ?>
        <?=\frontend\widgets\ForumOverviewWidget::widget(['section' => $section]);?>
        <?php endforeach;?>
        <?=\frontend\widgets\WhoOnlineWidget::widget();?>
      </section>
    </div>
    <aside class="forum-sidebar">
    <?=\frontend\widgets\ForumSidebarWidget::widget();?>
    <?=\frontend\widgets\AdsWidget::widget(['position' => \frontend\models\Ads::POSITION_SIDEBAR]);?>
    </aside>
  </div>
</main>
<?php 
$script = <<< JS
JS;
$this->registerJs($script);
?>
