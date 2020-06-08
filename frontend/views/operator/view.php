<?php 
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use common\components\helpers\TimeElapsed;
?>
<main>
  <section class="section-module">
    <div class="container">
      <div class="sec-content">
        <div class="mod-column">
          <section class="operator-hero widget-box">
            <div class="hero-main"><a class="hero-photo" href="<?=$model->main_url;?>"><img src="/img/common/sample_img_00.png" alt="<?=$model->name;?>"></a>
              <div class="hero-info">
                <div class="hero-name"><?=$model->name;?></div>
                <div class="hero-rate"><span class="rate-text">Very Good <?=number_format($model->averageStar(), 1);?></span><span class="rate-star">
                    <div class="star-rating"><span style="width:<?=$model->averageReviewPercent();?>%"></span></div></span></div>
                <div class="hero-buttons">
                  <a class="btn btn-outline-light" href="<?=$model->main_url;?>">Visit now</a>
                  <?php if (!$isFavorite) : ?>
                  <a class="btn btn-outline-light add-favorite-action" href="<?=Url::to(['operator/add-favorite', 'id' => $model->id]);?>">Add to favorite <i class="fa fa-star-o"></i></a>
                  <?php endif;?>
                </div>
                <div class="hero-feature">
                  <p><i class="fa fa-clock-o"></i> Average Complaint Response Time: 1 hour</p>
                </div>
              </div>
            </div>
            <div class="hero-footer">
              <ul class="hero-nav">
                <li><a href="#"><i class="fa fa-info-circle"></i><span class="nav-text">Overview</span></a></li>
                <li><a href="#"><i class="fa fa-exclamation-circle"></i><span class="nav-text">Details</span></a></li>
                <li><a href="#"><i class="fa fa-comments"></i><span class="nav-text">Player Reviews (552)</span></a></li>
                <li><a href="#"><i class="fa fa-gift"></i><span class="nav-text">Bonuses (10)</span></a></li>
                <li><a href="#"><i class="fa fa-thumbs-down"></i><span class="nav-text">Complaints (692)</span></a></li>
                <li><a href="#"><i class="fa fa-newspaper"></i><span class="nav-text">News (22)</span></a></li>
              </ul>
            </div>
          </section>
          <section class="operator-overview widget-box">
            <h2 class="widget-head">
              <div class="head-text"><i class="fa fa-info-circle"></i><span class="text"><?=$model->name;?> Overview</span></div>
            </h2>
            <div class="widget-content">
              <h3 class="content-title">Best Place In The World</h3>
              <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Veritatis vitae, repellat ad modi sapiente corrupti quisquam et adipisci fugiat beatae, delectus tempora vero doloribus itaque, expedita nam incidunt. Eaque expedita amet vero fugit numquam earum eos, consectetur necessitatibus eius nobis enim similique velit tempora.</p>
              <p>Earum quae eveniet odio fuga eaque debitis, dignissimos quisquam ipsam harum eius nulla corporis. Distinctio, nesciunt. Iure quos ad nemo pariatur dignissimos quaerat! Cupiditate distinctio necessitatibus perspiciatis placeat iste nam, rerum nemo? Adipisci possimus facere id, incidunt doloremque aperiam odio porro dolore nulla a autem nihil optio provident expedita esse quas omnis. Nulla amet animi aut velit quisquam, laudantium perferendis ex blanditiis autem delectus praesentium ratione, nobis hic facere consectetur perspiciatis? Temporibus delectus minima.</p>
            </div>
            <div class="widget-foot overview-button"><a class="trans" href="#">Show more</a></div>
          </section>
          <section class="operator-detail widget-box">
            <h2 class="widget-head">
              <div class="head-text"><i class="fa fa-info-circle"></i><span class="text">Henderson &amp; Bench Details</span></div>
            </h2>
            <div class="widget-content">
              <ul class="operator-detail-list">
                <li>
                  <div class="label">
                    <div class="label-icon"><i class="fas fa-globe-americas"></i></div>
                    <div class="label-text">Main URL</div>
                  </div>
                  <div class="content"><a href="#">www.henderson.com</a></div>
                </li>
                <li>
                  <div class="label">
                    <div class="label-icon"><i class="fa fa-language"></i></div>
                    <div class="label-text">Supported Languages</div>
                  </div>
                  <div class="content"><a href="#">Vietnamese</a>,
                    <a href="#">Chinese</a>,
                    <a href="#">English</a>
                  </div>
                </li>
                <li>
                  <div class="label">
                    <div class="label-icon"><i class="fas fa-undo-alt"></i></div>
                    <div class="label-text">Backup URLs</div>
                  </div>
                  <div class="content"><a href="#">Link 1</a>,
                    <a href="#">Link 2</a>,
                    <a href="#">Link 3</a>,
                    <a href="#">Link 4</a>
                  </div>
                </li>
                <li>
                  <div class="label">
                    <div class="label-icon"><i class="fas fa-dollar-sign"></i></div>
                    <div class="label-text">Supported Currencies</div>
                  </div>
                  <div class="content"><a href="#">VND</a>,
                    <a href="#">THB</a>,
                    <a href="#">MYR</a>,
                    <a href="#">USD</a>
                  </div>
                </li>
                <li>
                  <div class="label">
                    <div class="label-icon"><i class="fas fa-gamepad"></i></div>
                    <div class="label-text">Products</div>
                  </div>
                  <div class="content"><a href="#">Product 1</a>,
                    <a href="#">Product 2</a>,
                    <a href="#">Product 3</a>
                  </div>
                </li>
                <li>
                  <div class="label">
                    <div class="label-icon"><i class="fab fa-codepen"></i></div>
                    <div class="label-text">License</div>
                  </div>
                  <div class="content"><a href="#">Philippines</a></div>
                </li>
                <li>
                  <div class="label">
                    <div class="label-icon"><i class="fas fa-credit-card"></i></div>
                    <div class="label-text">Deposit Methods</div>
                  </div>
                  <div class="content"><a href="#">Product 1</a>,
                    <a href="#">Product 2</a>,
                    <a href="#">Product 3</a>
                  </div>
                </li>
                <li>
                  <div class="label">
                    <div class="label-icon"><i class="fas fa-building"></i></div>
                    <div class="label-text">Owner</div>
                  </div>
                  <div class="content"><a href="#">Golden Mountain, Inc.</a></div>
                </li>
                <li>
                  <div class="label">
                    <div class="label-icon"><i class="fas fa-dollar-sign"></i></div>
                    <div class="label-text">Withdrawal Methods</div>
                  </div>
                  <div class="content"><a href="#">Product 1</a>,
                    <a href="#">Product 2</a>,
                    <a href="#">Product 3</a>
                  </div>
                </li>
                <li>
                  <div class="label">
                    <div class="label-icon"><i class="fas fa-calendar-alt"></i></div>
                    <div class="label-text">Established</div>
                  </div>
                  <div class="content"><a href="#">2001</a></div>
                </li>
                <li>
                  <div class="label">
                    <div class="label-icon"><i class="fas fa-clock"></i></div>
                    <div class="label-text">Withdrawal Time</div>
                  </div>
                  <div class="content">Local Banks: 0-24 hours</div>
                </li>
                <li>
                  <div class="label">
                    <div class="label-icon"><i class="fas fa-comments"></i></div>
                    <div class="label-text">Live Chat</div>
                  </div>
                  <div class="content">Yes</div>
                </li>
                <li>
                  <div class="label">
                    <div class="label-icon"><i class="fas fa-meh"></i></div>
                    <div class="label-text">Withdrawal Limit</div>
                  </div>
                  <div class="content">Up to $50,000 per transaction</div>
                </li>
                <li>
                  <div class="label">
                    <div class="label-icon"><i class="fas fa-envelope"></i></div>
                    <div class="label-text">Contact</div>
                  </div>
                  <div class="content">
                    Support Email: csd@henderson.com
                    <br>
                    Support Telephone: +8412345678
                  </div>
                </li>
                <li>
                  <div class="label">
                    <div class="label-icon"><i class="fas fa-gift"></i></div>
                    <div class="label-text">Rebates</div>
                  </div>
                  <div class="content">Max 1.5%</div>
                </li>
              </ul>
              <div class="suggest-edit"><a class="btn btn-link" href="#">Suggest an edit</a></div>
            </div>
          </section>
          <section class="operator-review-rate">
            <h2 class="sec-title text-center"><?=$model->name;?></h2>
            <?php if (!$isReview) : ?>
            <div class="sec-container widget-box">
              <div class="rate-main">
                <div class="user-photo"><img src="/img/common/avatar_img_02.png" alt="Username"></div>
                <div class="rate-content">
                  <div class="rate-label">Rate Henderson &amp; Bench and write a review</div>
                  <div class="rate-star">
                    <div class="star-group">
                      <?php for ($i = 0; $i < 10; $i++) : ?>
                      <svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" viewBox="0 0 24 24" fill="#D8D8D8" style="margin-left: 2px;">
                        <path d="M23.3365713,8.79596431 L16.3173369,7.84685057 L12.3964479,0.505772166 C12.3964479,0.220616216 12.175529,0 11.8905268,0 C11.6059461,0 11.3850272,0.220616216 11.3850272,0.505772166 L7.46203025,7.87806587 L0.663714711,8.79596431 C0.410332532,8.70020928 0.127016684,8.8583949 0.0313132653,9.14355085 C-0.0639685526,9.4287068 0.0629333376,9.71344092 0.347513988,9.80750864 L5.75454635,15.5979461 L4.39404004,23.1592189 C4.23593968,23.3802569 4.26713815,23.7295308 4.48974346,23.8877164 C4.71024076,24.0771173 5.02644149,24.0146868 5.18454185,23.7919614 L11.9204604,20.3118777 L18.6559574,23.7919614 C18.8140578,24.0129994 19.1302585,24.0771173 19.3511774,23.8877164 C19.5716747,23.6983155 19.6357581,23.3819443 19.4464593,23.1592189 L18.1192594,15.7561317 L23.6839705,9.80750864 C23.9377743,9.71175361 24.0625682,9.4287068 23.9689728,9.14355085 C23.8732694,8.8583949 23.5899535,8.70020928 23.3365713,8.79596431 Z"></path>
                      </svg>
                      <?php endfor;?>
                    </div>
                  </div>
                </div>
              </div>
              <?php if ($user) : ?>
              <div class="rate-button"><a class="btn btn-lg btn-primary" href="#write-review-section">Write a review</a></div>
              <?php endif;?>
            </div>
            <?php endif;?>
          </section>
          <section class="operator-review-group widget-box">
            <div class="review-header">
              <div class="header-buttons"><a class="btn-text sortable is-down js-btn-sort" href="#">Sort by date</a><a class="btn-text sortable is-down js-btn-sort" href="#">Sort by date</a>
                <div class="btn-text" href="#">Average rating <?=$model->averageReviewRating();?> of <?=number_format($model->countReview());?> reviews</div>
              </div>
            </div>
            <div class="review-list">
            </div>
            <div class="review-button"><a class="btn trans" href="javascript:;" id="load-more-reivew">Show more player reviews</a></div>
          </section>
          <?php if ($user && !$isReview) : ?>
          <section class="operator-review-form widget-box" id="write-review-section">
            <div class="formrv-header">
              <div class="user-photo"><img src="<?=$user->getAvatarUrl('50x50');?>" alt="Username"></div>
              <div class="rate-content">
                <div class="rate-label">Rate Henderson &amp; Bench and write a review</div>
                <div class="rate-star">
                  <div class="star-group">
                    <?php for ($i = 0; $i < 10; $i++) : ?>
                    <svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" viewBox="0 0 24 24" fill="#D8D8D8" style="margin-right: 2px;" class="rating-star" data-value="<?=$i+1;?>">
                      <path d="M23.3365713,8.79596431 L16.3173369,7.84685057 L12.3964479,0.505772166 C12.3964479,0.220616216 12.175529,0 11.8905268,0 C11.6059461,0 11.3850272,0.220616216 11.3850272,0.505772166 L7.46203025,7.87806587 L0.663714711,8.79596431 C0.410332532,8.70020928 0.127016684,8.8583949 0.0313132653,9.14355085 C-0.0639685526,9.4287068 0.0629333376,9.71344092 0.347513988,9.80750864 L5.75454635,15.5979461 L4.39404004,23.1592189 C4.23593968,23.3802569 4.26713815,23.7295308 4.48974346,23.8877164 C4.71024076,24.0771173 5.02644149,24.0146868 5.18454185,23.7919614 L11.9204604,20.3118777 L18.6559574,23.7919614 C18.8140578,24.0129994 19.1302585,24.0771173 19.3511774,23.8877164 C19.5716747,23.6983155 19.6357581,23.3819443 19.4464593,23.1592189 L18.1192594,15.7561317 L23.6839705,9.80750864 C23.9377743,9.71175361 24.0625682,9.4287068 23.9689728,9.14355085 C23.8732694,8.8583949 23.5899535,8.70020928 23.3365713,8.79596431 Z"></path>
                    </svg>
                    <?php endfor;?>
                  </div>
                </div>
              </div>
            </div>
            <div class="formrv-main">
              <?php $form = ActiveForm::begin(['action' => Url::to(['operator/add-review', 'id' => $model->id]), 'id' => 'add-review-form']); ?>
              <?= $form->field($reviewForm, 'star', [
                'options' => ['tag' => false],
                'template' => '{input}',
                'inputOptions' => ['id' => 'rating-star-value']
              ])->hiddenInput()->label(false);?>
              <div class="formrv-inputs">
                <?= $form->field($reviewForm, 'good_thing', [
                  'options' => ['class' => 'formrv-row row-like'],
                  'template' => '{input}',
                  'inputOptions' => ['placeholder' => 'What do you like?', 'rows' => 6, 'class' => 'form-control']
                ])->textArea()->label(false);?>
                <?= $form->field($reviewForm, 'bad_thing', [
                  'options' => ['class' => 'formrv-row row-dislike'],
                  'template' => '{input}',
                  'inputOptions' => ['placeholder' => 'What do you dislike?', 'rows' => 6, 'class' => 'form-control']
                ])->textArea()->label(false);?>
              </div>
              <div class="formrv-options">
                <?= $form->field($reviewForm, 'notify_register', [
                  'options' => ['class' => 'formrv-check'],
                  'template' => '{input}',
                  'inputOptions' => ['class' => 'form-check-input', 'id' => 'review-option-1'],
                  'labelOptions' => ['class' => 'form-check-label']
                ])->checkbox()->label('Notify me about new player reviews on this page.');?>

                <?= $form->field($reviewForm, 'experience', [
                  'options' => ['class' => 'formrv-check'],
                  'template' => '{input}',
                  'inputOptions' => ['class' => 'form-check-input', 'id' => 'review-option-2'],
                  'labelOptions' => ['class' => 'form-check-label']
                ])->checkbox()->label('I declare that my review is based on my own experience and represents my genuine opinion of this operator.');?>
              </div>
              <div class="formrv-button">
                <button class="btn btn-primary btn-lg" type="submit">Post my review</button>
              </div>
              <?php ActiveForm::end();?>
            </div>
          </section>
          <?php endif;?>
          <section class="operator-bonus">
            <h2 class="sec-title text-center"><?=$model->name;?> Bonuses</h2>
            <div class="row">
              <?php foreach ($bonuses as $bonus) : ?>
              <div class="col-12 col-sm-6 col-md-6 col-lg-3">
                <div class="block-bonuses js-bonuses">
                  <div class="bonuses-front">
                    <div class="bonuses-icon fas fa-exclamation-circle js-exclamation"></div>
                    <div class="bonuses-image"><img class="object-fit" src="<?=$bonus->getImageUrl('400x220');?>" alt="image"></div>
                    <div class="bonuses-body">
                      <h3 class="bonuses-title"><?=$bonus->title;?></h3>
                      <p class="bonuses-desc">WELCOME BONUS</p>
                    </div><a class="btn btn-primary" href="<?=Url::to(['bonus/view', 'id' => $bonus->id]);?>">GET BONUS</a>
                  </div>
                  <div class="bonuses-back">
                    <div class="bonuses-icon fas fa-close js-close"></div>
                    <div class="bonuses-body">
                      <h3 class="bonuses-title"><?=$bonus->title;?></h3>
                      <p class="bonuses-desc"><?=$bonus->content;?></p>
                    </div><a class="btn btn-primary" href="<?=Url::to(['bonus/view', 'id' => $bonus->id]);?>">GET BONUS</a>
                  </div>
                </div>
              </div>
              <?php endforeach;?>
            </div>
            <div class="operator-sec-button"><a class="btn" href="<?=Url::to(['bonus/index']);?>">See all <i class="fas fa-chevron-right"></i></a></div>
          </section>
          <section class="operator-complaint">
            <h2 class="sec-title text-center"><?=$model->name;?> Complaints</h2>
            <ul class="complaint-stats">
              <li>Total 99 cases</li>
              <li>700/995 case resolved (90%)</li>
              <li>5 hours average response</li>
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
                  <div class="complaint-desc"><?=$complain->title;?></div><a class="btn btn-primary" href="<?=Url::to(['complain/view', 'id' => $complain->id]);?>">READ MORE</a>
                </div>
              </div>
              <?php endforeach;?>
            </div>
            <div class="operator-sec-button"><a class="btn" href="<?=Url::to(['complain/index', 'operator_id' => $model->id]);?>">See all <i class="fas fa-chevron-right"></i></a></div>
          </section>
          <section class="operator-trouble widget-box">
            <div class="trouble-title">Have trouble with <?=$model->name;?></div>
            <div class="trouble-button"><a class="btn btn-lg trans" href="<?=Url::to(['complain/create', 'operator_id' => $model->id]);?>">Submit complaint</a><a class="btn btn-lg trans" href="#">Learn more</a></div>
          </section>
        </div>
        <aside class="mod-sidebar">
          <div class="sidebar-col sidebar-category">
            <p class="category-title text-center">Henderson &amp; Bench<br>bonuses</p>
            <ul class="list-news-cate">
              <li><a class="trans" href="#"><span class="icon"><img src="/img/common/category_icon_01.png" alt="image"></span><span class="name">100% up to $500<br>+200 bonus</span></a></li>
              <li><a class="trans" href="#"><span class="icon"><img src="/img/common/category_icon_01.png" alt="image"></span><span class="name">100% up to $500<br>+200 bonus</span></a></li>
              <li><a class="trans" href="#"><span class="icon"><img src="/img/common/category_icon_01.png" alt="image"></span><span class="name">100% up to $500<br>+200 bonus</span></a></li>
              <li><a class="trans" href="#"><span class="icon"><img src="/img/common/category_icon_01.png" alt="image"></span><span class="name">100% up to $500<br>+200 bonus</span></a></li>
              <li><a class="trans" href="#"><span class="icon"><img src="/img/common/category_icon_01.png" alt="image"></span><span class="name">100% up to $500<br>+200 bonus</span></a></li>
            </ul>
            <div class="category-button"><a class="trans" href="#">Show all bonuses</a></div>
          </div>
          <div class="sidebar-col sidebar-category">
            <p class="category-title text-center">Henderson &amp; Bench<br>news</p>
            <ul class="list-news-cate">
              <li><a class="trans" href="#"><span class="icon"><img src="/img/common/category_icon_01.png" alt="image"></span><span class="name">100% up to $500<br>+200 bonus</span></a></li>
              <li><a class="trans" href="#"><span class="icon"><img src="/img/common/category_icon_01.png" alt="image"></span><span class="name">100% up to $500<br>+200 bonus</span></a></li>
              <li><a class="trans" href="#"><span class="icon"><img src="/img/common/category_icon_01.png" alt="image"></span><span class="name">100% up to $500<br>+200 bonus</span></a></li>
              <li><a class="trans" href="#"><span class="icon"><img src="/img/common/category_icon_01.png" alt="image"></span><span class="name">100% up to $500<br>+200 bonus</span></a></li>
              <li><a class="trans" href="#"><span class="icon"><img src="/img/common/category_icon_01.png" alt="image"></span><span class="name">100% up to $500<br>+200 bonus</span></a></li>
            </ul>
            <div class="category-button"><a class="trans" href="#">Show all news</a></div>
          </div>
          <div class="sidebar-col side-operator">
            <?=\frontend\widgets\TopOperatorWidget::widget();?>
          </div>
          <div class="sidebar-delineation"><a class="trans" href="#"><img src="/img/operators/img_01.jpg" alt="image"></a></div>
        </aside>
      </div>
    </div>
  </section>
