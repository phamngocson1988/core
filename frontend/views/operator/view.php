<?php 
use yii\helpers\Url;
use yii\helpers\Html;
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
                <li><a href="#overview"><i class="fa fa-info-circle"></i><span class="nav-text">Overview</span></a></li>
                <li><a href="#detail"><i class="fa fa-exclamation-circle"></i><span class="nav-text">Details</span></a></li>
                <li><a href="#"><i class="fa fa-comments"></i><span class="nav-text">Player Reviews (552)</span></a></li>
                <li><a href="#"><i class="fa fa-gift"></i><span class="nav-text">Bonuses (10)</span></a></li>
                <li><a href="#"><i class="fa fa-thumbs-down"></i><span class="nav-text">Complaints (692)</span></a></li>
                <li><a href="#"><i class="fa fa-newspaper"></i><span class="nav-text">News (22)</span></a></li>
              </ul>
            </div>
          </section>
          <section class="operator-overview widget-box" id="overview">
            <h2 class="widget-head">
              <div class="head-text"><i class="fa fa-info-circle"></i><span class="text"><?=$model->name;?> Overview</span></div>
            </h2>
            <div class="widget-content">
              <h3 class="content-title">Best Place In The World</h3>
              <?=$model->overview;?>
            </div>
            <div class="widget-foot overview-button"><a class="trans" href="#">Show more</a></div>
          </section>
          <section class="operator-detail widget-box" id="detail">
            <h2 class="widget-head">
              <div class="head-text"><i class="fa fa-info-circle"></i><span class="text"><?=$model->name;?></span></div>
            </h2>
            <div class="widget-content">
              <ul class="operator-detail-list">
                <li>
                  <div class="label">
                    <div class="label-icon"><i class="fas fa-globe-americas"></i></div>
                    <div class="label-text">Main URL</div>
                  </div>
                  <div class="content"><a href="<?=$model->main_url;?>" target="_blank"><?=$model->main_url;?></a></div>
                </li>
                <li>
                  <div class="label">
                    <div class="label-icon"><i class="fa fa-language"></i></div>
                    <div class="label-text">Supported Languages</div>
                  </div>
                  <div class="content">
                    <?php $languages = array_map(function($language) {
                      return Html::a($language, "javascript:;");
                    }, explode(",", $model->support_language));
                    echo implode(",", $languages);
                    ?>
                  </div>
                </li>
                <li>
                  <div class="label">
                    <div class="label-icon"><i class="fas fa-undo-alt"></i></div>
                    <div class="label-text">Backup URLs</div>
                  </div>
                  <div class="content">
                    <?php $urls = array_map(function($url) {
                      return Html::a($url, $url, ['target' => '_blank']);
                    }, explode(",", $model->backup_url));
                    echo implode(",", $urls);
                    ?>
                  </div>
                </li>
                <li>
                  <div class="label">
                    <div class="label-icon"><i class="fas fa-dollar-sign"></i></div>
                    <div class="label-text">Supported Currencies</div>
                  </div>
                  <div class="content">
                    <?php $currencies = array_map(function($currency) {
                      return Html::a($currency, "javascript:;");
                    }, explode(",", $model->support_currency));
                    echo implode(",", $currencies);
                    ?>
                  </div>
                </li>
                <li>
                  <div class="label">
                    <div class="label-icon"><i class="fas fa-gamepad"></i></div>
                    <div class="label-text">Products</div>
                  </div>
                  <div class="content">
                    <?php $products = array_map(function($product) {
                      return Html::a($product, "javascript:;");
                    }, explode(",", $model->product));
                    echo implode(",", $products);
                    ?>
                  </div>
                </li>
                <li>
                  <div class="label">
                    <div class="label-icon"><i class="fab fa-codepen"></i></div>
                    <div class="label-text">License</div>
                  </div>
                  <div class="content"><a href="javascript:;"><?=$model->license;?></a></div>
                </li>
                <li>
                  <div class="label">
                    <div class="label-icon"><i class="fas fa-credit-card"></i></div>
                    <div class="label-text">Deposit Methods</div>
                  </div>
                  <div class="content">
                    <?php $depositMethods = array_map(function($deposit) {
                      return Html::a($deposit, "javascript:;");
                    }, explode(",", $model->deposit_method));
                    echo implode(",", $depositMethods);
                    ?>
                  </div>
                </li>
                <li>
                  <div class="label">
                    <div class="label-icon"><i class="fas fa-building"></i></div>
                    <div class="label-text">Owner</div>
                  </div>
                  <div class="content"><a href="javascript:;"><?=$model->owner;?></a></div>
                </li>
                <li>
                  <div class="label">
                    <div class="label-icon"><i class="fas fa-dollar-sign"></i></div>
                    <div class="label-text">Withdrawal Methods</div>
                  </div>
                  <div class="content">
                    <?php $withdrawMethods = array_map(function($method) {
                      return Html::a($method, "javascript:;");
                    }, explode(",", $model->withdrawal_method));
                    echo implode(",", $withdrawMethods);
                    ?>
                  </div>
                </li>
                <li>
                  <div class="label">
                    <div class="label-icon"><i class="fas fa-calendar-alt"></i></div>
                    <div class="label-text">Established</div>
                  </div>
                  <div class="content"><a href="javascript:;"><?=$model->established;?></a></div>
                </li>
                <li>
                  <div class="label">
                    <div class="label-icon"><i class="fas fa-clock"></i></div>
                    <div class="label-text">Withdrawal Time</div>
                  </div>
                  <div class="content"><?=$model->withdrawal_time;?></div>
                </li>
                <li>
                  <div class="label">
                    <div class="label-icon"><i class="fas fa-comments"></i></div>
                    <div class="label-text">Live Chat</div>
                  </div>
                  <div class="content"><?=$model->livechat_support ? 'Yes' : 'No';?></div>
                </li>
                <li>
                  <div class="label">
                    <div class="label-icon"><i class="fas fa-meh"></i></div>
                    <div class="label-text">Withdrawal Limit</div>
                  </div>
                  <div class="content">Up to <?=sprintf("%s %s", $model->withdrawal_currency, number_format($model->withdrawal_limit));?> per transaction</div>
                </li>
                <li>
                  <div class="label">
                    <div class="label-icon"><i class="fas fa-envelope"></i></div>
                    <div class="label-text">Contact</div>
                  </div>
                  <div class="content">
                    <?=$model->support_email;?>
                    <br>
                    <?=$model->support_phone;?>
                  </div>
                </li>
                <li>
                  <div class="label">
                    <div class="label-icon"><i class="fas fa-gift"></i></div>
                    <div class="label-text">Rebates</div>
                  </div>
                  <div class="content">Max <?=$model->rebate;?>%</div>
                </li>
              </ul>
              <div class="suggest-edit"><a class="btn btn-link" href="<?=Url::to(['manage/index', 'id' => $model->id, 'slug' => $model->slug]);?>">Suggest an edit</a></div>
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
          <section class="operator-review-group widget-box" id="review">
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
          <?=\frontend\widgets\OperatorBonusWidget::widget(['operator' => $model]);?>
          <?=\frontend\widgets\OperatorComplainWidget::widget(['operator' => $model]);?>
          
          
        </div>
        <aside class="mod-sidebar">
          <div class="sidebar-col sidebar-category">
            <?=\frontend\widgets\TopOperatorBonusWidget::widget(['operator' => $model]);?>
          </div>
          <div class="sidebar-col sidebar-category">
            <?=\frontend\widgets\TopOperatorNewsWidget::widget(['operator' => $model]);?>
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