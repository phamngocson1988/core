<?php 
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use common\components\helpers\TimeElapsed;
?>
<main>
  <section class="section-profile-user">
    <div class="container">
      <div class="sec-heading-profile widget-box mb-4">
        <div class="heading-banner"><img class="object-fit" src="../img/profile/profile_bnr.jpg" alt="image"></div>
        <div class="heading-body">
          <div class="heading-avatar col-avatar">
            <div class="heading-image operator-avatar-background">
              <img class="object-fit operator-avatar" src="<?=$model->getImageUrl('150x150');?>" alt="image">
              <a class="edit-camera fas fa-camera trans" href="javascript:;"></a>
              <input type="file" id="upload-user-avatar-element" name="upload-user-avatar-element" style="display: none" multiple accept="image/*"/>
            </div>
            <h1 class="heading-name"><?=$model->name;?></h1>
          </div>
          <div class="heading-right">
            <ul class="profile-link profile-link-custom">
              <li class="edit-profile"><a class="trans" href="<?=Url::to(['manage/edit', 'id' => $model->id, 'slug' => $model->slug]);?>"><i class="fas fa-cog"></i><span>EDIT MY PAGE</span></a></li>
            </ul>
          </div>
        </div>
      </div>
      <div class="sec-content">
        <div class="mod-column">
          <div class="widget-box timeline-post">
            <div class="timeline-heading">
              <p class="heading-text mb-0">ALL ACTIVITIES</p>
              <div class="dropdown dropdown-fillter">
                <button class="dropdown-toggle" id="dropdownMenuButton" type="button" data-toggle="dropdown" aria-expanded="false"><i class="fas fa-glass-martini"></i>FILLTER</button>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                  <ul class="list-tabs tabs-none">
                    <li><a class="trans" href="<?=Url::to(['manage/review', 'id' => $model->id, 'slug' => $model->slug]);?>">Reviews (52)</a></li>
                    <li><a class="trans" href="<?=Url::to(['manage/complain', 'id' => $model->id, 'slug' => $model->slug]);?>">Complaints (49)</a></li>
                    <li><a class="trans" href="<?=Url::to(['manage/information', 'id' => $model->id, 'slug' => $model->slug]);?>">Page information</a></li>
                  </ul>
                </div>
              </div>
            </div>
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
                      <?php $form = ActiveForm::begin(['action' => Url::to(['manage/reply-review', 'id' => $review->id]), 'id' => 'reply-review-form']); ?>
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
                      <h3 class="complaint-title"><?=$complain->title;?></h3>
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
                    <div class="review-reply">
                      <?php $form = ActiveForm::begin(['action' => Url::to(['manage/reply-complain', 'id' => $complain->id]), 'id' => 'reply-complain-form']); ?>
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
                        'inputOptions' => ['value' => $model->id]
                      ])->hiddenInput()->label(false);?>

                      <div class="form-group">
                        <button class="btn btn-primary" type="submit">Post my reply</button>
                      </div>
                      <?php ActiveForm::end();?>
                    </div>
                  </div>
                </article>
                <?php endif;?>
              </div>
            </div>
          </div>
        </div>
        <div class="mod-sidebar">
          <div class="sidebar-col sidebar-category">
            <?=\frontend\widgets\ReviewStatByOperatorWidget::widget(['operator_id' => $model->id]);?>
            <?=\frontend\widgets\ComplainStatByOperatorWidget::widget(['operator_id' => $model->id]);?>
            
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
// upload image
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
    $('body').find('.operator-avatar').attr('src', thumb);
    $('body').find('.operator-avatar-background').attr('style', 'background-image: url("'+thumb+'")')
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
var reviewForm = new AjaxFormSubmit({
  element : 'form#reply-complain-form'
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
JS;
$uploadLink = Url::to(['image/ajax-upload']);
$updateAvatarLink = Url::to(['manage/update-avatar', 'id' => $model->id]);
$script = str_replace('###LINK###', $uploadLink, $script);
$script = str_replace('###UPDATEAVATAR###', $updateAvatarLink, $script);
$this->registerJs($script);
?>