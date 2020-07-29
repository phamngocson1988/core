<?php 
use yii\helpers\Url;
use common\components\helpers\TimeElapsed;
use yii\bootstrap\ActiveForm;
?>
<?php foreach ($complains as $complain) :?>
<?php $user = $complain->user;?>
<?php $reason = $complain->reason;?>
<?php $replies = $complain->replies;?>
<article class="review-item complaint-item">
  <div class="review-user">
    <div class="user-photo"><img src="<?=$user->getAvatarUrl('150x150');?>" alt="Username"></div>
    <div class="user-name"><a href="javascript:;"><?=$user->getName();?></a></div>
    <div class="user-meta"><span><?=$user->countComplain();?> complains</span><span><?=$user->getCountryName();?></span></div>
    <div class="user-message"><a href="javascript:;"><i class="fas fa-envelope"></i> Message</a></div>
  </div>
  <div class="review-content">
    <div class="review-date">Complained on <span><?=date("F j, Y", strtotime($complain->created_at));?></span></div>
    <div class="review-complaint-heading">
      <h3 class="complaint-title"><a href="<?=Url::to(['manage/detail-complain', 'operator_id' => $operator->id, 'slug' => $operator->slug, 'id' => $complain->id]);?>"><?=$complain->title;?></a></h3>
      <div class="complaint-status"><i class="fa fa-exclamation-circle"></i> <?=ucfirst($complain->status);?> Case (<?=TimeElapsed::timeElapsed($complain->created_at);?>)</div>
    </div>
    <div class="review-complaint-info">
      <div class="info-title">Complaint Info</div>
      <ul class="operator-detail-list">
        <li>
          <div class="label">
            <div class="label-icon"><i class="fas fa-undo-alt"></i></div>
            <div class="label-text">Reason</div>
          </div>
          <div class="content"><a href="javascript:;"><?=$reason->title;?></a></div>
        </li>
        <!-- <li>
          <div class="label">
            <div class="label-icon"><i class="fas fa-dollar-sign"></i></div>
            <div class="label-text">Amount</div>
          </div>
          <div class="content">VND 1,500,000</div>
        </li> -->
      </ul>
    </div>
    <div class="review-text">
      <?=$complain->description;?>
    </div>
    <!-- <div class="review-more"><a href="#">Show more</a></div> -->
    <div class="review-comments">
      <?php foreach ($replies as $reply) :?>
      <?php $userReply = $reply->user;?>
      <div class="review-comment">
        <div class="review-comment-header">
          <div class="user-photo"><img src="<?=$userReply->getAvatarUrl('50x50');?>" alt="$userReply->username;?>"></div>
          <div class="user-name"><?=$userReply->getName();?></div>
          <div class="comment-date">Replied on <?=date("F j, Y", strtotime($reply->created_at));?></div>
        </div>
        <div class="review-comment-content">
          <p><?=$reply->description;?></p>
        </div>
        <!-- <div class="review-more"><a href="#">Show more</a></div> -->
      </div>
      <?php endforeach;?>
    </div>
    <?php if ($complain->isOpen()): ;?>
    <div class="review-reply">
      <?php $form = ActiveForm::begin(['action' => Url::to(['manage/reply-complain', 'complain_id' => $complain->id, 'operator_id' => $operator->id, 'slug' => $operator->slug]), 'options' => ['class' => 'reply-complain-form']]); ?>
      <?= $form->field($complainForm, 'description', [
        'inputOptions' => ['placeholder' => 'Reply...', 'rows' => 5, 'class' => 'form-control']
      ])->textArea()->label(false);?>
      <?= $form->field($complainForm, 'mark_close', [
        'options' => ['class' => 'form-group form-check'],
        'template' => '{input}{label}',
        'inputOptions' => ['class' => 'form-check-input'],
        'labelOptions' => ['class' => 'form-check-label']
      ])->checkbox()->label('Mark to close this case');?>

      <?= $form->field($complainForm, 'operator_id', [
        'options' => ['tag' => false],
        'template' => '{input}',
        'inputOptions' => ['value' => $operator->id]
      ])->hiddenInput()->label(false);?>

      <div class="form-group">
        <button class="btn btn-primary reply-complain-button" type="button">Post my reply</button>
      </div>
      <?php ActiveForm::end();?>
    </div>
    <?php endif;?>
  </div>
</article>
<?php endforeach;?>