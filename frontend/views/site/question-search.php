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
          <input type="text" name="q" value="<?=$q;?>" placeholder="Got a question? Find it here...">
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
                <li><a href="javascript:void(0);">Search</a></li>
              </ul>
              <div class="qa-section-title">
                Kinggems Knowledge Base
              </div>
              <div class="qa-section-list showing" style="display: block">
                <div class="row">
                  <div class="qa-item col col-12 col-lg-12">
                    <div class="qa-group">
                      <ul>
                        <?php foreach ($models as $question) : ?>
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
          <div class="fb-page" data-href="https://www.facebook.com/KhoaHocKyNang.org/" data-tabs="like" data-width="" data-height="400" data-small-header="false" data-adapt-container-width="true" data-hide-cover="false" data-show-facepile="true"><blockquote cite="https://www.facebook.com/KhoaHocKyNang.org/" class="fb-xfbml-parse-ignore"><a href="https://www.facebook.com/KhoaHocKyNang.org/">Khóa Học Kỹ Năng</a></blockquote></div>
        </div>
      </div>
    </div>
  </div>
</section>
