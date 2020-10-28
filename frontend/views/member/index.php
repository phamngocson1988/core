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
          <p class="profile-member"><?=Yii::t('app', 'Member');?></p>
          <ul class="profile-text">
            <li>
              <p class="text"><?=Yii::t('app', 'Joined');?></p>
              <p class="date"><?=date("F j, Y", strtotime($user->created_at));?></p>
            </li>
            <?php if ($user->last_login): ?>
            <li>
              <p class="text"><?=Yii::t('app', 'Last visited');?></p>
              <p class="date"><?=date("F j, Y", strtotime($user->last_login));?></p>
            </li>
            <?php endif;?>
          </ul>
        </div>
      </div>
      <div class="forum-profile">
        <div class="forum-main">
          <section class="profile-activity">
            <h2 class="activity-title"><?=Yii::t('app', 'Activities');?></h2>
            <div class="activity-list">
              <?php foreach ($posts as $post) : ?>
              <?php $topic = $post->topic;?>
              <article class="activity-topic"><a class="user-photo" href="javascript:;"><img src="<?=$user->getAvatarUrl('50x50');?>" alt="<?=$user->username;?>"></a>
                <div class="topic-main">
                  <h3 class="topic-title"><a href="<?=Url::to(['forum/topic', 'id' => $topic->id, 'slug' => $topic->slug]);?>"><?=$topic->subject;?></a></h3>
                  <div class="topic-reaction"><?=Yii::t('app', "<a href='javascript:;'>{user}</a> replied to <a href='{author_link}'>{author}</a>'s topic in <a href='{topic_link}'>{topic}</a>", ['user' => $user->username, 'author' => $topic->creator->username, 'topic' => $topic->subject, 'author_link' => Url::to(['member/index', 'username' => $topic->creator->username]), 'topic_link' => Url::to(['forum/topic', 'id' => $topic->id, 'slug' => $topic->slug])]);?></div>
                  <div class="topic-content">
                    <p><?=nl2br($post->content);?></p>
                  </div>
                  <div class="topic-bottom"><a href="#"><i class="fa fa-clock"></i> <?=TimeElapsed::timeElapsed($topic->created_at);?></a><a href="javascript:;"><i class="fa fa-comment"></i> <?=Yii::t('app', '{count} replied', ['count' => number_format($topic->countPost())]);?></a></div>
                </div>
              </article>
            <?php endforeach;?>
            </div>
          </section>
        </div>
        <aside class="forum-sidebar">
          <div class="sidebar-col widget-box"><a class="btn-profile trans" href="#"><i class="fas fa-trophy"></i><span><?=Yii::t('app', 'badge');?></span></a></div>
          <div class="widget-box side-reputation">
            <p class="reputation-ttl"><?=Yii::t('app', 'community reputation');?></p>
            <p class="reputation-number"><?=number_format($user->totalPoint());?></p><span><?=Yii::t('app', 'Points');?></span>
          </div>
          <?php if ($favorites) : ?>
          <div class="sidebar-col widget-box">
            <div class="widget-head"><?=Yii::t('app', "{count} favorites", ['count' => number_format($totalFavorite)]);?></div>
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
            <div class="widget-head"><?=Yii::t('app', 'Profile information');?></div>
            <div class="widget-inner">
              <ul class="profile-data">
                <li>
                  <p class="profile-ttl"><?=Yii::t('app', 'Gender');?></p>
                  <p class="profile-text"><?=$user->getGenderLabel();?></p>
                </li>
                <li>
                  <p class="profile-ttl"><?=Yii::t('app', 'Location');?></p>
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
      <li class="breadcrumb-item"><a class="fa fa-home trans" href="/"><?=Yii::t('app', 'Home');?></a></li>
      <li class="breadcrumb-item active"><?=$user->username;?></li>
    </ol>
  </div>
</main>