<?php
use yii\helpers\Url;
?>
<div class="category-row">
    <p class="category-title"><a class="trans" href="#"><i class="fas fa-users"></i><?=Yii::t('app', 'Manage users');?></a></p>
    <div class="category-inner">
      <ul class="category-list">
        <li><a class="trans" href="<?=Url::to(['manage-staff/admin', 'operator_id' => $operator->id, 'slug' => $operator->slug]);?>"><?=Yii::t('app', 'Page admins');?> (<?=$countAdmin;?>)</a></li>
        <li><a class="trans" href="<?=Url::to(['manage-staff/sub-admin', 'operator_id' => $operator->id, 'slug' => $operator->slug]);?>"><?=Yii::t('app', 'Sub admins');?> (<?=$countSubAdmin;?>)</a></li>
        <li><a class="trans" href="<?=Url::to(['manage-staff/moderator', 'operator_id' => $operator->id, 'slug' => $operator->slug]);?>"><?=Yii::t('app', 'Forum representtatives');?> (<?=$countModerator;?>)</a></li>
      </ul>
    </div>
  </div>