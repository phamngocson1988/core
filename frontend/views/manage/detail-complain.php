<?php
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
?>
<main>
  <section class="section-module">
    <div class="container">
      <?php echo $this->render('@frontend/views/manage/header.php', ['operator' => $operator]);?>
      <section class="operator-detail widget-box">
        <h2 class="widget-head">
          <div class="head-text"><i class="fa fa-info-circle"></i><span class="text">Complaint Info</span></div>
        </h2>
        <div class="widget-content">
          <ul class="operator-detail-list">
            <li>
              <div class="label">
                <div class="label-icon"></div>
                <div class="label-text">Disputed Operator</div>
              </div>
              <div class="content"><a href="<?=Url::to(['operator/view', 'id' => $operator->id, 'slug' => $operator->slug]);?>"><?=$operator->name;?></a></div>
            </li>
            <li>
              <div class="label">
                <div class="label-icon"></div>
                <div class="label-text">Registered Username</div>
              </div>
              <div class="content"><a href="javascript:;"><?=$complain->account_name;?></a></div>
            </li>
            <li>
              <div class="label">
                <div class="label-icon"></div>
                <div class="label-text">Reason</div>
              </div>
              <div class="content"><a href="javascript:;"><?=$reason->title;?></a></div>
            </li>
            <li>
              <div class="label">
                <div class="label-icon"></div>
                <div class="label-text">Registered Email</div>
              </div>
              <div class="content"><a href="javascript:;"><?=$complain->account_email;?></a></div>
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
              <?php $form = ActiveForm::begin(['action' => Url::to(['manage/reply-complain', 'complain_id' => $complain->id, 'operator_id' => $operator->id, 'slug' => $operator->slug]), 'options' => ['class' => 'reply-complain-form']]); ?>
              <div class="review-reply" style="border: none; margin-top: 0">
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
                  <button class="btn btn-primary reply-complain-button" type="button">Post my reply</button>
                </div>
              </div>
              <?php ActiveForm::end();?>
            </div>
          </article>
          <?php endif;?>
        </div>
      </section>
    </div>
  </section>
</main>
<?php
$script = <<< JS
$('.review-list').on('click', '.reply-complain-button', function() {
  var form = $(this).closest('form.reply-complain-form');
  $.ajax({
    url: form.attr('action'),
    type: form.attr('method'),
    dataType : 'json',
    data: form.serialize(),
    success: function (result, textStatus, jqXHR) {
        if (result.status == false) {
          toastr.error(result.errors);
        } else {
          toastr.success(result.data.message);
          setTimeout(() => {  
            location.reload();
          }, 1000);
        }
    },
  });
  return false;
});
JS;
$this->registerJs($script);
?>