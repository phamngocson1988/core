<?php
use yii\helpers\Url;
?>
<div class="sidebar-category category-has-btn">
    <p class="category-title">CASE BY OPERATORS</p>
    <div class="category-inner">
      <ul class="category-list">
        <?php foreach ($operators as $operator) : ?>
        <li><a href="<?=Url::to(['complain/view', 'id' => $operator->id]);?>"><span class="category-icon"><img src="<?=$operator->getImageUrl('50x50');?>" alt="icon"></span><span class="category-name"><?=sprintf("%s (%s)", $operator->name, number_format($stats[$operator->id]));?></span></a></li>
        <?php endforeach;?>
      </ul>
    </div><a class="btn btn-primary" href="<?=Url::to(['complain/index']);?>">SEE ALL</a>
  </div>