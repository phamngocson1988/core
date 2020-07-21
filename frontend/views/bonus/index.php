<?php
use yii\helpers\Url;
use frontend\widgets\LinkPager;
?>
<main>
  <section class="section-module">
    <div class="container"><a class="trans delineation" href="#"><img class="object-fit" src="../img/top/delineation_bnr_01.jpg" alt="image"></a>
      <div class="heading-group">
        <h1 class="sec-title">LATEST BONUSES</h1>
      </div>
      <div class="sec-content">
        <div class="mod-column">
          <div class="widget-box bonus-total">
            <select class="form-control">
              <option value="All">ALL</option>
              <option value="FIRST DEPOSIT BONUS">FIRST DEPOSIT BONUS</option>
              <option value="SECOND DEPOSIT BONUS">SECOND DEPOSIT BONUS</option>
            </select>
            <div class="total-text text-right">TOTAL <?=number_format($total);?> ACTIVE BONUSES</div>
          </div>
          <div class="row">
            <?php foreach ($bonuses as $bonus) :?>
            <div class="col-12 col-sm-6 col-md-6 col-lg-4 col-xl-3">
              <div class="block-bonuses js-bonuses">
                <div class="bonuses-front">
                  <div class="bonuses-icon fas fa-exclamation-circle js-exclamation"></div>
                  <div class="bonuses-image"><img class="object-fit" src="<?=$bonus->getImageUrl('400x220');?>" alt="image"></div>
                  <div class="bonuses-body">
                    <h3 class="bonuses-title"><?=$bonus->title;?></h3>
                    <p class="bonuses-desc">WELCOME BONUS</p>
                  </div><a class="btn btn-primary" href="<?=Url::to(['bonus/view', 'id' => $bonus->id]);?>">GET BONUS</a>
                </div>
                <div class="bonuses-back">
                  <div class="bonuses-icon fas fa-close js-close"></div>
                  <div class="bonuses-body">
                    <h3 class="bonuses-title"><?=$bonus->title;?></h3>
                    <p class="bonuses-desc">Type: Welcome Bonus<br>Bonus Value: $150<br>Minimum Deposit: $15<br>Wagering Requirement: 15x</p>
                  </div><a class="btn btn-primary" href="<?=Url::to(['bonus/view', 'id' => $bonus->id]);?>">GET BONUS</a>
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
            <div class="sidebar-category">
              <p class="category-title">FILTER BONUS</p>
              <div class="category-inner">
                <ul class="category-list list-dropdown">
                  <li><a class="js-btn-dropdown" href="javascript:void(0)">BONUS VALUE</a>
                    <ul class="js-dropdown">
                      <li><a href="#">VALUE 1</a></li>
                      <li><a href="#">VALUE 2</a></li>
                      <li><a href="#">VALUE 3</a></li>
                      <li><a href="#">VALUE 4</a></li>
                    </ul>
                  </li>
                  <li><a class="js-btn-dropdown" href="javascript:void(0)">W/R</a>
                    <ul class="js-dropdown">
                      <li><a href="#">W/R 1</a></li>
                      <li><a href="#">W/R 2</a></li>
                    </ul>
                  </li>
                  <li><a class="js-btn-dropdown" href="javascript:void(0)">MIN DEPOSIT</a>
                    <ul class="js-dropdown">
                      <li><a href="#">MIN DEPOSIT 1</a></li>
                      <li><a href="#">MIN DEPOSIT 2</a></li>
                    </ul>
                  </li>
                  <li><a class="js-btn-dropdown" href="javascript:void(0)">MAX BONUS</a>
                    <ul class="js-dropdown">
                      <li><a href="#">MAX BONUS 1</a></li>
                      <li><a href="#">MAX BONUS 2</a></li>
                    </ul>
                  </li>
                  <li><a class="js-btn-dropdown" href="javascript:void(0)">MAX WITHDRAW</a>
                    <ul class="js-dropdown">
                      <li><a href="#">MAX WITHDRAW 1</a></li>
                      <li><a href="#">MAX WITHDRAW 2</a></li>
                    </ul>
                  </li>
                  <li><a class="js-btn-dropdown" href="javascript:void(0)">PRODUCT</a>
                    <ul class="js-dropdown">
                      <li><a href="#">PRODUCT 1</a></li>
                      <li><a href="#">PRODUCT 2</a></li>
                    </ul>
                  </li>
                  <li><a class="js-btn-dropdown" href="javascript:void(0)">EXCLUSIVE</a>
                    <ul class="js-dropdown">
                      <li><a href="#">EXCLUSIVE 1</a></li>
                      <li><a href="#">EXCLUSIVE 2</a></li>
                    </ul>
                  </li>
                </ul>
              </div>
            </div>
          </div>
          <div class="sidebar-col">
            <?=\frontend\widgets\TopOperatorWidget::widget();?>
          </div>
        </aside>
      </div>
    </div>
  </section>
</main>