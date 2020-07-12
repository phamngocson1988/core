<?php
use common\components\helpers\TimeElapsed;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;

$creator = $topic->creator;
?>

<main>
  <div class="forum-container container">
    <div class="forum-main">
      <section class="section-thread-heading">
        <div class="heading-content"><a class="author-photo" href="javascript:;"><img src="<?=$creator->getAvatarUrl('150x150');?>" alt="<?=$creator->getName();?>"></a>
          <h1 class="thread-title"><a href="javascript:;"><?=$topic->subject;?></a></h1>
          <div class="thread-info">By <a href="javascript:;"><?=$creator->getName();?></a>, <?=TimeElapsed::timeElapsed($topic->created_at);?></div>
        </div>
        <div class="heading-button"><a class="btn btn-link" href="<?=Url::to(['forum/create']);?>">Start new topic</a><a class="btn btn-primary" href="#reply">Reply to this topic</a></div>
      </section>
      <section class="section-thread">
        <ol class="thread-list">
          <?php foreach ($posts as $post) : ?>
          <?php $sender = $post->sender;?>
          <li class="thread-row widget-box">
            <aside class="thread-panel">
              <div class="author-name"><a href="javascript:;"><?=$sender->getName();?></a></div>
              <div class="author-level">User Level 1</div><a class="author-photo" href="javascript:;"><img src="<?=$sender->getAvatarUrl('150x150');?>" alt="<?=$sender->getName();?>"></a>
              <div class="author-type">Member</div>
              <div class="author-point">115 Points</div>
              <div class="author-stat"><?=number_format($sender->countForumPost());?> posts</div>
            </aside>
            <div class="thread-main">
              <div class="thread-date">Posted <time datetime="<?=$post->created_at;?>"><?=date('F j, Y', strtotime($post->created_at));?></time></div>
              <div class="thread-content">
                <?=$post->content;?>
              </div>
              <div class="thread-reaction"><a href="#">Username 1</a>, <a href="#">Username 2</a> and <a href="#">26 others</a> liked this</div>
            </div>
          </li>
          <?php endforeach;?>
        </ol>
        <div class="thread-reply widget-box" id="reply">
          <h3 class="reply-title">Join the conversation</h3>
          <?php if (Yii::$app->user->isGuest) : ?>
          <div class="reply-desc">
            <p>You can post now and register later. If you have an account, <a href="#modalLogin">sign in now</a> to post with your account.</p>
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
          <button class="btn btn-primary" style="float: right" type="submit">Reply to this topic</button>
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
}
JS;
$this->registerJs($script);
?>