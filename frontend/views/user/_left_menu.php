<?php
use yii\helpers\Url;
?>
<div class="col col-lg-2 offset-lg-1 col-md-3 offset-md-0 col-sm-12 col-12">
  <div class="product-category profile-left">
    <ul class="refer-qa-list">
      <li class="qa-item">
        <a class="cus-btn gray-btn qa-question" href="javascript:;">account infomation</a>
        <div class="qa-answer showing" style="display: block">
          <a href="<?=Url::to(['user/profile']);?>" class="sub-btn" code='user.profile'>Profile</a>
          <a href="<?=Url::to(['user/password']);?>" class="sub-btn" code='user.password'>Change password</a>
        </div>
      </li>
      <li class="qa-item">
        <a class="cus-btn gray-btn qa-question" href="javascript:;">HIstory
        transaction</a>
        <div class="qa-answer showing" style="display: block">
          <a href="javascript:;" class="sub-btn" code='user.wallet'>Wallet</a>
          <a href="javascript:;" class="sub-btn" code='user.order'>Order</a>
        </div>
      </li>
    </ul>
  </div>
</div>