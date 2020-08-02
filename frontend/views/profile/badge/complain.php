<?php 
use common\components\helpers\TimeElapsed;
?>  
  <div class="timeline-post-row">
    <div class="timeline-group">
      <div class="timeline-icon"><i class="fas fa-thumbs-down"></i></div>
      <div class="timeline-ttl">You wrote a complaint: <?=$badge->description;?></div>
	  <div class="timeline-time"><i class="far fa-clock"></i><span><?=TimeElapsed::timeElapsed($badge->created_at);?></span></div>
    </div>
  </div>