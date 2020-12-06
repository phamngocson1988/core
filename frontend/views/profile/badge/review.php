<?php 
use common\components\helpers\TimeElapsed;
?>    
  <div class="timeline-post-row">
    <div class="timeline-group">
      <div class="timeline-icon"><i class="fas fa-comments"></i></div>
      <div class="timeline-ttl"><?=Yii::t('app', 'You wrote a review');?>: <?=$badge->description;?></div>
      <div class="timeline-time"><i class="far fa-clock"></i><span><?=TimeElapsed::timeElapsed($badge->created_at);?></span></div>
    </div>
  </div>