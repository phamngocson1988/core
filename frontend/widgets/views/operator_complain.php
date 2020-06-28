<?php
use yii\helpers\Url;
use common\components\helpers\TimeElapsed;
?>
<section class="operator-complaint" id="complain">
  <h2 class="sec-title text-center"><?=$operator->name;?> Complaints</h2>
  <ul class="complaint-stats">
    <li>Total <?=number_format($total);?> cases</li>
    <li><?=sprintf("%s/%s", number_format($totalResolve), number_format($total));?> case resolved (<?=$percent;?>%)</li>
    <?php if ($avgResponseTime) : ?>
    <li><?=round($avgResponseTime);?> hours average response</li>
    <?php endif;?>
  </ul>
  <div class="row">
    <?php foreach ($complains as $complain) : ?>
    <div class="col-12 col-sm-6 col-md-6 col-lg-3">
      <div class="block-complaint">
        <div class="complaint-image"><img src="/img/complain/<?=$complain->status;?>.jpg" alt="image"></div>
        <div class="complaint-heading">
          <p class="complaint-ttl"><?=strtoupper($complain->status);?> CASE</p>
          <p><?=TimeElapsed::timeElapsed($complain->created_at);?></p>
        </div>
        <div class="complaint-desc"><?=sprintf("%s - %s", $complain->operator->name, $complain->reason->title);?></div><a class="btn btn-primary" href="<?=Url::to(['complain/view', 'id' => $complain->id]);?>">READ MORE</a>
      </div>
    </div>
    <?php endforeach;?>
  </div>
  <div class="operator-sec-button"><a class="btn" href="<?=Url::to(['complain/operator', 'id' => $operator->id, 'slug' => $operator->slug]);?>">See all <i class="fas fa-chevron-right"></i></a></div>
</section>

<section class="operator-trouble widget-box">
  <div class="trouble-title">Have trouble with <?=$operator->name;?></div>
  <div class="trouble-button"><a class="btn btn-lg trans" href="<?=Url::to(['complain/create', 'operator_id' => $operator->id]);?>">Submit complaint</a><a class="btn btn-lg trans" href="#">Learn more</a></div>
</section>