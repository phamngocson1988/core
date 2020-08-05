<?php
use yii\helpers\Url; 
use common\components\helpers\TimeElapsed;
?>
<main>
  <div class="section-keyvisual">
    <div class="container">
      <?=\frontend\widgets\AdsWidget::widget(['position' => \frontend\models\Ads::POSITION_TOPHOME]);?>
      <?=\frontend\widgets\NewsBannerWidget::widget();?>
    </div>
  </div>
  <section class="section-newest">
    <div class="container">
      <div class="heading-group">
        <h2 class="sec-title"><?=Yii::t('app', 'newest_operator');?></h2><a class="btn btn-primary trans" href="<?=Url::to(['operator/index']);?>"><?=Yii::t('app', 'see_all_newest_operator');?><i class="fas fa-chevron-right"></i></a>
      </div>
      <div class="newest-slider js-newest-slider">
        <?php foreach ($newestOperators as $operator) :?>
        <div class="newest-item">
          <div class="block-card">
            <div class="card-image"><img class="object-fit" src="<?=$operator->getImageUrl('400x220');?>" alt="image"></div>
            <div class="card-body">
              <div class="star-rating-group">
                <div class="star-rating"><span style="width:<?=$operator->averageReviewPercent();?>%"></span></div><span class="star-rating-text"><?=number_format($operator->averageStar(), 1);?></span>
              </div>
              <h3 class="card-title"><?=$operator->name;?></h3>
              <p class="card-desc">
                <?=$operator->product;?>
              </p><a class="btn btn-primary" href="<?=Url::to(['operator/view', 'id' => $operator->id, 'slug' => $operator->slug]);?>">JOIN NOW</a>
            </div>
          </div>
        </div>
        <?php endforeach;?>
      </div>
    </div>
  </section>
  <section class="section-latest-bonuses">
    <div class="container">
      <div class="heading-group">
        <h2 class="sec-title"><?=Yii::t('app', 'lastest_bonus');?></h2><a class="btn btn-primary trans" href="<?=Url::to(['bonus/index']);?>"><?=Yii::t('app', 'see_all_lastest_bonus');?><i class="fas fa-chevron-right"></i></a>
      </div>
      <div class="row">
        <?php foreach ($lastestBonuses as $bonus) : ?>
        <div class="col-12 col-sm-6 col-md-4 col-lg-3 col-lrg-2">
          <div class="block-bonuses js-bonuses">
            <div class="bonuses-front">
              <div class="bonuses-icon fas fa-exclamation-circle js-exclamation"></div>
              <div class="bonuses-image"><img class="object-fit" src="<?=$bonus->getImageUrl('400x220');?>" alt="image"></div>
              <div class="bonuses-body">
                <h3 class="bonuses-title"><?=$bonus->title;?></h3>
                <p class="bonuses-desc"><?=$bonus->getType();?></p>
              </div><a class="btn btn-primary" href="<?=Url::to(['bonus/view', 'id' => $bonus->id]);?>"><?=Yii::t('app', 'get_bonus');?></a>
            </div>
            <div class="bonuses-back">
              <div class="bonuses-icon fas fa-close js-close"></div>
              <div class="bonuses-body">
                <h3 class="bonuses-title"><?=$bonus->title;?></h3>
                <p class="bonuses-desc">
                  Type: <?=$bonus->getType();?><br>
                  Bonus Value: $150<br>
                  Minimum Deposit: <?=$bonus->minimum_deposit;?><br>
                  Wagering Requirement: <?=$bonus->wagering_requirement;?>
                </p>
              </div><a class="btn btn-primary" href="<?=Url::to(['bonus/view', 'id' => $bonus->id]);?>"><?=Yii::t('app', 'get_bonus');?></a>
            </div>
          </div>
        </div>
        <?php endforeach;?>
      </div>
    </div>
  </section>
  <section class="section-latest-complaints">
    <div class="container">
      <div class="heading-group">
        <h2 class="sec-title">LATEST COMPLAINTS</h2><a class="btn btn-primary trans" href="#">SEE ALL LATEST COMPLAINTS<i class="fas fa-chevron-right"></i></a>
      </div>
      <div class="row">
        <div class="col-md-12 col-lg-9 col-lrg-8">
          <div class="row">
            <?php foreach ($lastestComplains as $complain) : ?>
            <div class="col-12 col-sm-6 col-md-4 col-lg-4 col-xl-3">
              <div class="block-complaint">
                <div class="complaint-image"><img src="<?=$complain->getIcon();?>" alt="image"></div>
                <div class="complaint-heading">
                  <p class="complaint-ttl"><?=strtoupper($complain->status);?> CASE</p>
                  <p><?=TimeElapsed::timeElapsed($complain->created_at);?></p>
                </div>
                <div class="complaint-desc"><?=sprintf("%s - %s", $complain->operator->name, $complain->reason->title);?></div><a class="btn btn-primary" href="<?=Url::to(['complain/view', 'id' => $complain->id]);?>">READ MORE</a>
              </div>
            </div>
            <?php endforeach;?>
          </div>
        </div>
        <div class="col-md-12 col-lg-3 col-lrg-2">
          <?=\frontend\widgets\ComplainByOperatorWidget::widget();?>
        </div>
      </div>
    </div>
  </section>
</main>