</main>
<?php
$script = <<< JS
$(".add-favorite-action").ajax_action({
  confirm: false,
  callback: function(eletement, data) {
    toastr.success(data.message);
    setTimeout(function(){
      location.reload();
    },1000);
  },
  error: function(errors) {
      toastr.error(errors);
  },
});

$(".rating-star").on("click", function() {
  var val = $(this).data('value');
  $("#rating-star-value").val(val);
  $(".rating-star").attr('fill', '#D8D8D8');
  $(".rating-star").each(function( index ) {
    if (index < val) {
      $( this ).attr('fill', '#ddc72d');
    }
  });
});
// Review List
var reviewListLoading = new AjaxPaging({
  container: '.review-list',
  request_url: '###REVIEWLIST###',
  auto_first_load: true
});
// Review Form
var reviewForm = new AjaxFormSubmit({
  element : 'form#add-review-form'
});
reviewForm.error = function (errors) {
  toastr.error(errors);
}
reviewForm.success = function (data, form) {
  toastr.success(data.message);
  setTimeout(() => {  
    location.reload();
  }, 1000);
}
reviewListLoading.no_data = function() {
  console.log('no_data');
  // $('#load-more-reivew').style('display', 'none');
};
reviewListLoading.stop_search = function() {
  console.log('stop_search');
  // $('#load-more-reivew').remove();
};
$('#load-more-reivew').on('click', function() {

  console.log('load more');
  reviewListLoading.load();
})
JS;
$listReviewLink = Url::to(['operator/list-review', 'id' => $model->id]);
$script = str_replace('###REVIEWLIST###', $listReviewLink, $script);
$this->registerJs($script);
?>