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
    <a href="<?=Url::to(['setting/affiliate_program']);?>">Affiliate</a>
  </li>
  <li <?php if ($tab == 'refer_program') echo 'class="active"' ;?> >
    <a href="<?=Url::to(['setting/refer_program']);?>">Refer friend</a>
  </li>
  <li <?php if ($tab == 'terms') echo 'class="active"' ;?> >
    <a href="<?=Url::to(['setting/terms']);?>">Terms and conditions</a>
  </li>
</ul>