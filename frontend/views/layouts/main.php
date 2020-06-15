<?php
use yii\helpers\Html;
use yii\helpers\Url;
use frontend\assets\AppAsset;
use frontend\components\toastr\NotificationFlash;
AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<!--[if IE 9 ]> <html lang="vi" class="ie9 loading-site no-js"> <![endif]-->
<!--[if IE 8 ]> <html lang="vi" class="ie8 loading-site no-js"> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!-->
<html lang="<?=Yii::$app->language ?>">
<!--<![endif]-->
<head>
  <meta charset="<?= Yii::$app->charset ?>">
  <meta name="HandheldFriendly" content="True">
  <meta name="MobileOptimized" content="320">
  <meta name="format-detection" content="telephone=no">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <!--[if IE]><meta http-equiv="cleartype" content="on"><![endif]-->
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no, user-scalable=0" id="viewport">
  <meta name="description" content="">
  <meta name="keywords" content="">
  <?= Html::csrfMetaTags() ?>
  <title><?= Html::encode($this->title) ?></title>
  <link rel="shortcut icon" href="/favicon.ico" />
  <?= NotificationFlash::widget() ?>
  <?php $this->head() ?>
</head>
<body>
	<?php $this->beginBody();?>
  <div class="wrapper">
    <header class="header">
      <div class="container d-flex justify-content-between">
        <div class="header-left d-flex align-items-center">
          <h1 class="header-logo"><a class="trans" href="/"><img src="/img/common/logo.png" alt="image"></a></h1>
          <nav class="header-nav" id="js-nav-bar">
            <div class="header-nav-inner">
              <form class="header-search" action="./">
                <input class="form-control" type="text" placeholder="Search">
                <button class="fa fa-search" type="submit"></button>
              </form>
              <?php $main_menu_active = isset($this->params['main_menu_active']) ? $this->params['main_menu_active'] : '';?>
              <?php
              $items = [];
              if (Yii::$app->user->isGuest) {
                $items[] = ['label' => 'LOGIN', 'url' => '#modalLogin', 'visible' => Yii::$app->user->isGuest, 'template' => '<a href="{url}" data-toggle="modal">{label}</a>', 'options' => ['class' => 'header-login']];
              } else {
                $items[] = ['label' => strtoupper(Yii::$app->user->identity->getName()), 'url' => ['profile/index'], 'options' => ['class' => 'header-login']];
              }
              $items[] = ['label' => 'OPERATORS', 'url' => ['operator/index'], 'active' => $main_menu_active == 'operator.index'];
              $items[] = [
                'label' => 'BONUSES', 
                'url' => ['bonus/index'], 
                'active' => $main_menu_active == 'bonus.index', 
                'options' => ['class' => 'has-sub'],
                'template' => '<a href="{url}" class="item-nav no-link">{label}</a><div class="item-nav js-btn-dropdown">{label}</div>', 
                'items' => [
                  ['label' => 'Casino Bonuses', 'url' => '#', 'template' => '<a href="{url}" class="trans">{label}</a>']
                ],
              ];
              $items[] = ['label' => 'COMPLAINTS', 'url' => ['complain/index'], 'active' => $main_menu_active == 'complain.index'];
              $items[] = ['label' => 'NEWS', 'url' => ['news/index'], 'active' => $main_menu_active == 'news.index'];
              $items[] = ['label' => 'FORUM', 'url' => ['forum/index'], 'active' => $main_menu_active == 'forum.index'];
              ?>
              <?php echo yii\widgets\Menu::widget([
                'options' => ['class' => 'header-nav-list d-flex'],
                'itemOptions' => ['class' => ''],
                'linkTemplate' => '<a href="{url}" class="item-nav">{label}</a>',
                'submenuTemplate' => '<ul class="js-dropdown">{items}</ul>',
                'items' => $items
                ]);
              ?>
            </div>
          </nav>
        </div>
        <div class="header-right d-flex align-items-center">
          
          <?php if (Yii::$app->user->isGuest) : ?>
          <div class="header-login"><a href="#modalLogin" data-toggle="modal">LOGIN</a></div>
          <?php else : ?>
          <?php $user = Yii::$app->user->getIdentity();?>
          <div class="header-icon header-email"><a class="trans" href="#"><i class="fas fa-envelope"></i></a></div>
          <div class="header-icon header-bell header-dropdown"><a class="trans" href="#"><i class="fas fa-bell"></i></a><span class="js-action"></span>
            <div class="dropdown-mega">
              <div class="dropdown-mega-inner">
                <p class="mega-title">Notifications (12)</p>
                <div class="mega-content">
                  <ul class="bell-list">
                    <li><a class="bell-item trans" href="#">
                        <div class="bell-image"><img src="/img/common/avatar_img_01.png" alt="image"></div>
                        <div class="bell-info">
                          <div class="bell-group"><span class="bell-name">Username</span><span class="bell-txt">replied to your topic</span></div>
                          <p class="bell-date">a day ago</p>
                        </div></a></li>
                    <li><a class="bell-item trans" href="#">
                        <div class="bell-image"><img src="/img/common/avatar_img_01.png" alt="image"></div>
                        <div class="bell-info">
                          <div class="bell-group"><span class="bell-name">Username</span><span class="bell-txt">replied to your topic</span></div>
                          <p class="bell-date">a day ago</p>
                        </div></a></li>
                    <li><a class="bell-item trans" href="#">
                        <div class="bell-image"><img src="/img/common/avatar_img_01.png" alt="image"></div>
                        <div class="bell-info">
                          <div class="bell-group"><span class="bell-name">Username</span><span class="bell-txt">replied to your topic</span></div>
                          <p class="bell-date">a day ago</p>
                        </div></a></li>
                    <li><a class="bell-item trans" href="#">
                        <div class="bell-image"><img src="/img/common/avatar_img_01.png" alt="image"></div>
                        <div class="bell-info">
                          <div class="bell-group"><span class="bell-name">Username</span><span class="bell-txt">replied to your topic</span></div>
                          <p class="bell-date">a day ago</p>
                        </div></a></li>
                    <li><a class="bell-item trans" href="#">
                        <div class="bell-image"><img src="/img/common/avatar_img_01.png" alt="image"></div>
                        <div class="bell-info">
                          <div class="bell-group"><span class="bell-name">Username</span><span class="bell-txt">replied to your topic</span></div>
                          <p class="bell-date">a day ago</p>
                        </div></a></li>
                  </ul>
                </div>
                <div class="mega-btn"><span>Mark as read</span><a class="trans mg-left" href="#">VIEW ALL</a><a class="fas fa-cog" href="#"></a></div>
              </div>
            </div>
          </div>
          <?=\frontend\widgets\ProfileMenuWidget::widget();?>
          <?php endif;?>
          <div class="header-language"><a class="trans" href="#"><span>VI</span></a></div>
        </div>
        <div class="btn-menu" id="btn-menu"><span></span><span></span><span></span></div>
      </div>
    </header>
    <div class="overlay-menu" id="overlay-menu"></div>
      <?=$content;?>
    <footer class="footer">
      <div class="container d-flex flex-wrap">
        <div class="footer-col col-md-2 col-6">
          <ul class="footer-menu">
            <li><a href="#">OPERATORS</a></li>
            <li><a href="#">GAME</a></li>
            <li><a href="#">BONUSES</a></li>
            <li><a href="#">COMPLAINT</a></li>
            <li><a href="#">NEWS</a></li>
            <li><a href="#">FORUM</a></li>
          </ul>
        </div>
        <div class="footer-col col-md-2 col-6">
          <ul class="footer-menu">
            <li><a href="#">About Us</a></li>
            <li><a href="#">Advertise</a></li>
            <li><a href="#">Corporate</a></li>
            <li><a href="#">Contact Us</a></li>
          </ul>
        </div>
        <div class="footer-col col-12 col-md-7">
          <p class="footer-title">SIGN UP FOR LATEST PROMOTION OFFERS FORM OUR PARTNERS</p>
          <form class="footer-form d-flex" action="./">
            <input class="form-control form-control-sm" type="text" placeholder="Email address">
            <button class="btn btn-warning" type="submit">SIGN UP</button>
          </form>
          <div class="footer-text col-12 col-md-10">By subscribing you are certifying that you have reviewed and accepted our updated Privacy and Cookie Policy</div>
          <ul class="footer-sns d-flex align-items-center">
            <li><a class="fab fa-facebook trans" href="#"></a></li>
            <li><a class="fab fa-youtube trans" href="#"></a></li>
            <li><a class="fab fa-twitter trans" href="#"></a></li>
          </ul>
        </div>
      </div>
    </footer>
  </div>
  <?php require_once(Yii::$app->basePath . '/views/layouts/modal.php');?>
  <?php $this->endBody();?>
</body>
</html>
<?php $this->endPage();?>