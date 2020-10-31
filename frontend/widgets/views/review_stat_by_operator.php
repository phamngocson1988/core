<div class="category-row">
  <p class="category-title"><a class="trans" href="#"><i class="fas fa-pencil-alt"></i><?=Yii::t('app', 'Reviews');?> (<?=number_format($total);?>)</a></p>
  <div class="category-inner">
    <ul class="category-list">
      <li><a class="trans" href="#"><?=Yii::t('app', 'Unresponded reviews');?> (<?=number_format($unreply);?>)</a></li>
      <li><a class="trans" href="#"><?=Yii::t('app', 'Responded reviews');?> (<?=number_format($reply);?>)</a></li>
    </ul>
  </div>
</div>