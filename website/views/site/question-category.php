<?php 
use yii\helpers\Url;
?>
<section class="page-title">
  <div class="container">
    <div class="row">
      <div class="col col-sm-12">
        <div class="page-title-content text-center">
          <img src="/images/text-qa-title.png" alt="">
        </div>
      </div>
    </div>
  </div>
</section>
<section class="promotion-search">
  <div class="container">
    <div class="row">
      <div class="col col-sm-12">
        <form method="GET" autocomplete='off' action="<?=Url::to(['site/question-search']);?>">
          <input type="text" name="q" placeholder="Got a question? Find it here...">
          <input type="submit" value="">
        </form>
      </div>
    </div>
  </div>
</section>
<section class="qa-page">
  <div class="container">
    <div class="small-container">
      <div class="row">
        <div class="col col-12 col-sm-12 col-md-9 col-lg-9">
          <div class="qa-listing">
            <div class="qa-section">
              <ul class="page-breadcrumb">
                <li><a href="<?=Url::to(['site/question']);?>">Kinggems Knowledge Base</a> \ </li>
                <li><a href="javascript:void(0);"><?=$category->title;?></a></li>
              </ul>
              <div class="qa-section-title">
                Kinggems Knowledge Base
              </div>
              <div class="qa-section-list showing" style="display: block">
                <div class="row">
                  <div class="qa-item col col-12 col-lg-12">
                    <div class="qa-group">
                      <ul>
                        <?=$category->title;?>
                        <?php foreach ($category->questions as $question) : ?>
                        <li>
                          <i class="far fa-question-circle"></i>
                          <a href="<?=Url::to(['site/question-detail', 'id' => $question->id, 'slug' => $question->slug]);?>"><?=$question->title;?></a>
                        </li>
                        <?php endforeach;?>
                      </ul>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col col-12 col-sm-12 col-md-3 col-lg-3">
          <div class="qa-listing">
            <div class="qa-section">
              <div class="qa-section-title">More articles in <?=$category->title;?></div>
              <div class="qa-section-list showing" style="display: block">
                <div class="row">
                  <div class="qa-item col col-12 col-lg-12">
                    <div class="qa-group">
                      <ul>
                        <?php foreach ($category->questions as $question) : ?>
                        <li>
                          <i class="far fa-question-circle"></i>
                          <a href="<?=Url::to(['site/question-detail', 'id' => $question->id, 'slug' => $question->slug]);?>"><?=$question->title;?></a>
                        </li>
                        <?php endforeach;?>
                      </ul>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
