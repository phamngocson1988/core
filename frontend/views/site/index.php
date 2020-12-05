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
        <h2 class="sec-title"><?=Yii::t('app', 'Newest operator');?></h2><a class="btn btn-primary trans" href="<?=Url::to(['operator/index']);?>"><?=Yii::t('app', 'See all newest operator');?><i class="fas fa-chevron-right"></i></a>
      </div>
      <div class="newest-slider js-newest-slider">
        <?php foreach ($newestOperators as $operator) :?>
        <?php $operatorView = Url::to(['operator/view', 'id' => $operator->id, 'slug' => $operator->slug]);?>
        <div class="newest-item">
          <div class="block-card">
            <a href="<?=$operatorView;?>"><div class="card-image"><img class="object-fit" src="<?=$operator->getImageUrl('400x220');?>" alt="image"></div></a>
            <div class="card-body">
              <div class="star-rating-group">
                <div class="star-rating"><span style="width:<?=$operator->averageReviewPercent();?>%"></span></div><span class="star-rating-text"><?=number_format($operator->averageStar(), 1);?></span>
              </div>
              <h3 class="card-title"><a href="<?=$operatorView;?>" class="disabled-link"><?=$operator->name;?></a></h3>
              <p class="card-desc">
                <?=$operator->product;?>
              </p><a class="btn btn-primary" href="<?=$operatorView;?>"><?=Yii::t('app', 'Join now');?></a>
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
        <h2 class="sec-title"><?=Yii::t('app', 'Latest Bonuses');?></h2><a class="btn btn-primary trans" href="<?=Url::to(['bonus/index']);?>"><?=Yii::t('app', 'See all lastest bonus');?><i class="fas fa-chevron-right"></i></a>
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
              </div><a class="btn btn-primary" href="<?= $bonus->link ? $bonus->link : 'javascript:;';?>" <?php if ($bonus->link):?>target="_blank"<?php endif;?> ><?=Yii::t('app', 'Get Bonus');?></a>
            </div>
            <div class="bonuses-back">
              <div class="bonuses-icon fas fa-close js-close"></div>
              <div class="bonuses-body">
                <h3 class="bonuses-title"><?=$bonus->title;?></h3>
                <p class="bonuses-desc">
                  <?=Yii::t('app', 'Bonus Type');?>: <?=$bonus->getType();?><br>
                  <?=Yii::t('app', 'Bonus Value');?>: $150<br>
                  <?=Yii::t('app', 'Minimum Deposit');?>: <?=$bonus->minimum_deposit;?><br>
                  <?=Yii::t('app', 'Wagering Requirement');?>: <?=$bonus->wagering_requirement;?>
                </p>
              </div><a class="btn btn-primary" href="<?= $bonus->link ? $bonus->link : 'javascript:;';?>" <?php if ($bonus->link):?>target="_blank"<?php endif;?> ><?=Yii::t('app', 'Get Bonus');?></a>
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
        <h2 class="sec-title"><?=Yii::t('app', 'Lastest complaints');?></h2><a class="btn btn-primary trans" href="#"><?=Yii::t('app', 'See all lastest complaints');?><i class="fas fa-chevron-right"></i></a>
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
                <div class="complaint-desc"><?=sprintf("%s - %s", $complain->operator->name, $complain->reason->title);?></div><a class="btn btn-primary" href="<?=Url::to(['complain/view', 'id' => $complain->id, 'slug' => $complain->slug]);?>"><?=Yii::t('app', 'Read more');?></a>
              </div>
            </div>
            <?php endforeach;?>
          </div>
        </div>
        <div class="col-md-12 col-lg-3 col-lrg-2">
          <?=\frontend\widgets\ComplainByOperatorWidget::widget();?>
        </div>
      </div>
      <?=\frontend\widgets\AdsWidget::widget(['position' => \frontend\models\Ads::POSITION_BOTTOMHOME]);?>
    </div>
  </section>
</main>