<?php
use yii\helpers\Html;
use yii\helpers\Url;
?>
<section class="section-lg bg-default">
  <div class="container text-center">
    <h3 class="text-gray-4">User dashboard</h3>
  </div>
  <!-- Style switcher-->
  <div class="style-switcher hide" data-container="">
    <div class="style-switcher-container">
      <div class="style-switcher-toggle-wrap"> 
      </div>
      <section class="section section-lg novi-background bg-cover text-left bg-gray-darker">
        <div class="container">
          <div class="row row-50 justify-content-sm-center">
            <div class="col-sm-10 col-md-6 col-xl-4">
              <article class="box-minimal box-minimal-border">
                <div class="box-minimal-icon novi-icon mdi mdi-account-outline"></div>
                <p class="big box-minimal-title"><a href="<?=Url::to(['user/profile']);?>" style="color:white">My Profile</a></p>
                <hr>
                <div class="box-minimal-text">
                  Edit your basic inforamtion and password. <br/>
                  Or change passowrd <a href="<?=Url::to(['user/password']);?>">here</a>
                </div>
              </article>
            </div>
            <div class="col-sm-10 col-md-6 col-xl-4">
              <article class="box-minimal box-minimal-border">
                <div class="box-minimal-icon novi-icon mdi mdi-cart-outline"></div>
                <p class="big box-minimal-title"><a href="<?=Url::to(['user/orders']);?>" style="color:white">My Orders</a></p>
                <hr>
                <div class="box-minimal-text">
                List history of orders and their status.<br/>
                <?php if ($order) :?>
                Your last order: <a href="<?=Url::to(['user/detail', 'key' => $order->auth_key]);?>">#<?=$order->auth_key;?></a>
                <?php else :?>
                You have no orders.  <a href="<?=Url::to(['site/index']);?>">Shop now</a>
                <?php endif;?>
                </div>
              </article>
            </div>
            <div class="col-sm-10 col-md-6 col-xl-4">
              <article class="box-minimal box-minimal-border">
                <div class="box-minimal-icon novi-icon mdi mdi-credit-card"></div>
                <p class="big box-minimal-title"><a href="<?=Url::to(['user/wallet']);?>" style="color:white">My Wallet - (K)<?=number_format($coin);?></a></p>
                <hr>
                <div class="box-minimal-text">
                  List all your actions on the wallet<br/>
                  <?php if ($wallet) :?>
                  The last change in your wallet: <a href="<?=Url::to(['user/wallet']);?>"><?=sprintf("%s %s King Coin", $wallet->getTypeLabel(), number_format($wallet->coin));?></a>
                  <?php else :?>
                  You have no any King Coin.  <a href="<?=Url::to(['pricing/index']);?>">Buy now</a>
                  <?php endif;?>
                </div>
              </article>
            </div>
            <div class="col-sm-10 col-md-6 col-xl-4">
              <article class="box-minimal box-minimal-border">
                <div class="box-minimal-icon novi-icon mdi mdi-bank"></div>
                <p class="big box-minimal-title"><a href="<?=Url::to(['user/transaction']);?>" style="color:white">Transactions</a></p>
                <hr>
                <div class="box-minimal-text">
                  List all your transactions of your payments.<br/>
                  <?php if ($transaction) :?>
                  Last transaction: <?=$transaction->created_at;?><br/>
                  Total: $<?=number_format($transaction->total_price);?>
                  <?php else :?>
                  You have no any transactions.  <a href="<?=Url::to(['pricing/index']);?>">Buy now</a>
                  <?php endif;?>
                </div>
              </article>
            </div>
          </div>
        </div>
      </section>
    </div>
  </div>
</section>