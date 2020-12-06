<?php 
use yii\helpers\Url;
?>
<div class="category-row">
  <p class="category-title"><a class="trans" href="<?=Url::to(['manage/review', 'operator_id' => $operator->id, 'slug' => $operator->slug]);?>"><i class="fas fa-pencil-alt"></i><?=Yii::t('app', 'Reviews');?> (<?=number_format($total);?>)</a></p>
  <div class="category-inner">
    <ul class="category-list">
      <li><a class="trans" href="#"><?=Yii::t('app', 'Unresponded reviews');?> (<?=number_format($unreply);?>)</a></li>
      <li><a class="trans" href="#"><?=Yii::t('app', 'Responded reviews');?> (<?=number_format($reply);?>)</a></li>
    </ul>
  </div>
</div>