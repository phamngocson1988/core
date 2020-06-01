<?php
use yii\helpers\Url; 
?>
<main>
  <div class="section-keyvisual">
    <div class="container">
      <a class="trans delineation" href="#"><img class="object-fit" src="/img/top/delineation_bnr_01.jpg" alt="image"></a>
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
                <div class="star-rating"><span style="width:92.5%"></span></div><span class="star-rating-text">9.25</span>
              </div>
              <h3 class="card-title"><?=$operator->name;?></h3>
              <p class="card-desc">Product A, Product B,Product C, Product S</p><a class="btn btn-primary" href="#">JOIN NOW</a>
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
                <p class="bonuses-desc">WELCOME BONUS</p>
              </div><a class="btn btn-primary" href="<?=Url::to(['bonus/view', 'id' => $bonus->id]);?>"><?=Yii::t('app', 'get_bonus');?></a>
            </div>
            <div class="bonuses-back">
              <div class="bonuses-icon fas fa-close js-close"></div>
              <div class="bonuses-body">
                <h3 class="bonuses-title"><?=$bonus->title;?></h3>
                <p class="bonuses-desc">Type: Welcome Bonus<br>Bonus Value: $150<br>Minimum Deposit: $15<br>Wagering Requirement: 15x</p>
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
            <div class="col-12 col-sm-6 col-md-4 col-lg-4 col-xl-3">
              <div class="block-complaint">
                <div class="complaint-image"><img src="/img/top/img_02.jpg" alt="image"></div>
                <div class="complaint-heading">
                  <p class="complaint-ttl">OPEN CASE</p>
                  <p>An Hour Ago</p>
                </div>
                <div class="complaint-desc">Henderson &amp; Ben - Slow approval of withdrawal</div><a class="btn btn-primary" href="#">READ MORE</a>
              </div>
            </div>
            <div class="col-12 col-sm-6 col-md-4 col-lg-4 col-xl-3">
              <div class="block-complaint">
                <div class="complaint-image"><img src="/img/top/img_02.jpg" alt="image"></div>
                <div class="complaint-heading">
                  <p class="complaint-ttl">RESOLVED</p>
                  <p>An Hour Ago</p>
                </div>
                <div class="complaint-desc">Henderson &amp; Ben - Slow approval of withdrawal</div><a class="btn btn-primary" href="#">READ MORE</a>
              </div>
            </div>
            <div class="col-12 col-sm-6 col-md-4 col-lg-4 col-xl-3">
              <div class="block-complaint">
                <div class="complaint-image"><img src="/img/top/img_02.jpg" alt="image"></div>
                <div class="complaint-heading">
                  <p class="complaint-ttl">REJECTED</p>
                  <p>An Hour Ago</p>
                </div>
                <div class="complaint-desc">Henderson &amp; Ben - Slow approval of withdrawal</div><a class="btn btn-primary" href="#">READ MORE</a>
              </div>
            </div>
            <div class="col-12 col-sm-6 col-md-4 col-lg-4 col-xl-3">
              <div class="block-complaint">
                <div class="complaint-image"><img src="/img/top/img_02.jpg" alt="image"></div>
                <div class="complaint-heading">
                  <p class="complaint-ttl">REJECTED</p>
                  <p>An Hour Ago</p>
                </div>
                <div class="complaint-desc">Henderson &amp; Ben - Slow approval of withdrawal</div><a class="btn btn-primary" href="#">READ MORE</a>
              </div>
            </div>
            <div class="col-12 col-sm-6 col-md-4 col-lg-4 col-xl-3">
              <div class="block-complaint">
                <div class="complaint-image"><img src="/img/top/img_02.jpg" alt="image"></div>
                <div class="complaint-heading">
                  <p class="complaint-ttl">OPEN CASE</p>
                  <p>An Hour Ago</p>
                </div>
                <div class="complaint-desc">Henderson &amp; Ben - Slow approval of withdrawal</div><a class="btn btn-primary" href="#">READ MORE</a>
              </div>
            </div>
            <div class="col-12 col-sm-6 col-md-4 col-lg-4 col-xl-3">
              <div class="block-complaint">
                <div class="complaint-image"><img src="/img/top/img_02.jpg" alt="image"></div>
                <div class="complaint-heading">
                  <p class="complaint-ttl">RESOLVED</p>
                  <p>An Hour Ago</p>
                </div>
                <div class="complaint-desc">Henderson &amp; Ben - Slow approval of withdrawal</div><a class="btn btn-primary" href="#">READ MORE</a>
              </div>
            </div>
            <div class="col-12 col-sm-6 col-md-4 col-lg-4 col-xl-3">
              <div class="block-complaint">
                <div class="complaint-image"><img src="/img/top/img_02.jpg" alt="image"></div>
                <div class="complaint-heading">
                  <p class="complaint-ttl">REJECTED</p>
                  <p>An Hour Ago</p>
                </div>
                <div class="complaint-desc">Henderson &amp; Ben - Slow approval of withdrawal</div><a class="btn btn-primary" href="#">READ MORE</a>
              </div>
            </div>
            <div class="col-12 col-sm-6 col-md-4 col-lg-4 col-xl-3">
              <div class="block-complaint">
                <div class="complaint-image"><img src="/img/top/img_02.jpg" alt="image"></div>
                <div class="complaint-heading">
                  <p class="complaint-ttl">REJECTED</p>
                  <p>An Hour Ago</p>
                </div>
                <div class="complaint-desc">Henderson &amp; Ben - Slow approval of withdrawal</div><a class="btn btn-primary" href="#">READ MORE</a>
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-12 col-lg-3 col-lrg-2">
          <div class="sidebar-category category-has-btn">
            <p class="category-title">CASE BY OPERATORS</p>
            <div class="category-inner">
              <ul class="category-list">
                <li><a href="#"><span class="category-icon"><img src="/img/common/category_icon_01.png" alt="icon"></span><span class="category-name">COCA COLA (1,120)</span></a></li>
                <li><a href="#"><span class="category-icon"><img src="/img/common/category_icon_01.png" alt="icon"></span><span class="category-name">COCA COLA (965)</span></a></li>
                <li><a href="#"><span class="category-icon"><img src="/img/common/category_icon_01.png" alt="icon"></span><span class="category-name">COCA COLA (222)</span></a></li>
                <li><a href="#"><span class="category-icon"><img src="/img/common/category_icon_01.png" alt="icon"></span><span class="category-name">COCA COLA (103)</span></a></li>
                <li><a href="#"><span class="category-icon"><img src="/img/common/category_icon_01.png" alt="icon"></span><span class="category-name">COCA COLA (44)</span></a></li>
                <li><a href="#"><span class="category-icon"><img src="/img/common/category_icon_01.png" alt="icon"></span><span class="category-name">COCA COLA (52)</span></a></li>
                <li><a href="#"><span class="category-icon"><img src="/img/common/category_icon_01.png" alt="icon"></span><span class="category-name">COCA COLA</span></a></li>
                <li><a href="#"><span class="category-icon"><img src="/img/common/category_icon_01.png" alt="icon"></span><span class="category-name">COCA COLA</span></a></li>
                <li><a href="#"><span class="category-icon"><img src="/img/common/category_icon_01.png" alt="icon"></span><span class="category-name">COCA COLA</span></a></li>
                <li><a href="#"><span class="category-icon"><img src="/img/common/category_icon_01.png" alt="icon"></span><span class="category-name">COCA COLA</span></a></li>
                <li><a href="#"><span class="category-icon"><img src="/img/common/category_icon_01.png" alt="icon"></span><span class="category-name">COCA COLA</span></a></li>
                <li><a href="#"><span class="category-icon"><img src="/img/common/category_icon_01.png" alt="icon"></span><span class="category-name">COCA COLA</span></a></li>
              </ul>
            </div><a class="btn btn-primary" href="#">SEE ALL</a>
          </div>
        </div>
      </div><a class="delineation large trans" href="#"><img class="object-fit" src="/img/top/delineation_bnr_02.jpg" alt="image"></a>
    </div>
  </section>
</main>