<main>
  <section class="section-news-detail">
    <div class="container">
      <div class="sec-content">
        <div class="mod-column">
          <div class="widget-box mb-5">
            <div class="news-article p-3 p-md-5">
              <?=Yii::t('app', 'Corporate');?>
            </div>
          </div>
        </div>
        <div class="mod-sidebar">
          <div class="sidebar-col">
          	<?=\frontend\widgets\TopOperatorWidget::widget();?>
          </div>
          <?=\frontend\widgets\AdsWidget::widget(['position' => \frontend\models\Ads::POSITION_SIDEBAR]);?>
        </div>
      </div>
    </div>
  </section>
</main>