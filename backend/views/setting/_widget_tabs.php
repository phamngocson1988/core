<?php
use yii\helpers\Url;
?>
<ul class="nav nav-tabs">
  <li <?php if ($tab == 'flash_announcement') echo 'class="active"' ;?> >
    <a href="<?=Url::to(['setting/flash_announcement']);?>">Thông báo nhanh</a>
  </li>
  <li <?php if ($tab == 'welcome_bonus') echo 'class="active"' ;?> >
    <a href="<?=Url::to(['setting/welcome_bonus']);?>">Qùa tặng khi đăng ký</a>
  </li>
  <li <?php if ($tab == 'affiliate_program') echo 'class="active"' ;?> >
    <a href="<?=Url::to(['setting/affiliate_program']);?>">Chương trình bán hàng liên kết</a>
  </li>
 
</ul>