<?php 
use common\components\helpers\TimeElapsed;
?>  
<div class="timeline-post-row">
	<div class="timeline-group">
	  <div class="timeline-icon"><i class="fas fa-trophy"></i></div>
	  <div class="timeline-ttl"><?=$badge->description;?></div>
	  <div class="timeline-time"><i class="far fa-clock"></i><span><?=TimeElapsed::timeElapsed($badge->created_at);?></span></div>
	  <div class="timeline-badges"><span class="badge-container"><img src="/img/common/member.png" alt="image"></span></div>
	</div>
</div>