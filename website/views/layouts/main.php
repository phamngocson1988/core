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
    })(window,document,'script','dataLayer','GTM-PDQS4ZB');</script>
    <!-- End Google Tag Manager -->
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title><?= Html::encode($this->title) ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <META NAME="webmoney.attestation.label" CONTENT="webmoney attestation label#6CD7944E-3EDF-4E7D-BB7D-1BFBB2530BC5">
    <link rel="apple-touch-icon" href="/apple-touch-icon.png">
    <link rel="icon" href="/favicon.ico" type="image/x-icon">
    <?= Html::csrfMetaTags() ?>
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-JZYRNBYH2H"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());

      gtag('config', 'G-JZYRNBYH2H');
    </script>
    <!-- Facebook Pixel Code -->
    <script>
      !function(f,b,e,v,n,t,s)
      {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
      n.callMethod.apply(n,arguments):n.queue.push(arguments)};
      if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
      n.queue=[];t=b.createElement(e);t.async=!0;
      t.src=v;s=b.getElementsByTagName(e)[0];
      s.parentNode.insertBefore(t,s)}(window, document,'script',
      'https://connect.facebook.net/en_US/fbevents.js');
      fbq('init', '2482388012006645');
      fbq('track', 'PageView');
    </script>
    <noscript><img height="1" width="1" style="display:none"
      src="https://www.facebook.com/tr?id=2482388012006645&ev=PageView&noscript=1"
    /></noscript>
    <!-- End Facebook Pixel Code -->
    <?php $this->head() ?>
</head>
<body>
<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-PDQS4ZB"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->

<div class="overlay" style="display: none;">Loading&#8230;</div>
<?php require_once(Yii::$app->basePath . '/views/layouts/facebook_livechat.php');?>
<?= NotificationFlash::widget() ?>
<?php $this->beginBody() ?>
<?php require_once(Yii::$app->basePath . '/views/layouts/header.php');?>
<?= $content ?>
<div class="pt-5 pb-5 footer">
  <div class="container">
    <div class="row">
      <div class="col-lg-5 col-xs-12 about-company" data-aos="fade-up" data-aos-anchor-placement="center-bottom"
        data-aos-duration="500">
        <h2 class="logo mb-3">
          <a href="/">
            <img src="/images/logo.png" />
          </a>
        </h2>
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
      <div class="col-lg-3 col-xs-12 links" data-aos="fade-up" data-aos-anchor-placement="center-bottom"
        data-aos-duration="800">
        <h4 class="mt-lg-0 mb-5">Find Us</h4>
        <ul class="m-0 p-0">
          <li class="nav-item mb-3">
            <a target="_blank" href="https://www.facebook.com/Kinggems.us/" class="d-flex align-items-center">
              <img class="icon-sm mr-2" src="/images/icon/facebook-icon.svg" />
              <span>Facebook</span>
            </a>
          </li>
          <li class="nav-item mb-3">
            <a target="_blank" href="https://wa.me/84979997559" class="d-flex align-items-center">
              <img class="icon-sm mr-2" src="/images/icon/viber.svg" />
              <span>Viber</span>
            </a>
          </li>
          <li class="nav-item mb-3">
            <a target="_blank" href="https://t.me/KINGGEMS1303" class="d-flex align-items-center">
              <img class="icon-sm mr-2" src="/images/icon/telegram-icon.svg" />
              <span>Telegram</span>
            </a>
          </li>
          <li class="nav-item mb-3">
            <a target="_blank" href="https://u.wechat.com/IK-OOlb-deUWqmVLUAnz-GA" class="d-flex align-items-center">
              <img class="icon-sm mr-2" src="/images/icon/wechat-icon.svg" />
              <span>Wechat</span>
            </a>
          </li>
          <li class="nav-item mb-3">
            <a target="_blank" href="https://line.me/ti/p/6MOHXdDoCg" class="d-flex align-items-center">
              <img class="icon-sm mr-2" src="/images/icon/line.svg" />
              <span>Line</span>
            </a>
          </li>
        </ul>
      </div>
      <div class="col-lg-4 col-xs-12 payment-method" data-aos="fade-up" data-aos-anchor-placement="center-bottom"
        data-aos-duration="1100">
        <h4 class="mt-lg-0 mb-5">Payment methods</h4>
        <ul class="list-inline">
          <li class="list-inline-item">
            <a href="javascript:;"><img class="icon-lg" src="/images/icon/visa.svg" /></i></a>
          </li>
          <li class="list-inline-item">
            <a href="javascript:;"><img class="icon-lg" src="/images/icon/mastercard.svg" /></i></a>
          </li>
          <li class="list-inline-item">
            <a href="javascript:;"><img class="icon-lg" src="/images/icon/paypal.svg" /></i></a>
          </li>
          <!-- <li class="list-inline-item">
            <a href="javascript:;"><img class="icon-lg" src="/images/icon/payoneer.svg" /></i></a>
          </li> -->
          <li class="list-inline-item">
            <a href="javascript:;"><img style="width:100px" src="/images/icon/alipay.png" /></i></a>
          </li>
          <li class="list-inline-item">
            <a href="javascript:;"><img style="width:100px" src="/images/icon/wechatpay.png" /></i></a>
          </li>
          <li class="list-inline-item">
            <a href="javascript:;"><img style="width:100px" src="/images/icon/skrill.svg" /></i></a>
          </li>
          <li class="list-inline-item">
            <!-- begin WebMoney Transfer : accept label -->
            <a href="https://www.megastock.com" target="_blank"><img src="https://www.webmoney.ru/img/icons/88x31_wm_blue.png" alt="www.megastock.com" border="0"/></a>
            <!-- end WebMoney Transfer : accept label -->
          </li>
          <li class="list-inline-item">
            <!-- begin WebMoney Transfer : attestation label -->
            <a href="https://passport.webmoney.ru/asp/certview.asp?wmid=679742853702" target="_blank"><img src="https://www.megastock.ru/Doc/Logo/v_blue_on_white_en.png" alt="Here you can find information as to the passport for our WM-identifier 679742853702" border="0"><br><span style="font-size: 0.7em;">Check passport</span></a>
            <!-- end WebMoney Transfer : attestation label -->
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
