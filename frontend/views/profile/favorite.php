<?php 
use yii\helpers\Url;
use common\components\helpers\TimeElapsed;
$user = Yii::$app->user->getIdentity();
$level = $user->getLevel();
$point = $user->totalPoint();
?>
<main>
  <section class="section-profile-user">
    <div class="container">
      <div class="sec-heading-profile widget-box mb-4">
        <div class="heading-banner"><img class="object-fit" src="/img/profile/profile_bnr.jpg" alt="image"></div>
        <div class="heading-body">
          <div class="heading-avatar col-md-6 col-lg-4 order-md-2 p-0">
            <div class="heading-image user-avatar-background">
              <img class="object-fit user-avatar" src="<?=$user->getAvatarUrl('180x180');?>" alt="image">
              <a class="edit-camera fas fa-camera trans" href="#"></a>
              <input type="file" id="upload-user-avatar-element" name="upload-user-avatar-element" style="display: none" multiple accept="image/*"/>
            </div>
            <h1 class="heading-name"><?=$user->getName();?></h1>
          </div>
          <div class="heading-left d-flex col-md-3 col-lg-4 order-md-1 p-0">
            <div class="level d-flex">
              <div class="text-uppercase">Level</div>
              <div class="level-number"><?=$level;?></div>
            </div>
            <div class="point d-flex">
              <div class="text-uppercase">POINTS</div>
              <div class="point-number"><?=number_format($point);?></div>
            </div>
          </div>
          <div class="heading-right col-md-3 col-lg-4 order-md-3 p-0">
            <ul class="profile-link">
              <li class="favorites"><a class="trans" href="<?=Url::to(['profile/index']);?>"><span>MY PROFILE</span> <i class="far fa-user-circle"></i></a></li>
              <li class="edit-profile"><a class="trans" href="<?=Url::to(['profile/setting']);?>"><span>EDIT PROFILE</span><i class="fas fa-user"></i></a></li>
            </ul>
          </div>
        </div>
      </div>
      <div class="heading-group">
        <h2 class="sec-title">MY FAVORITE OPERATORS</h2><a class="btn btn-primary trans" href="<?=Url::to(['operator/index']);?>">+ ADD NEWS FAVORITE OPERATORS</a>
      </div>
      <div class="row">
        <?php foreach ($operators as $operator) : ?>
        <div class="col-12 col-sm-6 col-md-4 col-lg-3 col-lrg-2 mb-3">
          <div class="block-card">
            <div class="card-image"><img class="object-fit" src="<?=$operator->getImageUrl('400x220');?>" alt="image"></div>
            <div class="card-body">
              <div class="star-rating-group">
                <div class="star-rating"><span style="width:<?=$operator->averageReviewPercent();?>%"></span></div><span class="star-rating-text"><?=number_format($operator->averageStar(), 1);?></span>
              </div>
              <h3 class="card-title"><?=$operator->name;?></h3>
              <p class="card-desc">Product A, Product B,Product C, Product S</p><a class="btn btn-primary" href="<?=Url::to(['operator/view', 'id' => $operator->id, 'slug' => $operator->slug]);?>">JOIN NOW</a>
            </div>
          </div>
        </div>
        <?php endforeach;?>
      </div>
    </div>
  </section>
  <section class="section-latest-complaints section-white">
    <div class="container">
      <div class="heading-group">
        <h2 class="sec-title">FOLLOWED COMPLAINTS</h2><a class="btn btn-primary trans" href="<?=Url::to(['complain/index']);?>">+ FOLLOWED COMPLAINTS</a>
      </div>
      <div class="row">
        <?php foreach ($complains as $complain) : ?>
        <div class="col-12 col-sm-6 col-md-4 col-lg-3 col-lrg-2 mb-3">
          <div class="block-complaint">
            <div class="complaint-image"><img src="<?=$complain->getIcon();?>" alt="image"></div>
            <div class="complaint-heading">
              <p class="complaint-ttl"><?=strtoupper($complain->status);?> CASE</p>
              <p><?=TimeElapsed::timeElapsed($complain->created_at);?></p>
            </div>
            <div class="complaint-desc"><?=sprintf("%s - %s", $complain->operator->name, $complain->reason->title);?></div><a class="btn btn-primary" href="#">READ MORE</a>
          </div>
        </div>
        <?php endforeach;?>
      </div>
    </div>
  </section>
</main>
<?php
$script = <<< JS
var uploadImage = new AjaxUploadImage({
  trigger_element: '.edit-camera',
  file_element: '#upload-user-avatar-element', // seletor of the file element
  review_width: '180',
  review_height: '180',
  link: '###LINK###'
});
uploadImage.callback = function(data) { 
  console.log(data);
  var objs = Object.values(data);
  if (objs.length) {
    var avatarObj = objs[0];
    var id = avatarObj.id;
    var thumb = avatarObj.thumb;
    console.log(id);
    console.log(thumb);
    $('body').find('.user-avatar').attr('src', thumb);
    $('body').find('.user-avatar-background').attr('style', 'background-image: url("'+thumb+'")')
    // Update user avatar
    $.ajax({
      url: '###UPDATEAVATAR###',
      type: 'POST',
      dataType : 'json',
      data: {id: id},
      success: function (result, textStatus, jqXHR) {
        console.log(result);
      },
    });
  } else {
    toastr.error('No file');
  }
};
JS;
$uploadLink = Url::to(['image/ajax-upload']);
$updateAvatarLink = Url::to(['profile/update-avatar']);
$script = str_replace('###LINK###', $uploadLink, $script);
$script = str_replace('###UPDATEAVATAR###', $updateAvatarLink, $script);
$this->registerJs($script);
?>