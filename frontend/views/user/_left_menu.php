<?php
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
$user_menu_active = ArrayHelper::getValue($this->params, 'user_menu_active');
if (Yii::$app->user->isGuest) die('Not permission');
$user = Yii::$app->user->getIdentity();
?>
<div class="col col-lg-2 offset-lg-1 col-md-3 offset-md-0 col-sm-12 col-12">
  <div class="product-category profile-left">
    <ul class="refer-qa-list" user_menu_active='<?=$user_menu_active;?>'>
      <li class="qa-item">
        <a class="cus-btn gray-btn qa-question" href="javascript:;">account infomation</a>
        <div class="qa-answer showing" style="display: block">
          <a href="<?=Url::to(['user/profile']);?>" class="sub-btn" code='user.profile'>Profile</a>
          <a href="<?=Url::to(['user/password']);?>" class="sub-btn" code='user.password'>Change password</a>
        </div>
      </li>
      <li class="qa-item">
        <a class="cus-btn gray-btn qa-question" href="javascript:;">History
        transaction</a>
        <div class="qa-answer showing" style="display: block">
          <a href="<?=Url::to(['user/transaction']);?>" class="sub-btn" code='user.transaction'>Transaction</a>
          <a href="<?=Url::to(['user/wallet']);?>" class="sub-btn" code='user.wallet'>Wallet</a>
          <?php if (!$user->isReseller()) : ?>
          <a href="<?=Url::to(['user/orders']);?>" class="sub-btn" code='user.order'>Order</a>
          <?php endif;?>
        </div>
      </li>
      <?php if ($user->isReseller()) : ?>
      <li class="qa-item">
        <a class="cus-btn gray-btn qa-question" href="javascript:;">Reseller</a>
        <div class="qa-answer showing" style="display: block">
          <a href="<?=Url::to(['reseller/order', 'status' => 'verifying']);?>" class="sub-btn" code='reseller.verifying'>Verifying</a>
          <a href="<?=Url::to(['reseller/order', 'status' => 'pending']);?>" class="sub-btn" code='reseller.pending'>Pending</a>
          <a href="<?=Url::to(['reseller/order', 'status' => 'processing']);?>" class="sub-btn" code='reseller.processing'>Processing</a>
          <a href="<?=Url::to(['reseller/order', 'status' => 'completed']);?>" class="sub-btn" code='reseller.completed'>Completed</a>
          <a href="<?=Url::to(['reseller/order', 'status' => 'cancelled']);?>" class="sub-btn" code='reseller.cancelled'>Cancelled</a>
        </div>
      </li>
      <?php endif;?>
    </ul>
  </div>
</div>