<?php
use yii\helpers\Url;
use common\components\helpers\StringHelper;
?>
<div id="preloader">
  <a href="/" class="logo">
    <img src="/images/logo.png" alt="logo kinggems" />
  </a>
</div>
<nav class="navbar navbar-top" role="navigation">
  <div class="container">
    <div class="navbar-header w-100">
      <div class="d-flex justify-content-between align-items-center">
        <div class="p-2 flex-fill">
          <a href="/" class="logo">
            <img data-aos="zoom-in" data-aos-duration="1000" src="/images/logo.png" alt="logo kinggems" />
          </a>
        </div>
        <div class="p-2 flex-fill search-item">
          <form method="GET" autocomplete='off' action="<?=Url::to(['game/index']);?>">
            <div class="input-group">
              <input type="text" name="q" class="form-control" placeholder="enter your keyword here"
                aria-label="enter your keyword here" aria-describedby="basic-addon2">
              <div class="input-group-append">
                <button class="btn" type="submit">
                  <img class="icon-sm" src="/images/icon/search.svg" />
                </button>
              </div>
            </div>
          </form>
        </div>
        <div class="p-2 flex-fill">
          <div class="d-flex justify-content-between align-items-center">
            <!-- isLogin -->
            <?php if (!Yii::$app->user->isGuest) :?>
            <?php $user = Yii::$app->user->getIdentity();?>
            <div class="p-2 flex-fill login-item">
              <div class="dropdown">
                <a class="dropdown-toggle link-light" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  <?=$user->name;?>
                  <br />
                  <span class="text-green"><?=StringHelper::numberFormat($user->walletBalance(), 2);?> Kcoin</span>
                </a>
              
                <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                  <a class="dropdown-item" href="<?=Url::to(['profile/index']);?>">Account info</a>
                  <a class="dropdown-item" href="<?=Url::to(['order/index']);?>">My order</a>
                  <a class="dropdown-item" href="<?=Url::to(['site/logout']);?>">Log-out</a>
                </div>
              </div>
            </div>
            <div class="p-2 flex-fill order-item border-gradian-1">
              <a class="link-light" href="<?=Url::to(['order/index']);?>">
                <img class="icon-sm" src="/images/icon/bill.svg" />My order</a>
            </div>
            <div class="p-2 flex-fill noti-item">
              <?=\website\components\notifications\Notifications::widget();?>
              <?=\website\components\notifications\MessageNotifications::widget();?>
            </div>
            <?php else:?>
            <div class="p-2 flex-fill login-item">
              <a class="link-light" href="#modalLogin" data-toggle="modal">Login / Signup</a>
            </div>
            <?php endif;?>
            
          </div>
        </div>
        <div class="nav-mb p-2">
          <div id="nav-icon">
            <span></span>
            <span></span>
            <span></span>
          </div>
        </div>
      </div>
    </div>
  </div>
  <?php if (!Yii::$app->user->isGuest) :?>
  <div class="navbar-header navbar-noti w-100">
    <div class="d-flex justify-content-between">
      <?=\website\components\notifications\Notifications::widget([
        'options' => [
          'class' => 'w-100 text-center py-2 dropdown noti-item',
          'id' => 'mobile-header-notification',
        ]
      ]);?>
      <?=\website\components\notifications\MessageNotifications::widget([
        'options' => [
          'class' => 'w-100 text-center py-2 dropdown noti-item border-gradian-1',
          'id' => 'mobile-message-notification',
        ]
      ]);?>
      <div class="w-100 text-center py-2">
        <a class="link-light" href="<?=Url::to(['order/index']);?>">
          <img class="icon-sm" src="/images/icon/bill.svg" />
          My order
        </a>
      </div>
    </div>
  </div>
  <?php endif;?>
</nav>
<div class="navbar-main">
  <div class="container">
    <?php $main_menu_active = isset($this->params['main_menu_active']) ? $this->params['main_menu_active'] : '';?>
    <?php echo yii\widgets\Menu::widget([
      'options' => ['class' => 'nav'],
      'itemOptions' => ['class' => 'nav-item'],
      'linkTemplate' => '<a href="{url}" class="nav-link">{label}</a>',
      'items' => [
          // Add one more record
          ['label' => 'Home', 'url' => ['site/index'], 'active' => $main_menu_active == 'site.index'],
          ['label' => 'Kcoin wallet', 'url' => ['wallet/index'], 'active' => $main_menu_active == 'wallet.index', 'visible' => !Yii::$app->user->isGuest],
          ['label' => 'Games', 'url' => ['game/index'], 'active' => $main_menu_active == 'game.index'],
          ['label' => 'Promotion', 'url' => 'https://promotion.kinggems.us', 'active' => $main_menu_active == 'site.index2'],
          ['label' => 'Affiliate', 'url' => ['affiliate/index'], 'active' => $main_menu_active == 'affiliate.index', 'visible' => !Yii::$app->user->isGuest],
          ['label' => 'Referral', 'url' => ['referral/index'], 'active' => $main_menu_active == 'referral.index', 'visible' => !Yii::$app->user->isGuest],
          ['label' => 'Help center', 'url' => ['question/index'], 'active' => $main_menu_active == 'question.index'],
        ],
      ]);
    ?>
  </div>
</div>
