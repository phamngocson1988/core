<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use website\assets\AppAsset;
use website\components\toastr\NotificationFlash;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang=""> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8" lang=""> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9" lang=""> <![endif]-->
<!--[if gt IE 8]><!-->
<html class="no-js" lang="<?= Yii::$app->language ?>">
<!--<![endif]-->
<head>
    <!-- Google Tag Manager -->
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
    new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
    j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
    'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
    })(window,document,'script','dataLayer','GTM-NP4585Q');</script>
    <!-- End Google Tag Manager -->
    <script>
    var hm = hm || [];
    hm.push('setClient', 'HM-001068');
    (function() {
        var dom = document.createElement('script'); dom.type = 'text/javascript';
        dom.async = true;
        dom.src = ('https:' == document.location.protocol ? 'https://' : 'http://') + 'dashboard.heatmap.vn/js/tracker.production.js?id=1141db4bf944362a9fb7';
        var s = document.getElementsByTagName('script')[0];
        s.parentNode.insertBefore(dom, s);
    })();
    </script>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title><?= Html::encode($this->title) ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="apple-touch-icon" href="/apple-touch-icon.png">
    <link rel="icon" href="/favicon.ico" type="image/x-icon">
    <?= Html::csrfMetaTags() ?>
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-37CX919500"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());

      gtag('config', 'G-37CX919500');
    </script>
    <?php $this->head() ?>
</head>
<body>
<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-NP4585Q"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
<?php require_once(Yii::$app->basePath . '/views/layouts/facebook_livechat.php');?>
<?= NotificationFlash::widget() ?>
<?php $this->beginBody() ?>
<?php require_once(Yii::$app->basePath . '/views/layouts/header.php');?>
<?= $content ?>
<div class="pt-5 pb-5 footer">
  <div class="container">
    <div class="row">
      <div class="col-lg-5 col-xs-12 about-company">
        <h2>ABOUT US</h2>
        <p class="pr-5 text-white-50">King Gems is an organization,we provide worldwide top up services for mobile
          games. Our service is delivered with 03 core values:
          Fast Top-up
          Cost Savings
          Multiple Choices
          Come to us to enjoy your games with acceptable cost and best quality service!
          We understand and believe that customer’s trust is the greatest asset of King Gems. Therefore, we are
          constantly developing to improve service quality, in order to meet the diverse needs of all multinational
          customers.</p>
      </div>
      <div class="col-lg-3 col-xs-12 links">
        <h4 class="mt-lg-0 mt-sm-3">Links</h4>
        <ul class="m-0 p-0">
          <li>- <a href="#">Lorem ipsum</a></li>
          <li>- <a href="#">Nam mauris velit</a></li>
          <li>- <a href="#">Etiam vitae mauris</a></li>
          <li>- <a href="#">Fusce scelerisque</a></li>
          <li>- <a href="#">Sed faucibus</a></li>
          <li>- <a href="#">Mauris efficitur nulla</a></li>
        </ul>
      </div>
      <div class="col-lg-4 col-xs-12 payment-method">
        <h4 class="mt-lg-0 mt-sm-4">Payment methods</h4>
        <ul class="list-inline">
          <li class="list-inline-item">
            <a href="#"><img class="icon-lg" src="/images/icon/visa.svg" /></i></a>
          </li>
          <li class="list-inline-item">
            <a href="#"><img class="icon-lg" src="/images/icon/mastercard.svg" /></i></a>
          </li>
          <li class="list-inline-item">
            <a href="#"><img class="icon-lg" src="/images/icon/paypal.svg" /></i></a>
          </li>
          <li class="list-inline-item">
            <a href="#"><img class="icon-lg" src="/images/icon/payoneer.svg" /></i></a>
          </li>
          <li class="list-inline-item">
            <a href="#"><img class="icon-lg" src="/images/icon/visa.svg" /></i></a>
          </li>
          <li class="list-inline-item">
            <a href="#"><img class="icon-lg" src="/images/icon/mastercard.svg" /></i></a>
          </li>
          <li class="list-inline-item">
            <a href="#"><img class="icon-lg" src="/images/icon/paypal.svg" /></i></a>
          </li>
          <li class="list-inline-item">
            <a href="#"><img class="icon-lg" src="/images/icon/payoneer.svg" /></i></a>
          </li>
        </ul>
      </div>
    </div>
    <div class="row mt-5">
      <div class="col copyright">
        <p class=""><small class="text-white-50">© 2019. King Gems All Rights Reserved.</small></p>
      </div>
    </div>
  </div>
</div>
<?php require_once(Yii::$app->basePath . '/views/layouts/modal.php');?>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
