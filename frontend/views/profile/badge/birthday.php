<?php 
use common\components\helpers\TimeElapsed;
?>  
  <div class="timeline-post-row">
    <div class="timeline-group">
      <div class="timeline-icon"><i class="fas fa-birthday-cake"></i></div>
      <div class="timeline-ttl"><?=Yii::t('app', 'You updated your birthday');?></div>
	  <div class="timeline-time"><i class="far fa-clock"></i><span><?=TimeElapsed::timeElapsed($badge->created_at);?></span></div>
    </div>
  </div>