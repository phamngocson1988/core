<?php
use yii\helpers\Url;
use common\components\helpers\TimeElapsed;
?>
<section class="operator-complaint" id="complain">
  <h2 class="sec-title text-center"><?=$operator->name;?> <?=Yii::t('app', 'Complaints');?></h2>
  <ul class="complaint-stats">
    <li><?=Yii::t('app', 'Total {total} cases', ['total' => number_format($total)]);?></li>
    <li><?=Yii::t('app', '{resolve}/{total} case resolved', ['resolve' => number_format($totalResolve), 'total' => number_format($total)]);?> (<?=$percent;?>%)</li>
    <?php if ($avgResponseTime) : ?>
    <li><?=Yii::t('app', '{average} hours average response', ['average' => round($avgResponseTime)]);?></li>
    <?php endif;?>
  </ul>
  <div class="row">
    <?php foreach ($complains as $complain) : ?>
    <div class="col-12 col-sm-6 col-md-6 col-lg-3">
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
  <div class="operator-sec-button"><a class="btn" href="<?=Url::to(['complain/operator', 'id' => $operator->id, 'slug' => $operator->slug]);?>"><?=Yii::t('app', 'See all');?> <i class="fas fa-chevron-right"></i></a></div>
</section>

<section class="operator-trouble widget-box">
  <div class="trouble-title"><?=Yii::t('app', 'Have trouble with {operator}', ['operator' => $operator->name]);?></div>
  <div class="trouble-button"><a class="btn btn-lg trans" href="<?=Url::to(['complain/create', 'operator_id' => $operator->id]);?>"><?=Yii::t('app', 'Submit complaint');?></a><a class="btn btn-lg trans" href="#"><?=Yii::t('app', 'Learn more');?></a></div>
</section>