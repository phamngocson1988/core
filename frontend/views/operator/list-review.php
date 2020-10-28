<?php foreach ($models as $model) : ?>
<?php $user = $model->user;?>
<?php $operator = $model->operator;?>
<?php $percent = $model->star * 10;?>
<article class="review-item">
  <div class="review-user">
    <div class="user-photo"><img src="<?=$user->getAvatarUrl('100x100');?>" alt="<?=$user->getName();?>"></div>
    <div class="user-name"><a href="javascript:;"><?=$user->getName();?></a></div>
    <div class="user-meta"><span><?=Yii::t('app', '{count} reviews', ['count' => number_format($user->countReview())]);?></span><span><?=$user->getCountryName();?></span></div>
    <div class="user-message"><a href="javascript:;"><i class="fas fa-envelope"></i> <?=Yii::t('app', 'Message');?></a></div>
  </div>
  <div class="review-content">
    <div class="review-date"><?=Yii::t('app', 'Reviewed on');?> <span><?=date("F j, Y", strtotime($model->created_at));?></span></div>
    <div class="review-rate">
      <div class="star-rating"><span style="width:<?=$percent;?>%"></span></div>
    </div>
    <div class="review-text">
      <div class="review-text-positive">
        <p><?=$model->good_thing;?></p>
      </div>
      <div class="review-text-negative">
        <p><?=$model->bad_thing;?></p>
      </div>
    </div>
    <!-- <div class="review-more"><a href="#">Show more</a></div> -->
    <?php if ($model->reply) : ?>
    <div class="review-comments">
      <div class="review-comment">
        <div class="review-comment-header">
          <div class="user-photo"><img src="<?=$operator->getImageUrl('50x50');?>" alt="Username"></div>
          <div class="user-name"><?=$operator->name;?></div>
          <div class="comment-date"><?=Yii::t('app', 'Reviewed on');?> <?=date('F j, Y', strtotime($model->replied_at));?></div>
        </div>
        <div class="review-comment-content">
          <p><?=$model->reply;?></p>
        </div>
        <!-- <div class="review-more"><a href="#">Show more</a></div> -->
      </div>
    </div>
    <?php endif;?>
  </div>
</article>
<?php endforeach;?>