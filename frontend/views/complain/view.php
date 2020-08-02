<?php
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use common\components\helpers\TimeElapsed;
$this->title = sprintf("Complain - %s", $complain->title);
$currentUser = Yii::$app->user->getIdentity();
?>
<main>
  <section class="section-module">
    <div class="container">
      <section class="operator-hero complaint-hero widget-box">
        <div class="hero-main">
          <a class="hero-photo" href="#"><img src="<?=$operator->getImageUrl('150x150');?>" alt="<?=$operator->name;?>"></a>
          <div class="hero-info">
            <div class="hero-name-sm"><?=$operator->name;?></div>
            <h1 class="hero-title"><?=$complain->title;?></h1>
            <div class="hero-buttons">
            <?php if ($currentUser) : ?>
            <?php $isFollow = $currentUser->isFollow($complain->id);?>
              <a class="btn btn-outline-light <?= $isFollow ? '' : 'd-none' ;?>" id="unfollow-complain" href="<?=Url::to(['complain/unfollow', 'id' => $complain->id]);?>">Unfollow <i class="fa fa-star-o"></i></a>
              <a class="btn btn-outline-light <?= $isFollow ? 'd-none' : '' ;?>" id="follow-complain" href="<?=Url::to(['complain/follow', 'id' => $complain->id]);?>">Follow <i class="fa fa-star"></i></a>
            <?php endif;?>
            </div>
            <div class="hero-feature">
              <p><i class="fa fa-clock-o"></i> <?=ucfirst($complain->status);?> Case (<?=TimeElapsed::timeElapsed($complain->created_at);?>)</p>
            </div>
          </div>
        </div>
        <div class="hero-footer">
          <ul class="hero-nav">
            <li><a href="#overview"><i class="fa fa-info-circle"></i><span class="nav-text">Overview</span></a></li>
            <li><a href="#discussion"><i class="fa fa-exclamation-circle"></i><span class="nav-text">Discussion</span></a></li>
            <li><a href="#complain"><i class="fa fa-comments"></i><span class="nav-text">Other Complaints (<?=$operator->totalComplain();?>)</span></a></li>
          </ul>
        </div>
      </section>
      <div class="sec-content">
        <div class="mod-column">
          <section class="operator-detail widget-box" id="overview">
            <h2 class="widget-head">
              <div class="head-text"><i class="fa fa-info-circle"></i><span class="text">Complaint Info</span></div>
            </h2>
            <div class="widget-content">
              <ul class="operator-detail-list">
                <li>
                  <div class="label">
                    <div class="label-icon"><i class="fas fa-globe-americas"></i></div>
                    <div class="label-text">Disputed Operator</div>
                  </div>
                  <div class="content"><a href="<?=Url::to(['operator/view', 'id' => $operator->id, 'slug' => $operator->slug]);?>"><?=$operator->name;?></a></div>
                </li>
                <li>
                  <div class="label">
                    <div class="label-icon"><i class="fa fa-language"></i></div>
                    <div class="label-text">Reason</div>
                  </div>
                  <div class="content"><a href="#"><?=$reason->title;?></a></div>
                </li>
                <?php if ($complain->files) : ?>
                <li>
                  <div class="label">
                    <div class="label-icon"><i class="fa fa-paperclip"></i></div>
                    <div class="label-text">Attached file</div>
                  </div>
                  <div class="content">
                    <?php foreach ($complain->files as $file) : ?>
                    <a href="<?=$file->file_id;?>" target="_blank">Attach File</a>
                    <?php endforeach;?>
                  </div>
                </li>
                <?php endif;?>
                <!-- <li>
                  <div class="label">
                    <div class="label-icon"><i class="fas fa-dollar-sign"></i></div>
                    <div class="label-text">Amount</div>
                  </div>
                  <div class="content">VND 1,500,000</div>
                </li> -->
              </ul>
            </div>
          </section>
          <section class="operator-review-group widget-box" id="discussion">
            <div class="review-list">
              <article class="review-item complaint-item">
                <div class="review-user">
                  <div class="user-photo"><img src="<?=$user->getAvatarUrl('100x100');?>" alt="<?=$user->name;?>"></div>
                  <div class="user-name"><a href="#"><?=$user->name;?></a></div>
                  <div class="user-meta"><span><?=$user->getCountryName();?></span></div>
                </div>
                <div class="review-content">
                  <div class="review-date">Posted on <?=date("F j, Y", strtotime($complain->created_at));?></div>
                  <div class="review-text"><?=$complain->description;?></div>
                </div>
              </article>
              <?php foreach ($replies as $reply) : ?>
              <?php if ($reply->operator_id) : ?>
              <article class="review-item complaint-item">
                <div class="review-user">
                  <div class="user-photo"><img src="<?=$operator->getImageUrl('100x100');?>" alt="<?=$operator->name;?>"></div>
                  <div class="user-name"><a href="<?=Url::to(['operator/view', 'id' => $operator->id, 'slug' => $operator->slug]);?>"><?=$operator->name;?></a></div>
                </div>
                <div class="review-content">
                  <div class="review-date">Posted on <?=date("F j, Y", strtotime($reply->created_at));?></div>
                  <div class="review-text"><?=$reply->description;?></div>
                </div>
              </article>
              <?php else : ?>
              <?php $userReply = $reply->user;?>
              <article class="review-item complaint-item">
                <div class="review-user">
                  <div class="user-photo"><img src="<?=$userReply->getAvatarUrl('100x100');?>" alt="<?=$userReply->name;?>"></div>
                  <div class="user-name"><a href="#"><?=$userReply->name;?></a></div>
                  <div class="user-meta"><span><?=$userReply->getCountryName();?></span></div>
                </div>
                <div class="review-content">
                  <div class="review-date">Posted on <?=date("F j, Y", strtotime($reply->created_at));?></div>
                  <div class="review-text"><?=$reply->description;?></div>
                </div>
              </article>
              <?php endif;?>
              <?php endforeach;?>
              
              <?php if ($canReply) : ?>
              <article class="review-item complaint-item">
                <div class="review-user">
                </div>
                <div class="review-content">
                  <?php $form = ActiveForm::begin(['action' => Url::to(['complain/reply', 'id' => $complain->id]), 'id' => 'add-reply-form']); ?>
                  <div class="review-reply" style="margin-top: 0px; padding-top:0px; border-top: none;">
                    <?= $form->field($replyForm, 'description', [
                      'template' => '{input}',
                      'inputOptions' => ['placeholder' => 'Reply...', 'rows' => 5, 'class' => 'form-control']
                    ])->textArea()->label(false);?>
                    <div class="form-group form-check">
                      <label class="form-check-label">
                        <?= $form->field($replyForm, 'mark_close', [
                          'options' => ['tag' => false],
                          'template' => '{input}',
                          'inputOptions' => ['class' => 'form-check-input'],
                        ])->checkbox(['class' => 'form-check-input'], false);?>
                        <span>Mark to close this case</span>
                      </label>
                    </div>
                    <div class="form-group">
                      <button class="btn btn-primary" type="submit">Post my reply</button>
                    </div>
                  </div>
                  <?php ActiveForm::end();?>
                </div>
              </article>
              <?php endif;?>
            </div>
          </section>
          <div class="section-complaint-back"><a class="btn btn-primary" href="<?=Url::to(['operator/view', 'id' => $operator->id, 'slug' => $operator->slug]);?>">Back to <?=$operator->name;?></a></div>
          <?=\frontend\widgets\OperatorComplainWidget::widget(['operator' => $operator]);?>
        </div>
        <aside class="mod-sidebar">
          <div class="sidebar-col side-operator">
            <?=\frontend\widgets\TopOperatorWidget::widget();?>
          </div>
          <?=\frontend\widgets\AdsWidget::widget(['position' => \frontend\models\Ads::POSITION_SIDEBAR]);?>
        </aside>
      </div>
    </div>
  </section>
</main>
<?php
$script = <<< JS
// Review Form
var reviewForm = new AjaxFormSubmit({
  element : 'form#add-reply-form'
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

// follow
$('#follow-complain').ajax_action({
  method: 'POST',
  callback: function(element, data) {
    $('#follow-complain').addClass('d-none');
    $('#unfollow-complain').removeClass('d-none');
    toastr.success(data.message);
  },
  error: function(errors) {
      toastr.error(errors);
  },
});

// unfollow
$('#unfollow-complain').ajax_action({
  method: 'POST',
  callback: function(element, data) {
    $('#unfollow-complain').addClass('d-none');
    $('#follow-complain').removeClass('d-none');
    toastr.success(data.message);
  },
  error: function(errors) {
      toastr.error(errors);
  },
});
JS;
$this->registerJs($script);
?>