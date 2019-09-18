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
              <div class="qa-section-title">
                Kinggems Knowledge Base
              </div>
              <div class="qa-section-list showing" style="display: block">
                <div class="row">
                  <?php foreach ($categories as $category) : ?>
                  <div class="qa-item col col-12 col-lg-6">
                    <div class="qa-group">
                      <ul>
                        <?=$category->title;?>
                        <?php 
                        $command = $category->getQuestions();
                        $command->limit(5);
                        ?>
                        <?php foreach ($command->all() as $question) : ?>
                        <li>
                          <i class="far fa-question-circle"></i>
                          <!-- <i class="fab fa-leanpub"></i> -->
                          <a href="<?=Url::to(['site/question-detail', 'id' => $question->id, 'slug' => $question->slug]);?>"><?=$question->title;?></a>
                        </li>
                        <?php endforeach;?>
                        <?php if ($command->count() > 5) : ?>
                        <li>
                          <i class="fas fa-angle-double-right"></i>
                          <a href="<?=Url::to(['site/question-category', 'id' => $category->id, 'slug' => $category->slug]);?>">See all</a>
                        </li>
                        <?php endif;?>
                      </ul>
                    </div>
                  </div>
                  <?php endforeach;?>
                  <!-- <a class="btn-see-all" href=""><i class="fas fa-angle-double-right"></i>See all</a> -->
                </div>
              </div>
            </div>
            <!-- <div class="qa-section">
              <div class="qa-section-title">
                  G2G Knowledge Base
              </div>
              <div class="qa-section-list">
                  <div class="row">
                      <div class="qa-item col col-12 col-lg-6">
                          <a class="qa-img" href="#"><img src="images/qa-avatar.png" alt=""></a>
                          <div class="qa-group">
                              <a href="#" class="qa-group-name"><b>Account</b> (19)</a>
                              <p>Getting started</p>
                              <p>Add</p>
                          </div>
                      </div>
                      <div class="qa-item col col-12 col-lg-6">
                          <a class="qa-img" href="#"><img src="images/qa-avatar.png" alt=""></a>
                          <div class="qa-group">
                              <a href="#" class="qa-group-name"><b>Account</b> (19)</a>
                              <p>Getting started</p>
                              <p>Add</p>
                          </div>
                      </div>
                      <div class="qa-item col col-12 col-lg-6">
                          <a class="qa-img" href="#"><img src="images/qa-avatar.png" alt=""></a>
                          <div class="qa-group">
                              <a href="#" class="qa-group-name"><b>Account</b> (19)</a>
                              <p>Getting started</p>
                              <p>Add</p>
                          </div>
                      </div>
                      <div class="qa-item col col-12 col-lg-6">
                          <a class="qa-img" href="#"><img src="images/qa-avatar.png" alt=""></a>
                          <div class="qa-group">
                              <a href="#" class="qa-group-name"><b>Account</b> (19)</a>
                              <p>Getting started</p>
                              <p>Add</p>
                          </div>
                      </div>
                      <div class="qa-item col col-12 col-lg-6">
                          <a class="qa-img" href="#"><img src="images/qa-avatar.png" alt=""></a>
                          <div class="qa-group">
                              <a href="#" class="qa-group-name"><b>Account</b> (19)</a>
                              <p>Getting started</p>
                              <p>Add</p>
                          </div>
                      </div>
                      <div class="qa-item col col-12 col-lg-6">
                          <a class="qa-img" href="#"><img src="images/qa-avatar.png" alt=""></a>
                          <div class="qa-group">
                              <a href="#" class="qa-group-name"><b>Account</b> (19)</a>
                              <p>Getting started</p>
                              <p>Add</p>
                          </div>
                      </div>
                  </div>
              </div>
              </div> -->
          </div>
        </div>
        <div class="col col-12 col-sm-12 col-md-3 col-lg-3">
          <div class="fb-page" data-href="https://www.facebook.com/KhoaHocKyNang.org/" data-tabs="like" data-width="" data-height="400" data-small-header="false" data-adapt-container-width="true" data-hide-cover="false" data-show-facepile="true"><blockquote cite="https://www.facebook.com/KhoaHocKyNang.org/" class="fb-xfbml-parse-ignore"><a href="https://www.facebook.com/KhoaHocKyNang.org/">Khóa Học Kỹ Năng</a></blockquote></div>
        </div>
      </div>
    </div>
  </div>
</section>
<?php
$script = <<< JS
$('.qa-section .qa-section-title').click(function(){
  if(!$(this).next().hasClass('showing')){
      $('.qa-section .qa-section-list.showing').slideToggle().removeClass('showing');
      $(this).next().slideToggle().addClass('showing');
  }else{
      $(this).next().slideToggle().removeClass('showing');
  }
});
JS;
$this->registerJs($script);
?>
