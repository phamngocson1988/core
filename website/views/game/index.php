<?php 
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use website\widgets\LinkPager;
?>
<div class="container mt-5">
  <?php $form = ActiveForm::begin(['method' => 'GET', 'action' => ['game/index'], 'options' => ['autocomplete' => 'off']]);?>
    <div class="input-group search-category">
      <?=$form->field($search, 'category_id', [
        'options' => ['class' => 'input-group-prepend dropdown'],
        'inputOptions' => ['class' => 'form-control btn btn-outline-secondary select-category', 'name' => 'category_id']
      ])->dropdownList($search->fetchCategory(), ['prompt' => 'All games'])->label(false);?>
      <?=$form->field($search, 'q', [
        'options' => ['tag' => false],
        'template' => '{input}',
        'inputOptions' => ['class' => 'form-control', 'name' => 'q', 'placeholder' => 'Enter keyword here.....']
      ])->textInput()->label(false);?>
      <div class="input-group-append">
        <button class="btn" type="submit"><img class="icon-sm" src="/images/icon/search.svg" /></button>
      </div>
    </div>
  <?php ActiveForm::end()?>
</div>
<div class="container category-page post-list mt-5">
  <div class="post-wrapper d-flex flex-wrap justify-content-left">
    <?php foreach ($models as $game) : ?>
    <?php $viewUrl = Url::to(['game/view', 'id' => $game->id, 'slug' => $game->slug]);?>
    <div class="post-item card">
      <div class="post-thumb">
        <a href="<?=$viewUrl;?>" class="hover-img"><img src="<?=$game->getImageUrl('300x300');?>" /></a>
        <?php if ($game->getSavedPrice()) : ?>
        <span class="tag save">save <?=number_format($game->getSavedPrice());?>%</span>
        <?php endif;?>
        <span class="tag promotion">promotion</span>
        <?php if ($game->isBackToStock()) : ?>
        <span class="tag bts">event in-game</span>
        <?php endif;?>
      </div>
      <div class="post-content">
        <h4 class="post-title">
          <a href="<?=$viewUrl;?>"><?=$game->title;?></a>
        </h4>
        <?php if ($game->hasCategory()) : ?>
        <div class="tags">
          <img src="/images/icon/tag.svg" />
          <?php $categories = array_slice($game->categories, 0, 3);?>
          <?php foreach ($categories as $category) : ?>
          <span class="badge badge-primary"><?=$category->name;?></span>
          <?php endforeach; ?>
          <?php if (count($game->categories) > 3) :?>
          <span class="badge badge-primary">...</span>
          <?php endif;?>
        </div>
        <?php endif;?>
        <div class="d-flex justify-content-between align-items-center py-2">
          <div class="flex-fill value">
            <span class="num"><?=number_format($game->pack);?></span>
            <br />
            <span class="text"><?=Html::encode($game->unit_name);?></span>
          </div>
          <div class="flex-fill price">
            <strike>$<?=number_format($game->getOriginalPrice());?></strike> <span class="num">$<?=number_format($game->getPrice());?></span>
          </div>
        </div>
        <div class="d-flex justify-content-between align-items-center">
          <div class="flex-fill status">
            <hr>
            <img class="icon-fire" src="/images/icon/fire.svg" />
            <?php if ($game->isSoldout()) :?>
            <span class="text" style="color: gray">out stock</span>
            <?php else : ?>
            <span class="text">in stock</span>
            <?php endif;?>
          </div>
          <div class="flex-fill">
            <a href="<?=$viewUrl;?>" class="main-btn">
              <span>BUY NOW</span>
            </a>
          </div>
        </div>
      </div>
    </div><!-- END POST ITEM -->
    <?php endforeach;?>
  </div> <!-- END POST CATEGORY -->
</div><!-- END container -->

<div class="container mt-5 mb-5">
  <nav aria-label="Page navigation" class="mt-2 mb-5">
    <?=LinkPager::widget(['pagination' => $pages]);?>
  </nav>
</div>

