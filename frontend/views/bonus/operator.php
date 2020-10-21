<?php
use yii\helpers\Url;
use frontend\widgets\LinkPager;
?>
<main>
        <section class="section-module">
          <div class="container">
            <div class="heading-group">
              <h1 class="sec-title"><?=$operator->name;?></h1><a class="btn btn-primary trans" href="<?=Url::to(['operator/view', 'id' => $operator->id, 'slug' => $operator->slug]);?>"><?=Yii::t('app', 'back_to_operator');?><i class="fas fa-chevron-right"></i></a>
            </div>
            <div class="sec-content">
              <div class="mod-column">
                <div class="row">
                  <?php foreach ($bonuses as $bonus) : ?>
                  <div class="col-12 col-sm-6 col-md-6 col-lg-4 col-xl-3">
                    <div class="block-bonuses js-bonuses">
                      <div class="bonuses-front">
                        <div class="bonuses-icon fas fa-exclamation-circle js-exclamation"></div>
                        <div class="bonuses-image"><img class="object-fit" src="<?=$bonus->getImageUrl('400x220');?>" alt="image"></div>
                        <div class="bonuses-body">
                          <h3 class="bonuses-title"><?=$bonus->title;?></h3>
                          <p class="bonuses-desc"><?=$bonus->getType();?></p>
                        </div><a class="btn btn-primary" href="javascript:;"><?=Yii::t('app', 'get_bonus');?></a>
                      </div>
                      <div class="bonuses-back">
                        <div class="bonuses-icon fas fa-close js-close"></div>
                        <div class="bonuses-body">
                          <h3 class="bonuses-title"><?=$bonus->title;?></h3>
                          <p class="bonuses-desc">
                            <?=Yii::t('app', 'bonus_type');?>: <?=$bonus->getType();?><br>
                            <?=Yii::t('app', 'bonus_value');?>: $150<br>
                            <?=Yii::t('app', 'minimum_deposit');?>: <?=$bonus->minimum_deposit;?><br>
                            <?=Yii::t('app', 'wagering_requirement');?>: <?=$bonus->wagering_requirement;?>
                          </p>
                        </div><a class="btn btn-primary" href="javascript:;"><?=Yii::t('app', 'get_bonus');?></a>
                      </div>
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
                <div class="sidebar-col">
                  <?=\frontend\widgets\TopOperatorWidget::widget();?>
                </div>
                <?=\frontend\widgets\AdsWidget::widget(['position' => \frontend\models\Ads::POSITION_SIDEBAR]);?>
              </aside>
            </div>
          </div>
        </section>
      </main>