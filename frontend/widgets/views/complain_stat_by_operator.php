<?php 
use yii\helpers\Url;
?>
<div class="category-row">
  <p class="category-title"><a class="trans" href="<?=$complainLink;?>"><i class="fas fa-comment"></i><?=Yii::t('app', 'Complaints');?> (<?=number_format($total);?>)</a></p>
  <div class="category-inner">
    <ul class="category-list">
      <li><a class="trans" href="#"><?=Yii::t('app', 'Open cases');?> (<?=number_format($open);?>)</a></li>
      <li><a class="trans" href="#"><?=Yii::t('app', 'Resolved');?> (<?=number_format($resolve);?>)</a></li>
      <li><a class="trans" href="#"><?=Yii::t('app', 'Rejected');?> (<?=number_format($reject);?>)</a></li>
    </ul>
  </div>
</div>