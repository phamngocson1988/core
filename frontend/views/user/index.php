<?php
use yii\helpers\Html;
use yii\helpers\Url;
?>
<section class="section-lg bg-default">
  <div class="container text-center">
    <h3 class="text-gray-4">User Profile</h3>
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
              <a href="<?=Url::to(['user/profile']);?>">
                <article class="box-minimal box-minimal-border">
                  <div class="box-minimal-icon novi-icon mdi mdi-thumb-up-outline"></div>
                  <p class="big box-minimal-title">Basic Profile</p>
                  <hr>
                  <div class="box-minimal-text">Edit your basic inforamtion and password</div>
                </article>
              </a>
            </div>
            <div class="col-sm-10 col-md-6 col-xl-4">
              <a href="<?=Url::to(['user/orders']);?>">
                <article class="box-minimal box-minimal-border">
                  <div class="box-minimal-icon novi-icon mdi mdi-account-multiple"></div>
                  <p class="big box-minimal-title">My Orders</p>
                  <hr>
                  <div class="box-minimal-text">List history of orders and their status</div>
                </article>
              </a>
            </div>
            <div class="col-sm-10 col-md-6 col-xl-4">
              <a href="<?=Url::to(['user/wallet']);?>">
                <article class="box-minimal box-minimal-border">
                  <div class="box-minimal-icon novi-icon mdi mdi-headset"></div>
                  <p class="big box-minimal-title">My Wallet</p>
                  <hr>
                  <div class="box-minimal-text">List all your transactions and the total of King Coins</div>
                </article>
              </a>
            </div>
          </div>
        </div>
      </section>
    </div>
  </div>
</section>