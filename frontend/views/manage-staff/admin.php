<?php
use yii\helpers\Url;
use frontend\models\Complain;
?>
<main>
  <section class="section-profile-user">
    <div class="container">
      <?php echo $this->render('@frontend/views/manage/header.php', ['operator' => $operator]);?>
      <h2 class="sec-heading-title">><?=Yii::t('app', 'Admin');?> (<?=count($users);?>)</h2>
      <div class="widget-box timeline-post">
        <div class="timeline-heading">
          <div class="heading-text mb-0"></div>
          <div class="dropdown dropdown-fillter">
            <button class="dropdown-toggle" id="dropdownMenuButton" type="button" data-toggle="dropdown" aria-expanded="false"><i class="fas fa-glass-martini"></i>FILLTER</button>
            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
              <ul class="list-tabs tabs-none">
                <li><a class="trans" href="<?=Url::to(['manage-staff/admin', 'operator_id' => $operator->id, 'slug' => $operator->slug]);?>"><?=Yii::t('app', 'Admin');?> </a></li>
                <li><a class="trans" href="<?=Url::to(['manage-staff/sub-admin', 'operator_id' => $operator->id, 'slug' => $operator->slug]);?>"><?=Yii::t('app', 'Sub admin');?> </a></li>
                <li><a class="trans" href="<?=Url::to(['manage-staff/moderator', 'operator_id' => $operator->id, 'slug' => $operator->slug]);?>"><?=Yii::t('app', 'Moderator');?> </a></li>
              </ul>
            </div>
          </div>
        </div>
        <div class="widget-main">
          <div class="review-list">
            <?php foreach ($users as $user) : ?>
            <article class="review-item complaint-item">
              <div class="review-user">
                <div class="user-photo"><img src="<?=$user->getAvatarUrl('150x150');?>" alt="<?=$user->getName();?>"></div>
                <div class="user-name"><a href="<?=Url::to(['member/index', 'username' => $user->username]);?>"><?=$user->username;?></a></div>
                <div class="user-message"><i class="fas fa-user-secret"></i> ><?=Yii::t('app', 'Admin');?></div>
              </div>
              <div class="review-content">
                <div class="review-date"><?=Yii::t('app', 'Joined');?> <span><?=date("F j, Y", strtotime($user->created_at));?></span></div>
                <div class="review-complaint-heading">
                  <h3 class="complaint-title"><?=$user->getName();?></h3>
                  <div class="complaint-status"><i class="fa fa-envelope"></i> <?=$user->email;?></div>
                </div>
              </div>
            </article>
            <?php endforeach;?>
          </div>
        </div>
      </div>
    </div>
  </section>
</main>
<?php
$script = <<< JS
JS;
$this->registerJs($script);
?>