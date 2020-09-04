<?php
use yii\helpers\Url;
?>
<section class="operator-bonus" id="bonus">
  <h2 class="sec-title text-center"><?=$operator->name;?> Bonuses</h2>
  <div class="row">
    <?php foreach ($bonuses as $bonus) : ?>
    <div class="col-12 col-sm-6 col-md-6 col-lg-3">
      <div class="block-bonuses js-bonuses">
        <div class="bonuses-front">
          <div class="bonuses-icon fas fa-exclamation-circle js-exclamation"></div>
          <div class="bonuses-image"><img class="object-fit" src="<?=$bonus->getImageUrl('400x220');?>" alt="image"></div>
          <div class="bonuses-body">
            <h3 class="bonuses-title"><?=$bonus->title;?></h3>
            <p class="bonuses-desc"><?=$bonus->getType();?></p>
          </div><a class="btn btn-primary" href="javascript:;">GET BONUS</a>
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
          </div><a class="btn btn-primary" href="javascript:;">GET BONUS</a>
        </div>
      </div>
    </div>
    <?php endforeach;?>
  </div>
  <div class="operator-sec-button"><a class="btn" href="<?=Url::to(['bonus/operator', 'id' => $operator->id, 'slug' => $operator->slug]);?>">See all <i class="fas fa-chevron-right"></i></a></div>
</section>