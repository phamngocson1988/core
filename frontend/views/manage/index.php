<?php 
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use common\components\helpers\TimeElapsed;
$complainLink = $isAdmin 
  ? Url::to(['manage/complain', 'operator_id' => $operator->id, 'slug' => $operator->slug]) 
  : Url::to(['manage/my-complain', 'operator_id' => $operator->id, 'slug' => $operator->slug]);
?>
<main>
  <section class="section-profile-user">
    <div class="container">
      <?php echo $this->render('@frontend/views/manage/header.php', ['operator' => $operator]);?>
      <div class="sec-content">
        <div class="mod-column">
          <div class="widget-box timeline-post">
            <div class="timeline-heading">
              <p class="heading-text mb-0">ALL ACTIVITIES</p>
              <div class="dropdown dropdown-fillter">
                <button class="dropdown-toggle" id="dropdownMenuButton" type="button" data-toggle="dropdown" aria-expanded="false"><i class="fas fa-glass-martini"></i>FILLTER</button>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                  <ul class="list-tabs tabs-none">
                    <li><a class="trans" href="<?=Url::to(['manage/review', 'operator_id' => $operator->id, 'slug' => $operator->slug]);?>">Reviews (<?=number_format($operator->countReview());?>)</a></li>
                    <li><a class="trans" href="<?=$complainLink;?>">Complaints (<?=number_format($operator->totalComplain());?>)</a></li>
                    <!-- <li><a class="trans" href="<?=Url::to(['manage/information', 'operator_id' => $operator->id, 'slug' => $operator->slug]);?>">Page information</a></li> -->
                  </ul>
                </div>
              </div>
            </div>
            <?php if (!$review && !$complain) : ?>
            <div class="widget-main">
              <div class="review-list">
                <center style="padding: 10px 0">No thing found</center>
              </div>
            </div>
            <?php endif;?>
            <div class="widget-main">
              <div class="review-list">
                <?php if ($review) :?>
                <?php $user = $review->user;?>
                <?php $percent = $review->star * 10;?>
                <article class="review-item">
                  <div class="review-user">
                    <div class="user-photo"><img src="<?=$user->getAvatarUrl('100x100');?>" alt="<?=$user->getName();?>"></div>
                    <div class="user-name"><a href="javascript:;"><?=$user->getName();?></a></div>
                    <div class="user-meta"><span><?=number_format($user->countReview());?> reviews</span><span><?=$user->getCountryName();?></span></div>
                    <div class="user-message"><a href="javascript:;"><i class="fas fa-envelope"></i> Message</a></div>
                  </div>
                  <div class="review-content">
                    <div class="review-date">Reviewed on <span><?=date("F j, Y", strtotime($review->created_at));?></span></div>
                    <div class="review-rate">
                      <div class="star-rating"><span style="width:<?=$percent;?>%"></span></div>
                    </div>
                    <div class="review-text">
                      <div class="review-text-positive">
                        <p><?=$review->good_thing;?></p>
                      </div>
                      <div class="review-text-negative">
                        <p><?=$review->bad_thing;?></p>
                      </div>
                    </div>
                    <div class="review-reply">
                      <?php $form = ActiveForm::begin(['action' => Url::to(['manage/reply-review', 'review_id' => $review->id, 'operator_id' => $operator->id, 'slug' => $operator->slug]), 'id' => 'reply-review-form']); ?>
                      <?= $form->field($reviewForm, 'reply', [
                        'inputOptions' => ['placeholder' => 'Reply...', 'rows' => 5, 'class' => 'form-control']
                      ])->textArea()->label(false);?>
                      <div class="form-group">
                        <button class="btn btn-primary" type="submit">Post my reply</button>
                      </div>
                      <?php ActiveForm::end();?>
                    </div>
                  </div>
                </article>
                <?php endif;?>
                <?php if ($complain) : ?>
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
                      <h3 class="complaint-title"><a href="<?=Url::to(['manage/detail-complain', 'operator_id' => $operator->id, 'slug' => $operator->slug, 'id' => $complain->id]);?>" class="disabled-link"><?=$complain->title;?></a></h3>
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
                    <!-- <div class="review-reply">
                      <?php $form = ActiveForm::begin(['action' => Url::to(['manage/reply-complain', 'complain_id' => $complain->id, 'operator_id' => $operator->id, 'slug' => $operator->slug]), 'id' => 'reply-complain-form']); ?>
                      <?= $form->field($complainForm, 'description', [
                        'inputOptions' => ['placeholder' => 'Reply...', 'rows' => 5, 'class' => 'form-control']
                      ])->textArea()->label(false);?>
                      <div class="form-group form-check">
                        <label class="form-check-label">
                          <?= $form->field($complainForm, 'mark_close', [
                            'options' => ['tag' => false],
                            'template' => '{input}',
                          ])->checkbox(['class' => 'form-check-input'], false);?>
                          <span>Mark to close this case</span>
                        </label>
                      </div>

                      <?= $form->field($complainForm, 'operator_id', [
                        'options' => ['tag' => false],
                        'template' => '{input}',
                        'inputOptions' => ['value' => $operator->id]
                      ])->hiddenInput()->label(false);?>

                      <div class="form-group">
                        <button class="btn btn-primary" type="submit">Post my reply</button>
                      </div>
                      <?php ActiveForm::end();?>
                    </div> -->
                  </div>
                </article>
                <?php endif;?>
              </div>
            </div>
          </div>
        </div>
        <div class="mod-sidebar">
          <div class="sidebar-col sidebar-category">
            <?=\frontend\widgets\ReviewStatByOperatorWidget::widget(['operator_id' => $operator->id]);?>
            <?=\frontend\widgets\ComplainStatByOperatorWidget::widget(['operator_id' => $operator->id]);?>
            
            <div class="category-row">
              <p class="category-title"><a class="trans" href="#"><i class="fas fa-users"></i>Manage users</a></p>
              <div class="category-inner">
                <ul class="category-list">
                  <li><a class="trans" href="#">Page admins (0)</a></li>
                  <li><a class="trans" href="#">Forum representtatives (0)</a></li>
                </ul>
              </div>
            </div>
          </div>
          <div class="sidebar-col widget-box">
            <div class="widget-title">PAGE'S STATS</div>
            <div class="widget-body">
              <ul class="stats-list">
                <li>Create since
                  <p class="text">March 29,2019</p>
                </li>
                <li>Total Visits
                  <p class="text">1</p>
                </li>
                <li>Bonus Claims
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
// Review Form
var reviewForm = new AjaxFormSubmit({
  element : 'form#reply-review-form'
});
reviewForm.error = function (errors) {
  toastr.error(errors);
}
reviewForm.success = function (data, form) {
  toastr.success(data.message);
  setTimeout(() => {  
    location.reload();
  }, 1000);
}

// Complain Form
var compLainForm = new AjaxFormSubmit({
  element : 'form#reply-complain-form'
});
compLainForm.error = function (errors) {
  toastr.error(errors);
}
compLainForm.success = function (data, form) {
  toastr.success(data.message);
  setTimeout(() => {  
    location.reload();
  }, 1000);
}
JS;
$uploadLink = Url::to(['image/ajax-upload']);
$updateAvatarLink = Url::to(['manage/update-avatar', 'id' => $operator->id]);
$script = str_replace('###LINK###', $uploadLink, $script);
$script = str_replace('###UPDATEAVATAR###', $updateAvatarLink, $script);
$this->registerJs($script);
?>