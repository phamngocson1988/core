<?php
use yii\helpers\Url;
?>
<p class="category-title">COMPLAINT CATEGORY</p>
<div class="category-inner">
  <ul class="category-list">
    <li><a href="#">All (<?=number_format($total);?>)</a></li>
    <?php foreach ($reasons as $reason) : ?>
    <li><a href="#"><?=sprintf("%s (%s)", $reason->title, number_format($stats[$reason->id]));?></a></li>
    <?php endforeach ;?>
  </ul>
</div>