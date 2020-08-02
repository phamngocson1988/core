<?php 
use yii\helpers\Url;
use common\components\helpers\TimeElapsed;
use yii\bootstrap\ActiveForm;

$currentUserId = Yii::$app->user->id;
?>
<?php foreach ($complains as $complain) :?>
<?php $user = $complain->user;?>
<?php $reason = $complain->reason;?>
<?php $replies = (array)$complain->replies;?>
<?php $lastReply = end($replies);?>
<article class="review-item complaint-item" data-id="<?=$complain->id;?>">
  <div class="review-user">
    <div class="user-photo"><img src="<?=$user->getAvatarUrl('150x150');?>" alt="<?=$user->getName();?>"></div>
    <div class="user-name"><a href="javascript:;"><?=$user->getName();?></a></div>
    <div class="user-meta"><span><?=$user->countComplain();?> complains</span><span><?=$user->getCountryName();?></span></div>
    <div class="user-message"><a href="javascript:;"><i class="fas fa-envelope"></i> Message</a></div>
  </div>
  <div class="review-content">
    <div class="review-date">Complained on <span><?=date("F j, Y", strtotime($complain->created_at));?></span></div>
    <div class="review-complaint-heading">
      <h3 class="complaint-title"><a href="<?=Url::to(['manage/detail-complain', 'operator_id' => $operator->id, 'slug' => $operator->slug, 'id' => $complain->id]);?>" class="disabled-link"><?=$complain->title;?></a></h3>
      <div class="complaint-status"><i class="fa fa-exclamation-circle"></i> <?=ucfirst($complain->status);?> Case (<?=TimeElapsed::timeElapsed($complain->created_at);?>)</div>
    </div>
    <div class="review-complaint-info">
      <div class="info-title">Complaint Info</div>
      <div class="list-operator-col">
        <div class="operator-col">
          <ul class="list-operator-text">
            <li>
              <div class="col-left">
                <div class="label-icon"><i class="fas fa-undo-alt"></i></div>
                <div class="label-text">Reason</div>
              </div>
              <div class="col-right"><a href="javascript:;"><?=$reason->title;?></a></div>
            </li>
          </ul>
        </div>
        <div class="operator-col">
          <ul class="list-operator-text">
            <li>
              <div class="col-left">
                <div class="label-text">Registered Username with Your Company</div>
              </div>
              <div class="col-right"><a href="javascript:;"><?=$complain->account_name;?></a></div>
            </li>
            <li>
              <div class="col-left">
                <div class="label-text">Registered Email with Your Company</div>
              </div>
              <div class="col-right"><?=$complain->account_email;?></div>
            </li>
          </ul>
        </div>
      </div>
    </div>
    <div class="review-text">
      <?=nl2br($complain->description);?>
      <?php if ($lastReply) : ?>
      <?php $lastUserReply = $lastReply->user;?>
      <p>FEPLIED BY <a href="#"><?=$currentUserId == $lastUserReply->id ? 'YOU' : $lastUserReply->getName();?></a> ON <?=date("F j, Y", strtotime($lastReply->created_at));?></p>
      <?=nl2br($lastReply->description);?>
      <?php endif;?>
    </div>
    <?php if ($complain->isOpen()) : ?>
    <?php if (!$complain->hasManager()):?>
    <div class="review-form-comment">
      <div class="form-comment-left form-check">
        <label class="form-check-label">
          <input class="form-check-input assign-to-me" type="checkbox"><a href="javascript:;" class="disabled-link assign-to-me">Respond to this complaint</a>
        </label>
      </div>
      <div class="form-comment-right"><span class="text">ASSIGN TO</span>
        <div class="form-group">
          <select class="form-control assign-to-admin">
            <?php foreach ($operator->listUserByRole('manager') as $manager) :?>
            <option value="<?=$manager->id;?>" selected><?=$manager->username;?></option>
            <?php endforeach;?>
          </select>
        </div>
      </div>
    </div>
    <?php else :?>
      <?php if ($complain->managed_by == Yii::$app->user->id) : ?>
      <div class="review-reply">
        <?php $form = ActiveForm::begin(['action' => Url::to(['manage/reply-complain', 'complain_id' => $complain->id, 'operator_id' => $operator->id, 'slug' => $operator->slug]), 'options' => ['class' => 'reply-complain-form']]); ?>

        <?= $form->field($complainForm, 'description', [
          'inputOptions' => ['placeholder' => 'Reply...', 'rows' => 5, 'class' => 'form-control']
        ])->textArea()->label(false);?>
        
        <div class="form-group">
          <!-- <div class="form-check form-check-inline">
            <label class="form-check-label">
              <input class="form-check-input" type="checkbox" value="option1"><span>Reject this case</span>
            </label>
          </div> -->
          <?= $form->field($complainForm, 'mark_close', [
            'options' => ['class' => 'form-check form-check-inline'],
            'template' => '{input}{label}',
            'inputOptions' => ['class' => 'form-check-input'],
            'labelOptions' => ['class' => 'form-check-label']
          ])->checkbox()->label('Close this case');?>
        </div>
        
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
    <?php endif;?>
    <?php endif;?>
  </div>
</article>
<?php endforeach;?>