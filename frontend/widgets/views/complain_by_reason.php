<?php
use yii\helpers\Url;
?>
<p class="category-title"><?=Yii::t('app', 'Complaint category');?></p>
<div class="category-inner">
  <ul class="category-list">
    <li><a href="#"><?=Yii::t('app', 'All');?> (<?=number_format($total);?>)</a></li>
    <?php foreach ($reasons as $reason) : ?>
    <li><a href="#"><?=sprintf("%s (%s)", $reason->title, number_format($stats[$reason->id]));?></a></li>
    <?php endforeach ;?>
  </ul>
</div>