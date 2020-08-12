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
use frontend\components\toastr\NotificationFlash;

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
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-NP4585Q"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
<?php require_once(Yii::$app->basePath . '/views/layouts/facebook_livechat.php');?>
<?= NotificationFlash::widget() ?>
<?php $this->beginBody() ?>
<div class="overlay"></div>
<div class="wrapper">
  <section class="header">
    <?php require_once(Yii::$app->basePath . '/views/layouts/top-header.php');?>
    <?php require_once(Yii::$app->basePath . '/views/layouts/bottom-header.php');?>
  </section>
  <?= $content ?>
  <?php require_once(Yii::$app->basePath . '/views/layouts/highlight.php');?>
  <?php require_once(Yii::$app->basePath . '/views/layouts/footer.php');?>
</div>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
