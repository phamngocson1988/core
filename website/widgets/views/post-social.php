<?php 
use yii\helpers\Url;
?>
<ul class="nav nav-divider align-items-center d-sm-inline-block mb-3 py-2">
    <li class="nav-item">  
        <?php if ($canLike) :?>                    
        <button type="button" class="btn <?=$isLike ? 'btn-primary' : '' ;?>" id="likeButton"><img src="/images/icon/like.png"/><?=$likes;?> likes</button>
        <?php endif;?>
        <button type="button" class="btn"><img src="/images/icon/view.png"/><?=number_format($view_count);?> views</button>        
    </li>
</ul>
<ul class="nav nav-divider align-items-center d-sm-inline-block mb-3 py-2">
  <li class="list-inline-item">
    <a href="https://www.facebook.com/sharer/sharer.php?u=<?=$share_link;?>&t=Kinggems Title"onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=300,width=600');return false;" target="_blank" title="Share on Facebook"><img class="icon-md" src="/images/icon/facebook.svg"></a>
  </li>
  <li class="list-inline-item">
    <a href="https://twitter.com/share?url=<?=$share_link;?>&via=TWITTER_HANDLE&text=Kinggems Title"
        onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=300,width=600');return false;"
        target="_blank" title="Share on Twitter"><img class="icon-md" src="/images/icon/twitter.svg">
    </a>
  </li>
  <li class="list-inline-item">
    <a href="https://t.me/share/url?url=<?=$share_link;?>&text=Kinggems Title" onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=300,width=600');return false;"
        target="_blank" title="Share on Telegram"><img class="icon-md" src="/images/icon/telegram-icon.svg"></a>
  </li>

</ul>

<?php
$likeUrl = Url::to(['post/like', 'id' => $post_id]);
$script = <<< JS

var isLikeProcessing = false;
var likes = $likes;
$('#likeButton').on('click', function() {
  if (isLikeProcessing) return;
  isLikeProcessing = true;
  var that = this;
  $.ajax({
      url: '$likeUrl',
      type: 'post',
      dataType : 'json',
      success: function (result, textStatus, jqXHR) {
        if (result.status == true) {
          $(that).toggleClass('btn-primary');
          if (result.is_like) {
              likes += 1;
            $(that).html('<img src="/images/icon/like.png"/>' + likes + ' likes');
          } else {
            likes -= 1;
            $(that).html('<img src="/images/icon/like.png"/>' + likes + ' likes');
          }
        }
      },
      complete: function() {
        isLikeProcessing = false;
      }
  });
})
JS;
$this->registerJs($script);
?>