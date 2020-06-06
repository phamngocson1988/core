<?php foreach ($models as $model) : ?>
<?php $user = $model->user;?>
<?php $operator = $model->operator;?>
<article class="review-item">
  <div class="review-user">
    <div class="user-photo"><img src="<?=$user->getAvatarUrl('100x100');?>" alt="<?=$user->getName();?>"></div>
    <div class="user-name"><a href="javascript:;"><?=$user->getName();?></a></div>
    <div class="user-meta"><span><?=number_format($user->countReview());?> reviews</span><span><?=$user->getCountryName();?></span></div>
    <div class="user-message"><a href="javascript:;"><i class="fas fa-envelope"></i> Message</a></div>
  </div>
  <div class="review-content">
    <div class="review-date">Reviewed on <span><?=date("F j, Y", strtotime($model->created_at));?></span></div>
    <div class="review-rate">
      <div class="star-rating"><span style="width:94.5%"></span></div>
    </div>
    <div class="review-text">
      <div class="review-text-positive">
        <p><?=$model->good_thing;?></p>
      </div>
      <div class="review-text-negative">
        <p><?=$model->bad_thing;?></p>
      </div>
    </div>
    <div class="review-more"><a href="#">Show more</a></div>
    <?php if ($model->reply) : ?>
    <div class="review-comments">
      <div class="review-comment">
        <div class="review-comment-header">
          <div class="user-photo"><img src="/img/common/sample_img_00.png" alt="Username"></div>
          <div class="user-name"><?=$operator->getImageUrl('50x50');?></div>
          <div class="comment-date">Replied on <?=date('F j, Y', strtotime($model->replied_at));?></div>
        </div>
        <div class="review-comment-content">
          <p><?=$model->reply;?></p>
        </div>
        <div class="review-more"><a href="#">Show more</a></div>
      </div>
    </div>
    <?php endif;?>
  </div>
</article>
<?php endforeach;?>