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
  <header class="section page-header">
    <!-- RD Navbar-->
    <div class="rd-navbar-wrap rd-navbar-shop-header">
      <nav class="rd-navbar" data-layout="rd-navbar-fixed" data-sm-layout="rd-navbar-fixed" data-md-layout="rd-navbar-fixed" data-md-device-layout="rd-navbar-fixed" data-lg-layout="rd-navbar-fullwidth" data-xl-layout="rd-navbar-static" data-lg-device-layout="rd-navbar-fixed" data-xl-device-layout="rd-navbar-static" data-md-stick-up-offset="100px" data-lg-stick-up-offset="150px" data-stick-up="true" data-sm-stick-up="true" data-md-stick-up="true" data-lg-stick-up="true" data-xl-stick-up="true">
        <div class="rd-navbar-top-panel novi-background">
          <div class="rd-navbar-nav-wrap">
            <!-- RD Navbar Nav-->
            <?php echo $this->render('_menu'); ?>
          </div>
        </div>
        <div class="rd-navbar-inner">
          <!-- RD Navbar Panel-->
          <div class="rd-navbar-panel">
            <!-- RD Navbar Toggle-->
            <button class="rd-navbar-toggle" data-rd-navbar-toggle=".rd-navbar-nav-wrap"><span></span></button>
            <!-- RD Navbar Brand-->
            <div class="rd-navbar-brand"><a class="brand-name" href="index.html"><img class="logo-default" src="/images/logo-default-128x52.png" alt="" width="128" height="52"/><img class="logo-inverse" src="/images/logo-inverse-128x52.png" alt="" width="128" height="52"/></a></div>
          </div>
          <div class="rd-navbar-aside-center">
            <!-- RD Navbar Search-->
            <div class="rd-navbar-search"><a class="rd-navbar-search-toggle" data-rd-navbar-toggle=".rd-navbar-search" href="#"><span></span></a>
              <form class="rd-search" action="search-results.html" data-search-live="rd-search-results-live" method="GET">
                <div class="rd-mailform-inline rd-mailform-sm rd-mailform-inline-modern">
                  <div class="rd-mailform-inline-inner">
                    <div class="form-wrap form-wrap-icon mdi-magnify">
                      <label class="form-label form-label" for="rd-navbar-search-form-input">Search...</label>
                      <input class="rd-navbar-search-form-input form-input" id="rd-navbar-search-form-input" type="text" name="s" autocomplete="off">
                      <div class="rd-search-results-live"></div>
                    </div>
                    <button class="rd-search-form-submit rd-search-form-submit-icon mdi mdi-magnify"></button>
                    <button class="rd-search-form-submit button form-button button-sm button-secondary button-nina">search</button>
                  </div>
                </div>
              </form>
            </div>
          </div>
          <div class="rd-navbar-aside-right">
            <?php if (Yii::$app->user->isGuest) :?>
            <div class="rd-navbar-shop rd-navbar-login"><a class="rd-navbar-shop-icon mdi mdi-login" href="login-page.html"><span class="d-none d-xl-inline">Login </span></a></div>
            <?php endif;?>
          </div>
        </div>
      </nav>
    </div>
  </header>

  <?= $content ?>

  <!-- CTA-->
  <section class="section section-xs text-center bg-gradient-1 novi-background bg-cover">
    <div class="container container-wide">
      <div class="box-cta-thin">
        <p class="big"><span class="label-cta label-cta-primary">Hot!</span><strong>Brave Theme</strong>&nbsp;<span>has what you need any movie online.&nbsp;</span><a class="link-bold" href="#">Choose it now!  </a></p>
      </div>
    </div>
  </section>
  <footer class="section page-footer page-footer-default novi-background bg-cover text-left bg-gray-darker">
    <div class="container container-wide">
      <div class="row row-50 justify-content-sm-center">
        <div class="col-md-6 col-xl-3">
          <div class="inset-xxl">
            <h6>About us</h6>
            <p class="text-spacing-sm">Brave is an innovative online cinema working for you 24/7. We have an extensive collection of movies and TV series, so you just have to select and enjoy what you like most in the world of movies, be it something classic or modern.</p>
          </div>
        </div>
        <div class="col-md-6 col-xl-2">
          <h6>Quick links</h6>
          <ul class="list-marked list-marked-primary">
            <li><a href="about-us.html">About</a></li>
            <li><a href="services.html">Services</a></li>
            <li><a href="shop-4-columns-layout.html">Shop</a></li>
            <li><a href="classic-blog.html">Blog</a></li>
            <li><a href="grid-gallery-outside-title.html">Portfolio</a></li>
            <li><a href="contacts.html">Contacts</a></li>
          </ul>
        </div>
        <div class="col-md-6 col-xl-4">
          <h6>Gallery</h6>
          <div class="instafeed text-center" data-lightgallery="group">
            <div class="row row-10 row-narrow">
              <div class="col-4">
                <div class="thumbnail-instafeed thumbnail-instafeed-minimal"> <a class="instagram-link" data-lightgallery="item" href="/images/project-1-1200x800-original.jpg"><img src="/images/project-1-570x380.jpg" alt="" width="570" height="380"/>
                    <!--img.instagram-image(src='/images/_blank.png', alt='', data-images-standard_resolution-url='src')--></a>
                  <div class="caption"> 
                    <ul class="list-inline">
                      <li><span class="icon novi-icon mdi mdi-plus">  </span></li>
                    </ul>
                  </div>
                </div>
              </div>
              <div class="col-4">
                <div class="thumbnail-instafeed thumbnail-instafeed-minimal"> <a class="instagram-link" data-lightgallery="item" href="/images/project-2-1200x800-original.jpg"><img src="/images/project-2-570x380.jpg" alt="" width="570" height="380"/>
                    <!--img.instagram-image(src='/images/_blank.png', alt='', data-images-standard_resolution-url='src')--></a>
                  <div class="caption"> 
                    <ul class="list-inline">
                      <li><span class="icon novi-icon mdi mdi-plus">  </span></li>
                    </ul>
                  </div>
                </div>
              </div>
              <div class="col-4">
                <div class="thumbnail-instafeed thumbnail-instafeed-minimal"> <a class="instagram-link" data-lightgallery="item" href="/images/project-3-1200x800-original.jpg"><img src="/images/project-3-570x380.jpg" alt="" width="570" height="380"/>
                    <!--img.instagram-image(src='/images/_blank.png', alt='', data-images-standard_resolution-url='src')--></a>
                  <div class="caption"> 
                    <ul class="list-inline">
                      <li><span class="icon novi-icon mdi mdi-plus">  </span></li>
                    </ul>
                  </div>
                </div>
              </div>
              <div class="col-4">
                <div class="thumbnail-instafeed thumbnail-instafeed-minimal"> <a class="instagram-link" data-lightgallery="item" href="/images/project-4-1200x800-original.jpg"><img src="/images/project-4-570x380.jpg" alt="" width="570" height="380"/>
                    <!--img.instagram-image(src='/images/_blank.png', alt='', data-images-standard_resolution-url='src')--></a>
                  <div class="caption"> 
                    <ul class="list-inline">
                      <li><span class="icon novi-icon mdi mdi-plus">  </span></li>
                    </ul>
                  </div>
                </div>
              </div>
              <div class="col-4">
                <div class="thumbnail-instafeed thumbnail-instafeed-minimal"> <a class="instagram-link" data-lightgallery="item" href="/images/project-5-1200x800-original.jpg"><img src="/images/project-5-570x380.jpg" alt="" width="570" height="380"/>
                    <!--img.instagram-image(src='/images/_blank.png', alt='', data-images-standard_resolution-url='src')--></a>
                  <div class="caption"> 
                    <ul class="list-inline">
                      <li><span class="icon novi-icon mdi mdi-plus">  </span></li>
                    </ul>
                  </div>
                </div>
              </div>
              <div class="col-4">
                <div class="thumbnail-instafeed thumbnail-instafeed-minimal"> <a class="instagram-link" data-lightgallery="item" href="/images/project-6-1200x800-original.jpg"><img src="/images/project-6-570x380.jpg" alt="" width="570" height="380"/>
                    <!--img.instagram-image(src='/images/_blank.png', alt='', data-images-standard_resolution-url='src')--></a>
                  <div class="caption"> 
                    <ul class="list-inline">
                      <li><span class="icon novi-icon mdi mdi-plus">  </span></li>
                    </ul>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-6 col-xl-3">
          <h6>newsletter</h6>
          <p class="text-spacing-sm">Keep up with our always upcoming product features and technologies. Enter your e-mail and subscribe to our newsletter.</p>
          <!-- RD Mailform: Subscribe-->
          <form class="rd-mailform rd-mailform-inline rd-mailform-sm" data-form-output="form-output-global" data-form-type="subscribe" method="post" action="bat/rd-mailform.php">
            <div class="rd-mailform-inline-inner">
              <div class="form-wrap">
                <input class="form-input" type="email" name="email" data-constraints="@Email @Required" id="subscribe-form-email-1"/>
                <label class="form-label" for="subscribe-form-email-1">Enter your e-mail</label>
              </div>
              <button class="button form-button button-sm button-secondary button-nina" type="submit">Subscribe</button>
            </div>
          </form>
        </div>
      </div>
      <p class="right">&#169;&nbsp;<span class="copyright-year"></span> All Rights Reserved&nbsp;<a href="#">Terms of Use</a>&nbsp;and&nbsp;<a href="privacy-policy.html">Privacy Policy</a></p>
    </div>
  </footer>
</div>
  <!-- Global Mailform Output-->
  <div class="snackbars" id="form-output-global"></div>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
