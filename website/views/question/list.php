<?php
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
?>
<div class="container my-5">
  <h3 class="text-red text-center mb-4">How can we help you?</h3>
  <div class="input-group search-qa">
    <input type="text" class="form-control" name="keyword" placeholder="Search Articles">
    <div class="input-group-append">
      <button class="btn" type="button">
        <img class="icon-sm" src="/images/icon/search.svg" />
      </button>
    </div>
  </div>
</div>

  <div class="container mb-5">
    <h2 class="mb-3"><?=$category->title;?></h2>
    <div id="accordion" class="accordion-qa">
      <?php foreach ($questions as $question) : ?>
      <div class="card">
        <div class="card-header" id="headingOne<?=$question->id;?>">
          <h5 class="mb-0">
            <button class="btn btn-link" data-toggle="collapse" data-target="#collapseOne<?=$question->id;?>" aria-expanded="true" aria-controls="collapseOne<?=$question->id;?>">
              <?=$question->title;?>
            </button>
          </h5>
        </div>
    
        <div id="collapseOne<?=$question->id;?>" class="collapse show" aria-labelledby="headingOne<?=$question->id;?>" data-parent="#accordion">
          <div class="card-body">
            <?=$question->content;?>
          </div>
        </div>
      </div>
      <?php endforeach;?>
    </div>
  </div>