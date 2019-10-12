<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use frontend\assets\AppAsset;
// use common\widgets\Alert;
// use frontend\components\toastr\NotificationFlash;
use frontend\components\sweetalert\Alert;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<!--[if IE 9 ]> <html lang="vi" class="ie9 loading-site no-js"> <![endif]-->
<!--[if IE 8 ]> <html lang="vi" class="ie8 loading-site no-js"> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!-->
<html lang="vi" class="loading-site no-js" lang="<?= Yii::$app->language ?>">
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <meta name='robots' content='noindex,follow' />
    <link rel="icon" href="/images/favicon.ico" type="image/x-icon">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-146694384-1"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());

      gtag('config', 'UA-146694384-1');
    </script>
    <?php $this->head() ?>
</head>
<?php
$bodyClass = isset($this->params['body_class']) ? $this->params['body_class'] : '';
?>
<body class='<?=$bodyClass;?>' >
<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-NP4585Q"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
<div id="fb-root"></div>
<script async defer crossorigin="anonymous" src="https://connect.facebook.net/vi_VN/sdk.js#xfbml=1&version=v3.3&appId=734107406647333&autoLogAppEvents=1"></script>
<?= Alert::widget() ?>
<?php $this->beginBody() ?>
<div class="overlay"></div>
<div class="wrapper">
  <section class="header no-home">
    <?php require_once(Yii::$app->basePath . '/views/layouts/top-header.php');?>
  </section>
  <?= $content ?>
  <?php require_once(Yii::$app->basePath . '/views/layouts/footer.php');?>
</div>
<?php $this->endBody() ?>
</body>
<?php require_once(Yii::$app->basePath . '/views/layouts/livechat.php');?>
</html>
<?php $this->endPage() ?>
