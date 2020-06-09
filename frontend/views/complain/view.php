<?php
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use common\components\helpers\TimeElapsed;
$this->title = sprintf("Complain - %s", $complain->title);
?>
<main>
  <section class="section-module">
    <div class="container">
      <section class="operator-hero complaint-hero widget-box">
        <div class="hero-main"><a class="hero-photo" href="#"><img src="<?=$operator->getImageUrl('150x150');?>" alt="Henderson &amp; Bench"></a>
          <div class="hero-info">
            <div class="hero-name-sm"><?=$operator->name;?></div>
            <h1 class="hero-title"><?=$complain->title;?></h1>
            <div class="hero-buttons"><a class="btn btn-outline-light" href="#">Follow <i class="fa fa-star-o"></i></a></div>
            <div class="hero-feature">
              <p><i class="fa fa-clock-o"></i> <?=ucfirst($complain->status);?> Case (<?=TimeElapsed::timeElapsed($complain->created_at);?>)</p>
            </div>
          </div>
        </div>
        <div class="hero-footer">
          <ul class="hero-nav">
            <li><a href="#"><i class="fa fa-info-circle"></i><span class="nav-text">Overview</span></a></li>
            <li><a href="#"><i class="fa fa-exclamation-circle"></i><span class="nav-text">Discussion</span></a></li>
            <li><a href="#"><i class="fa fa-comments"></i><span class="nav-text">Other Complaints (0)</span></a></li>
          </ul>
        </div>
      </section>
      <div class="sec-content">
        <div class="mod-column">
          <section class="operator-detail widget-box">
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
                <li>
                  <div class="label">
                    <div class="label-icon"><i class="fas fa-dollar-sign"></i></div>
                    <div class="label-text">Amount</div>
                  </div>
                  <div class="content">VND 1,500,000</div>
                </li>
              </ul>
            </div>
          </section>
          <section class="operator-review-group widget-box">
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
              <article class="review-item complaint-item">
                <div class="review-user">
                  <div class="user-photo"><img src="<?=$user->getAvatarUrl('100x100');?>" alt="<?=$user->name;?>"></div>
                  <div class="user-name"><a href="#"><?=$user->name;?></a></div>
                  <div class="user-meta"><span><?=$user->getCountryName();?></span></div>
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
                    <?= $form->field($replyForm, 'mark_close', [
                      'options' => ['class' => 'form-group form-check'],
                      'template' => '{input}{label}',
                      'inputOptions' => ['class' => 'form-check-input'],
                      'labelOptions' => ['class' => 'form-check-label']
                    ])->checkbox()->label('Mark to close this case');?>
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
          <section class="operator-complaint">
            <h2 class="sec-title text-center"><?=$operator->name;?> Complaints</h2>
            <ul class="complaint-stats">
              <li>Total 99 cases</li>
              <li>700/995 case resolved (90%)</li>
              <li>5 hours average response</li>
            </ul>
            <div class="row">
              <?php foreach ($complains as $complain) : ?>
              <div class="col-12 col-sm-6 col-md-6 col-lg-3">
                <div class="block-complaint">
                  <div class="complaint-image"><img src="/img/complain/<?=$complain->status;?>.jpg" alt="image"></div>
                  <div class="complaint-heading">
                    <p class="complaint-ttl"><?=strtoupper($complain->status);?> CASE</p>
                    <p><?=TimeElapsed::timeElapsed($complain->created_at);?></p>
                  </div>
                  <div class="complaint-desc"><?=$complain->title;?></div><a class="btn btn-primary" href="<?=Url::to(['complain/view', 'id' => $complain->id]);?>">READ MORE</a>
                </div>
              </div>
              <?php endforeach;?>
            </div>
            <div class="operator-sec-button"><a class="btn" href="<?=Url::to(['complain/index', 'operator_id' => $operator->id]);?>">See all <i class="fas fa-chevron-right"></i></a></div>
          </section>
          <section class="operator-trouble widget-box">
            <div class="trouble-title">Have trouble with <?=$operator->name;?>?</div>
            <div class="trouble-button"><a class="btn btn-lg trans" href="<?=Url::to(['complain/create', 'operator_id' => $operator->id]);?>">Submit complaint</a><a class="btn btn-lg trans" href="#">Learn more</a></div>
          </section>
        </div>
        <aside class="mod-sidebar">
          <div class="sidebar-col side-operator">
            <?=\frontend\widgets\TopOperatorWidget::widget();?>
          </div>
          <div class="sidebar-delineation"><a class="trans" href="#"><img src="../img/operators/img_01.jpg" alt="image"></a></div>
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
JS;
$this->registerJs($script);
?>