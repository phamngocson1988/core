<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use frontend\assets\AppAsset;
use common\widgets\Alert;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html class="wide wow-animation" lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, height=device-height, initial-scale=1.0">
    <meta name="format-detection" content="telephone=no">
    <link rel="icon" href="/images/favicon.ico" type="image/x-icon">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
    <!--[if lt IE 10]>
    <div style="background: #212121; padding: 10px 0; box-shadow: 3px 3px 5px 0 rgba(0,0,0,.3); clear: both; text-align:center; position: relative; z-index:1;"><a href="http://windows.microsoft.com/en-US/internet-explorer/"><img src="/images/ie8-panel/warning_bar_0000_us.jpg" border="0" height="42" width="820" alt="You are using an outdated browser. For a faster, safer browsing experience, upgrade for free today."></a></div>
    <script src="js/html5shiv.min.js"></script>
    <![endif]--> 
</head>
<body>
<?php $this->beginBody() ?>
<!-- Page preloader-->
<?php require_once(Yii::$app->basePath . '/views/layouts/_page_loader.php');?>

<div class="page">
  <!-- Page Header-->
  <header class="section page-header breadcrumbs-custom-wrap bg-gradient bg-secondary-2 novi-background bg-cover">
    <!-- RD Navbar-->
    <div class="rd-navbar-wrap rd-navbar-default">
      <nav class="rd-navbar" data-layout="rd-navbar-fixed" data-sm-layout="rd-navbar-fixed" data-md-layout="rd-navbar-fixed" data-md-device-layout="rd-navbar-fixed" data-lg-layout="rd-navbar-fullwidth" data-xl-layout="rd-navbar-static" data-lg-device-layout="rd-navbar-fixed" data-xl-device-layout="rd-navbar-static" data-md-stick-up-offset="2px" data-lg-stick-up-offset="2px" data-stick-up="true" data-sm-stick-up="true" data-md-stick-up="true" data-lg-stick-up="true" data-xl-stick-up="true">
        <div class="rd-navbar-inner"> 
          <!-- RD Navbar Panel-->
          <div class="rd-navbar-panel">
            <!-- RD Navbar Toggle-->
            <button class="rd-navbar-toggle" data-rd-navbar-toggle=".rd-navbar-nav-wrap"><span></span></button>
            <!-- RD Navbar Brand-->
            <div class="rd-navbar-brand"><a class="brand-name" href="index.html"><img class="logo-default" src="/images/logo-default-128x52.png" alt="" width="128" height="52"/><img class="logo-inverse" src="/images/logo-inverse-128x52.png" alt="" width="128" height="52"/></a></div>
          </div>
          <div class="rd-navbar-aside-right">
            <div class="rd-navbar-nav-wrap">
              <!-- RD Navbar Nav-->
              <?php echo $this->render('_menu'); ?>
            </div>
            <div class="rd-navbar-aside-right-inner">
              <!-- RD Navbar Search-->
              <div class="rd-navbar-search"><a class="rd-navbar-search-toggle" data-rd-navbar-toggle=".rd-navbar-search" href="#"><span></span></a>
                <form class="rd-search" action="search-results.html" data-search-live="rd-search-results-live" method="GET">
                  <div class="form-wrap">
                    <label class="form-label form-label" for="rd-navbar-search-form-input">Search...</label>
                    <input class="rd-navbar-search-form-input form-input" id="rd-navbar-search-form-input" type="text" name="s" autocomplete="off"/>
                    <div class="rd-search-results-live" id="rd-search-results-live"></div>
                  </div>
                  <button class="rd-search-form-submit mdi mdi-magnify"></button>
                </form>
              </div>
              <div class="rd-navbar-shop"><a class="rd-navbar-shop-icon mdi mdi-cart" href="shopping-cart.html"><span>2</span></a></div>
            </div>
          </div>
        </div>
      </nav>
    </div>

  </header>
	<section class="section fullwidth-page bg-image bg-image-4">
		<div class="fullwidth-page-inner">
	  	<?= $content ?>
	  	<!-- Page Footer-->
	    <div class="section-xs page-footer text-center">
	      <div class="container">
	        <p class="right">&#169; <span class="copyright-year"></span> All Rights Reserved
	          &nbsp;<a href="#">Terms of Use</a>&nbsp;<span>and</span>&nbsp;<a href="privacy-policy.html">Privacy Policy</a>
	        </p>
	      </div>
	    </div>
    </div>
  </section>
</div>
<!-- Global Mailform Output-->
<div class="snackbars" id="form-output-global"></div>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
