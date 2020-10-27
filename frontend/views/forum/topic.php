<?php
use common\components\helpers\TimeElapsed;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;

$creator = $topic->creator;
$user = Yii::$app->user->getIdentity();
?>

<main>
  <div class="forum-container container">
    <div class="forum-main">
      <section class="section-thread-heading">
        <div class="heading-content"><a class="author-photo" href="javascript:;"><img src="<?=$creator->getAvatarUrl('150x150');?>" alt="<?=$creator->getName();?>"></a>
          <h1 class="thread-title"><a href="javascript:;"><?=$topic->subject;?></a></h1>
          <div class="thread-info"><?=Yii::t('app', 'By {user}', ['user' => sprintf('<a href="%s">%s</a>', Url::to(['member/index', 'username' => $creator->username]), $creator->getName())]);?>, <?=TimeElapsed::timeElapsed($topic->created_at);?></div>
        </div>
        <?php if (!Yii::$app->user->isGuest) : ?>
        <div class="heading-button"><a class="btn btn-link" href="<?=Url::to(['forum/create']);?>"><?=Yii::t('app', 'Start new topic');?></a><a class="btn btn-primary" href="#reply"><?=Yii::t('app', 'Reply to this topic');?></a></div>
        <?php endif;?>
      </section>
      <section class="section-thread">
        <ol class="thread-list">
          <?php foreach ($posts as $post) : ?>
          <?php $sender = $post->sender;?>
          <li class="thread-row widget-box">
            <aside class="thread-panel">
              <div class="author-name"><a href="<?=Url::to(['member/index', 'username' => $sender->username]);?>"><?=$sender->getName();?></a></div>
              <div class="author-level"><?=Yii::t('app', 'User Level');?> <?=$sender->getLevel();?></div><a class="author-photo" href="javascript:;"><img src="<?=$sender->getAvatarUrl('150x150');?>" alt="<?=$sender->getName();?>"></a>
              <div class="author-type"><?=Yii::t('app', 'Member');?></div>
              <div class="author-point"><?=Yii::t('app', '{count} Points', ['count' => number_format($sender->totalPoint())]);?></div>
              <div class="author-stat"><?=Yii::t('app', '{count} posts', ['count' => number_format($sender->countForumPost())]);?></div>
            </aside>
            <div class="thread-main">
              <div class="thread-date"><?=Yii::t('app', 'Posted {time}', ['time' => sprintf('<time datetime="%s">%s</time>', $post->created_at, date('F j, Y', strtotime($post->created_at)))]);?></div>
              <div class="thread-content">
                <?=nl2br($post->content);?>
              </div>
              <div class="thread-reaction">
                <?php if ($user) :?>
                <?php 
                $isLike = $user->isLike($post->id);
                $likeLink = Url::to(['forum/like', 'id' => $post->id]);
                $dislikeLink = Url::to(['forum/dislike', 'id' => $post->id]);
                ?>
                <a class="like-btn <?=$isLike ? 'liked' : '';?>" href="<?=$isLike ? $dislikeLink : $likeLink;?>" data-like="<?=$likeLink;?>" data-dislike="<?=$dislikeLink;?>" data-username="<?=$user->username;?>"></a>
                <?php endif;?>
                <?php
                $userLikes = $post->userLike;
                $firstUserLike = (array)array_slice($userLikes, 0, 2);
                $remainingUserLike = count($userLikes) - 2; 
                ?>
                <?php foreach ($firstUserLike as $firstUser) : ;?>
                  <a href="<?=Url::to(['member/index', 'username' => $firstUser->username]);?>" data-post-id="<?=$post->id;?>" data-user-id="<?=$firstUser->id;?>"><?=$firstUser->username;?></a>
                <?php endforeach;?>
                <?php if ($remainingUserLike > 0) :?>
                <?=Yii::t('app', 'and <a href="javascript:;">{count} others</a> liked this', ['count' => number_format($remainingUserLike)]);?>
                <?php endif;?>
              </div>
            </div>
          </li>
          <?php endforeach;?>
        </ol>
        <div class="thread-reply widget-box" id="reply">
          <h3 class="reply-title"><?=Yii::t('app', 'Join the conversation');?></h3>
          <?php if (Yii::$app->user->isGuest) : ?>
          <div class="reply-desc">
            <p><?=Yii::t('app', 'You can post now and register later. If you have an account, <a href="#modalLogin" data-toggle="modal">sign in now</a> to post with your account.');?></p>
          </div>
          <?php else : ?>
          <?php $user = Yii::$app->user->getIdentity();?>
          <?php $form = ActiveForm::begin(['action' => Url::to(['forum/reply', 'id' => $topic->id]), 'id' => 'reply-topic-form']); ?>
          <div class="reply-form">
            <div class="form-photo">
              <div class="photo"><img src="<?=$user->getAvatarUrl('150x150');?>" alt="<?=$user->getName();?>"></div>
            </div>
            <div class="form-main">
              <?= $form->field($model, 'content', [
                'inputOptions' => ['class' => 'form-control', 'placeholder' => 'Reply to this topic', 'rows' => '2'],
                'template' => '{input}'
              ])->textArea()->label(false);?>
            </div>
          </div>
          <button class="btn btn-primary" style="float: right" type="submit"><?=Yii::t('app', 'Reply to this topic');?></button>
          <?php ActiveForm::end();?>
          <?php endif;?>
        </div>
      </section>
    </div>
    <aside class="forum-sidebar">
      <div class="side-delineation widget-box"></div>
    </aside>
  </div>
</main>

<?php
$script = <<< JS
// Review Form
var topic = new AjaxFormSubmit({
  element : 'form#reply-topic-form'
});
topic.error = function (errors) {
  toastr.error(errors);
}
topic.success = function (data, form) {
  toastr.success(data.message);
  setTimeout(() => {  
    location.reload();
  }, 1000);
};

$('a.like-btn').ajax_action({
  method: 'POST',
  callback: function(element, data) {
    if ($(element).hasClass('liked')) {
      $(element).attr('href', $(element).data('dislike'));
    } else {
      $(element).attr('href', $(element).data('like'));
    }
    location.reload();
    // $(element).toggleClass('liked');
  },
  error: function(errors) {
      console.log(errors);
  },
});
JS;
$this->registerJs($script);
?>