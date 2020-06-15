<?php 
use yii\helpers\Url;
$user = Yii::$app->user->getIdentity();
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
              <div class="level-number">1</div>
            </div>
            <div class="point d-flex">
              <div class="text-uppercase">POINTS</div>
              <div class="point-number">200</div>
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
                    <li role="presentation"><a class="active" href="#all" aria-controls="all" role="tab" data-toggle="tab">All</a></li>
                    <li role="presentation"><a href="#achievements" aria-controls="achievements" role="tab" data-toggle="tab">Achievements</a></li>
                    <li role="presentation"><a href="#complaints" aria-controls="complaints" role="tab" data-toggle="tab">Complaints</a></li>
                    <li role="presentation"><a href="#reviews" aria-controls="reviews" role="tab" data-toggle="tab">Reviews</a></li>
                  </ul>
                </div>
              </div>
            </div>
            <div class="tab-content">
              <div class="tab-pane active" id="all" role="tabpanel">
                <div class="timeline-post-inner">
                  <div class="timeline-post-row">
                    <div class="timeline-group">
                      <div class="timeline-icon"><i class="fas fa-trophy"></i></div>
                      <div class="timeline-ttl">Unlocked an ABCXYZ badge</div>
                      <div class="timeline-points"><i class="far fa-gem"></i><span>200 points</span></div>
                      <div class="timeline-time"><i class="far fa-clock"></i><span>2 hour ago</span></div>
                      <div class="timeline-badges"><span class="badge-container"><img src="/img/common/member.png" alt="image"></span></div>
                    </div>
                  </div>
                  <div class="timeline-post-row">
                    <div class="timeline-group">
                      <div class="timeline-icon"><i class="fas fa-thumbs-down"></i></div>
                      <div class="timeline-ttl">You wrote a complaint: henderson &amp; bench - my case was not approved open case</div>
                    </div>
                  </div>
                  <div class="timeline-post-row">
                    <div class="timeline-group">
                      <div class="timeline-icon"><i class="fas fa-comments"></i></div>
                      <div class="timeline-ttl">You wrote a review: henderson &amp; bench - my case was not approved</div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="tab-pane" id="achievements" role="tabpanel">
                <div class="timeline-post-inner">
                  <div class="timeline-post-row">
                    <div class="timeline-group">
                      <div class="timeline-icon"><i class="fas fa-trophy"></i></div>
                      <div class="timeline-ttl">Unlocked an ABCXYZ badge</div>
                      <div class="timeline-points"><i class="far fa-gem"></i><span>200 points</span></div>
                      <div class="timeline-time"><i class="far fa-clock"></i><span>2 hour ago</span></div>
                      <div class="timeline-badges"><span class="badge-container"><img src="/img/common/member.png" alt="image"></span></div>
                    </div>
                  </div>
                  <div class="timeline-post-row">
                    <div class="timeline-group">
                      <div class="timeline-icon"><i class="fas fa-thumbs-down"></i></div>
                      <div class="timeline-ttl">You wrote a complaint: henderson &amp; bench - my case was not approved open case</div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="tab-pane" id="complaints" role="tabpanel">
                <div class="timeline-post-inner">
                  <div class="timeline-post-row">
                    <div class="timeline-group">
                      <div class="timeline-icon"><i class="fas fa-trophy"></i></div>
                      <div class="timeline-ttl">Unlocked an ABCXYZ badge</div>
                      <div class="timeline-points"><i class="far fa-gem"></i><span>200 points</span></div>
                      <div class="timeline-time"><i class="far fa-clock"></i><span>2 hour ago</span></div>
                      <div class="timeline-badges"><span class="badge-container"><img src="/img/common/member.png" alt="image"></span></div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="tab-pane" id="reviews" role="tabpanel">
                <div class="timeline-post-inner">
                  <div class="timeline-post-row">
                    <div class="timeline-group">
                      <div class="timeline-icon"><i class="fas fa-thumbs-down"></i></div>
                      <div class="timeline-ttl">You wrote a complaint: henderson &amp; bench - my case was not approved open case</div>
                    </div>
                  </div>
                  <div class="timeline-post-row">
                    <div class="timeline-group">
                      <div class="timeline-icon"><i class="fas fa-comments"></i></div>
                      <div class="timeline-ttl">You wrote a review: henderson &amp; bench - my case was not approved</div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="mod-sidebar">
          <div class="sidebar-col widget-box"><a class="btn-profile trans" href="#"><i class="fas fa-envelope"></i><i class="fas fa-chevron-right"></i><span>MY MESSAGES (0)</span></a></div>
          <div class="sidebar-col widget-box">
            <div class="widget-title">LEVEL PROGRESS</div>
            <div class="widget-body">
              <div class="timeline-progress">
                <p class="timeline-txt">Level 1</p>
                <p class="timeline-levelval"><i class="far fa-gem"></i><span>200/500</span></p>
                <div class="progress-meter"><span style="width:40%"></span></div>
              </div>
              <p class="mb-2">Achievements</p>
              <ul class="achievement-badge">
                <li><img src="/img/common/member.png" alt="image"></li>
              </ul>
            </div>
          </div>
          <div class="sidebar-col widget-box">
            <div class="widget-title">RANKINGS</div>
            <div class="widget-body">
              <ul class="rankings-list">
                <li><a class="trans" href="#"><span class="icon"><img src="/img/common/planet.png" alt="image"></span><span class="text">#196 in overall</span><i class="fas fa-chevron-right"></i></a></li>
              </ul>
            </div>
          </div>
          <div class="sidebar-col widget-box">
            <div class="widget-title">USERNAME'S STATS</div>
            <div class="widget-body">
              <ul class="stats-list">
                <li>Member since
                  <p class="text">March 29,2019</p>
                </li>
                <li>Level
                  <p class="text">1</p>
                </li>
                <li>Badges
                  <p class="text">1</p>
                </li>
                <li>Forum posts
                  <p class="text">0</p>
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
JS;
$uploadLink = Url::to(['image/ajax-upload']);
$updateAvatarLink = Url::to(['profile/update-avatar']);
$script = str_replace('###LINK###', $uploadLink, $script);
$script = str_replace('###UPDATEAVATAR###', $updateAvatarLink, $script);
$this->registerJs($script);
?>