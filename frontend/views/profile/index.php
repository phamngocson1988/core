<?php 
use yii\helpers\Url;
use frontend\models\UserPoint;
use frontend\models\UserBadge;
$user = Yii::$app->user->getIdentity();
$level = $user->getLevel();
$point = $user->totalPoint();
$nextLevelPoint = UserPoint::getPointByLevel($level + 1);
$badgeUrl = Url::to(['profile/badge']);
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
              <li class="favorites"><a class="trans" href="<?=Url::to(['profile/favorite']);?>"><span>FAVORITES</span><i class="fas fa-star"></i></a></li>
              <li class="edit-profile"><a class="trans" href="<?=Url::to(['profile/setting']);?>"><span>EDIT PROFILE</span><i class="fas fa-user"></i></a></li>
            </ul>
          </div>
        </div>
      </div>
      <div class="sec-content">
        <div class="mod-column">
          <div class="widget-box timeline-post">
            <div class="timeline-heading">
              <p class="heading-text mb-0">ACTIVITIES</p>
              <div class="dropdown dropdown-fillter">
                <button class="dropdown-toggle" id="dropdownMenuButton" type="button" data-toggle="dropdown" aria-expanded="false"><i class="fas fa-glass-martini"></i>FILLTER</button>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                  <ul class="list-tabs nav nav-pills" role="tablist">
                    <li role="presentation"><a class="tab-item active" href="javascript:;" id="badge-all">All</a></li>
                    <li role="presentation"><a class="tab-item" href="javascript:;" id="badge-profile">Complete Profile</a></li>
                    <li role="presentation"><a class="tab-item" href="javascript:;" id="badge-complain">Complaints</a></li>
                    <li role="presentation"><a class="tab-item" href="javascript:;" id="badge-review">Reviews</a></li>
                  </ul>
                </div>
              </div>
            </div>
            <div class="tab-content">
              <div class="tab-pane active" id="all" role="tabpanel">
                <div class="timeline-post-inner badge-list">
                  
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="mod-sidebar">
          <div class="sidebar-col widget-box"><a class="btn-profile trans" href="<?=Url::to(['mail/index']);?>"><i class="fas fa-envelope"></i><i class="fas fa-chevron-right"></i><span>MY MESSAGES (0)</span></a></div>
          <div class="sidebar-col widget-box">
            <div class="widget-title">LEVEL PROGRESS</div>
            <div class="widget-body">
              <div class="timeline-progress">
                <p class="timeline-txt">Level <?=$level;?></p>
                <?php if ($nextLevelPoint !== false) : ?>
                <p class="timeline-levelval"><i class="far fa-gem"></i><span><?=number_format($point);?>/<?=number_format($nextLevelPoint);?></span></p>
                <div class="progress-meter"><span style="width:<?=$point*100/$nextLevelPoint;?>%"></span></div>
                <?php else : ?>
                <p class="timeline-levelval"><i class="far fa-gem"></i><span><?=number_format($point);?></span></p>
                <div class="progress-meter"><span style="width:100%"></span></div>
                <?php endif;?>
              </div>
              <p class="mb-2">Achievements</p>
              <ul class="achievement-badge">
                <?php if ($user->hasBadge(UserBadge::BADGE_PROFILE)) : ?>
                <li><img src="/img/common/member.png" alt="image"></li>
                <?php endif;?>
                <?php if ($user->hasBadge(UserBadge::BADGE_COMPLAIN)) : ?>
                <li><img src="/img/common/icon_review_dislike.png" alt="image"></li>
                <?php endif;?>
                <?php if ($user->hasBadge(UserBadge::BADGE_REVIEW)) : ?>
                <li><img src="/img/forum/forum_icon_01.png" alt="image"></li>
                <?php endif;?>
              </ul>
            </div>
          </div>
          <div class="sidebar-col widget-box">
            <div class="widget-title">RANKINGS</div>
            <div class="widget-body">
              <ul class="rankings-list">
                <li><a class="trans" href="javascript:;"><span class="icon"><img src="/img/common/planet.png" alt="image"></span><span class="text">#<?=number_format($user->getRanking());?> in overall</span><i class="fas fa-chevron-right"></i></a></li>
              </ul>
            </div>
          </div>
          <div class="sidebar-col widget-box">
            <div class="widget-title"><?=sprintf("%s'S STATS", $user->username);?></div>
            <div class="widget-body">
              <ul class="stats-list">
                <li>Member since
                  <p class="text"><?=date("F j, Y", strtotime($user->created_at));?></p>
                </li>
                <li>Level
                  <p class="text"><?=$level;?></p>
                </li>
                <li>Badges
                  <p class="text"><?=number_format($user->countBadge());?></p>
                </li>
                <li>Forum posts
                  <p class="text"><?=number_format($user->countForumPost());?></p>
                </li>
              </ul>
            </div>
          </div>
        </div>
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

// Badget List
var badgeListLoading = new AjaxPaging({
  container: '.badge-list',
  request_url: '$badgeUrl',
  auto_first_load: true
});
$('#load-more-reivew').on('click', function() {
  badgeListLoading.load();
});
$('#badge-all').on('click', function() {
  badgeListLoading.reset({
    condition: {}
  });
});
$('#badge-complain').on('click', function() {
  badgeListLoading.reset({
    condition: {
      badge: 'complain',
    }
  });
});
$('#badge-review').on('click', function() {
  badgeListLoading.reset({
    condition: {
      badge: 'review',
    }
  });
});
$('#badge-profile').on('click', function() {
  badgeListLoading.reset({
    condition: {
      badge: 'profile',
    }
  });
});
$('.tab-item').on('click', function(){
  $('.tab-item').removeClass('active');
  $(this).addClass('active');
})
JS;
$uploadLink = Url::to(['image/ajax-upload']);
$updateAvatarLink = Url::to(['profile/update-avatar']);
$script = str_replace('###LINK###', $uploadLink, $script);
$script = str_replace('###UPDATEAVATAR###', $updateAvatarLink, $script);
$this->registerJs($script);
?>