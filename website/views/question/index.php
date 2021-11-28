<?php
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
?>
<div class="container my-5">
  <h3 class="text-red text-center mb-4">How can we help you?</h3>
  <div class="input-group search-qa">
    <input type="text" class="form-control" placeholder="Search Articles">
    <div class="input-group-append">
      <button class="btn" type="button">
        <img class="icon-sm" src="/images/icon/search.svg" />
      </button>
    </div>
  </div>
</div>

<div class="container mb-5">
  <div class="row qa-category">
    <?php foreach ($categories as $category) : ?>
    <?php $link = $category->link ? $category->link : Url::to(['question/list', 'id' => $category->id]);?>
    <div class="col-md-4 text-center">
      <div class="qa-category-item">
      <?php if ($category->hot) : ?>
      <img class="icon-new" src="/images/icon/new.gif"/>
      <?php endif;?>
        <a href="<?=$link;?>">
          <img class="icon" src="<?=$category->icon_url;?>"/>
        </a>
        <h4 class="text-center"><a href="<?=Url::to(['question/list', 'id' => $category->id]);?>"><?=$category->title;?> <span class="num">(<?= ArrayHelper::getValue($stat, $category->id, 0);?>)</span></a></h4>
        <p class="text-center text-des"><?=$category->description;?></p>
      </div>
    </div>
    <?php endforeach;?>
  </div>
</div>