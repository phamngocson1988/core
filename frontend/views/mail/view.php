<?php
use common\components\helpers\TimeElapsed;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
?>

<div class="sec-message-view review-list widget-box" style="display: block !important">
  <article class="review-item complaint-item">
    <div class="review-user">
      <div class="user-photo"><img src="<?=$thread->sender->getAvatarUrl('150x150');?>" alt="Usern<?=$thread->sender->username;?>"></div>
      <div class="user-name"><a href="javascript:;"><?=$thread->sender->username;?></a></div>
    </div>
    <div class="review-content">
      <div class="review-complaint-heading">
        <h3 class="complaint-title"><?=$thread->subject;?></h3>
        <div class="review-date"><?=TimeElapsed::timeElapsed($thread->updated_at);?></div>
      </div>
      <div class="review-text">
        <?php $firstMail = array_shift($mails);?>
        <?=$firstMail->content;?>
      </div>
      <div class="review-comments">
        <?php foreach ($mails as $mail) : ?>
        <div class="review-comment">
          <div class="review-comment-header">
            <div class="user-photo"><img src="<?=$mail->sender->getAvatarUrl('150x150');?>" alt="Username"></div>
            <div class="user-name"><?=($mail->created_by == Yii::$app->user->id) ? 'You' : $mail->sender->username;?></div>
            <div class="comment-date"><?=Yii::t('app', 'Replied on {date}', ['date' => date("F j, Y", strtotime($mail->created_at))]);?></div>
          </div>
          <div class="review-comment-content">
            <?=$mail->content;?>
          </div>
        </div>
        <?php endforeach;?>
      </div>
      <div class="review-reply">
        <?php $form = ActiveForm::begin(['action' => Url::to(['mail/reply', 'id' => $thread->id]), 'id' => 'reply-form']); ?>
        <?= $form->field($model, 'content', [
        ])->textArea(['rows' => '5', 'placeholder' => 'Reply...'])->label(false);?>
        <div class="form-group">
          <button class="btn btn-primary" type="submit"><?=Yii::t('app', 'Post my reply');?></button>
        </div>
        <?php ActiveForm::end();?>
      </div>
    </div>
  </article>
</div>