<?php 
use yii\helpers\Url;
use frontend\models\UserPoint;
use frontend\models\UserBadge;
use common\components\helpers\TimeElapsed;
?>
<main>
  <div class="forum-container container section-forum-profile">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a class="fa fa-home trans" href="/">Home</a></li>
      <li class="breadcrumb-item active"><?=$user->username;?></li>
    </ol>
    <div class="widget-box">
      <div class="profile-banner"></div>
      <div class="profile-user">
        <div class="profile-left"><img class="img-radius" src="<?=$user->getAvatarUrl('80x80');?>" alt="Username"></div>
        <div class="profile-right">
          <h1 class="profile-name"><?=$user->username;?></h1>
          <p class="profile-member">Member</p>
          <ul class="profile-text">
            <li>
              <p class="text">Joined</p>
              <p class="date"><?=date("F j, Y", strtotime($user->created_at));?></p>
            </li>
            <?php if ($user->last_login): ?>
            <li>
              <p class="text">Last visited</p>
              <p class="date"><?=date("F j, Y", strtotime($user->last_login));?></p>
            </li>
            <?php endif;?>
          </ul>
        </div>
      </div>
      <div class="forum-profile">
        <div class="forum-main">
          <section class="profile-activity">
            <h2 class="activity-title">Activities</h2>
            <div class="activity-list">
              <?php foreach ($posts as $post) : ?>
              <?php $topic = $post->topic;?>
              <article class="activity-topic"><a class="user-photo" href="javascript:;"><img src="<?=$user->getAvatarUrl('50x50');?>" alt="<?=$user->username;?>"></a>
                <div class="topic-main">
                  <h3 class="topic-title"><a href="<?=Url::to(['forum/topic', 'id' => $topic->id, 'slug' => $topic->slug]);?>"><?=$topic->subject;?></a></h3>
                  <div class="topic-reaction"><a href="javascript:;"><?=$user->username;?></a> replied to <a href="<?=Url::to(['member/index', 'username' => $topic->creator->username]);?>"><?=$topic->creator->username;?></a>'s topic in <a href="<?=Url::to(['forum/topic', 'id' => $topic->id, 'slug' => $topic->slug]);?>"><?=$topic->subject;?></a></div>
                  <div class="topic-content">
                    <p><?=nl2br($post->content);?></p>
                  </div>
                  <div class="topic-bottom"><a href="#"><i class="fa fa-clock"></i> <?=TimeElapsed::timeElapsed($topic->created_at);?></a><a href="javascript:;"><i class="fa fa-comment"></i> <?=number_format($topic->countPost());?> replied</a></div>
                </div>
              </article>
            <?php endforeach;?>
            </div>
          </section>
        </div>
        <aside class="forum-sidebar">
          <div class="sidebar-col widget-box"><a class="btn-profile trans" href="#"><i class="fas fa-trophy"></i><span>badge</span></a></div>
          <div class="widget-box side-reputation">
            <p class="reputation-ttl">community reputation</p>
            <p class="reputation-number"><?=number_format($user->totalPoint());?></p><span>Points</span>
          </div>
          <?php if ($favorites) : ?>
          <div class="sidebar-col widget-box">
            <div class="widget-head"><?=sprintf("%s favorites", number_format($totalFavorite));?></div>
            <div class="widget-inner">
              <ul class="followers-list">
                <?php foreach ($favorites as $favorite) : ?>
                <li><a class="trans" href="<?=Url::to(['operator/index', 'id' => $favorite->id, 'slug' => $favorite->slug]);?>"><img class="img-radius" src="<?=$favorite->getImageUrl('50x50');?>" alt="<?=$favorite->name;?>"></a></li>
                <?php endforeach;?>
              </ul>
            </div>
          </div>
          <?php endif;?>
          <div class="sidebar-col widget-box">
            <div class="widget-head">Profile information</div>
            <div class="widget-inner">
              <ul class="profile-data">
                <li>
                  <p class="profile-ttl">Gender</p>
                  <p class="profile-text"><?=$user->getGenderLabel();?></p>
                </li>
                <li>
                  <p class="profile-ttl">Location</p>
                  <p class="profile-text"><?=$user->getCountryName();?></p>
                </li>
                <!-- <li>
                  <p class="profile-ttl">Interests</p>
                  <p class="profile-text">Drooling over Supercar beauties, but especially 2 - legged ones! Can never see enough of them, hehehe. ^^</p>
                </li> -->
              </ul>
            </div>
          </div>
        </aside>
      </div>
    </div>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a class="fa fa-home trans" href="/">Home</a></li>
      <li class="breadcrumb-item active"><?=$user->username;?></li>
    </ol>
  </div>
</main>