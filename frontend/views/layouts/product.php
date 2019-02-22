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
<div class="page-loader"> 
  <div class="page-loader-body"> 
    <div class="preloader-wrapper big active"> 
      <div class="spinner-layer spinner-blue"> 
        <div class="circle-clipper left">
          <div class="circle"></div>
        </div>
        <div class="gap-patch">
          <div class="circle"> </div>
        </div>
        <div class="circle-clipper right">
          <div class="circle"></div>
        </div>
      </div>
      <div class="spinner-layer spinner-red">
        <div class="circle-clipper left">
          <div class="circle"></div>
        </div>
        <div class="gap-patch">
          <div class="circle"> </div>
        </div>
        <div class="circle-clipper right">
          <div class="circle"></div>
        </div>
      </div>
      <div class="spinner-layer spinner-yellow"> 
        <div class="circle-clipper left">
          <div class="circle"></div>
        </div>
        <div class="gap-patch">
          <div class="circle"></div>
        </div>
        <div class="circle-clipper right">
          <div class="circle"> </div>
        </div>
      </div>
      <div class="spinner-layer spinner-green"> 
        <div class="circle-clipper left">
          <div class="circle"></div>
        </div>
        <div class="gap-patch">
          <div class="circle"></div>
        </div>
        <div class="circle-clipper right">
          <div class="circle"></div>
        </div>
      </div>
    </div>
  </div>
</div>
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
              <ul class="rd-navbar-nav">
                <li><a href="index.html">Home</a>
                  <!-- RD Navbar Dropdown-->
                  <ul class="rd-navbar-dropdown">
                    <li><a href="landing-default.html">Landing Default</a>
                    </li>
                    <li><a href="landing-auction.html">Landing Auction</a>
                    </li>
                    <li><a href="landing-environmental.html">Landing Environmental</a>
                    </li>
                    <li><a href="landing-it-company.html">Landing IT Company</a>
                    </li>
                    <li><a href="landing-maritime.html">Landing Maritime</a>
                    </li>
                    <li><a href="landing-museum.html">Landing Museum</a>
                    </li>
                    <li><a href="landing-private-airlines.html">Landing Private Airlines</a>
                    </li>
                    <li><a href="landing-taxi.html">Landing Taxi</a>
                    </li>
                    <li><a href="landing-trucking.html">Landing Trucking</a>
                    </li>
                    <li><a href="landing-theatre.html">Landing Theatre</a>
                    </li>
                    <li><a href="landing-movie.html">Landing Movie</a>
                    </li>
                    <li><a href="landing-seo.html">Landing SEO</a>
                    </li>
                  </ul>
                </li>
                <li><a href="#">Elements</a>
                  <!-- RD Navbar Megamenu-->
                  <ul class="rd-navbar-megamenu">
                    <li>
                      <ul class="rd-megamenu-list">
                        <li><a href="accordions.html">Accordions</a></li>
                        <li><a href="backgrounds-video.html">Backgrounds Video</a></li>
                        <li><a href="boxes.html">Boxes</a></li>
                        <li><a href="buttons.html">Buttons</a></li>
                        <li><a href="colors.html">Colors</a></li>
                        <li><a href="countdown.html">Countdown</a></li>
                        <li><a href="flickr.html">Flickr</a></li>
                      </ul>
                    </li>
                    <li>
                      <ul class="rd-megamenu-list">
                        <li><a href="forms.html">Forms</a></li>
                        <li><a href="gradient-backgrounds.html">Gradient Backgrounds</a></li>
                        <li><a href="grid-system.html">Grid System</a></li>
                        <li><a href="image-backgrounds.html">Image Backgrounds</a></li>
                        <li><a href="inline-video.html">Inline video</a></li>
                        <li><a href="in-page-navigation.html">In-Page Navigation</a></li>
                        <li><a href="lightboxes.html">Lightboxes</a></li>
                      </ul>
                    </li>
                    <li>
                      <ul class="rd-megamenu-list">
                        <li><a href="maps.html">Maps</a></li>
                        <li><a href="modals.html">Modals</a></li>
                        <li><a href="notifications.html">Notifications</a></li>
                        <li><a href="parallax.html">Parallax</a></li>
                        <li><a href="pricing.html">Pricing</a></li>
                        <li><a href="radials.html">Radials</a></li>
                        <li><a href="scrims-and-overlays.html">Scrims and Overlays</a></li>
                        <li><a href="isotope.html">Isotope</a></li>
                      </ul>
                    </li>
                    <li>
                      <ul class="rd-megamenu-list">
                        <li><a href="sliders.html">Sliders</a></li>
                        <li><a href="tables.html">Tables</a></li>
                        <li><a href="tabs.html">Tabs</a></li>
                        <li><a href="tooltips.html">Tooltips</a></li>
                        <li><a href="typed-text.html">Typed Text</a></li>
                        <li><a href="typography.html">Typography</a></li>
                      </ul>
                    </li>
                  </ul>
                </li>
                <li><a href="#">Blocks</a>
                  <!-- RD Navbar Megamenu-->
                  <ul class="rd-navbar-megamenu">
                    <li>
                      <ul class="rd-megamenu-list">
                        <li><a href="blocks-accordions.html">Accordions</a></li>
                        <li><a href="blocks-cta.html">Call to Actions</a></li>
                        <li><a href="blocks-cards.html">Cards</a></li>
                        <li><a href="blocks-contact-forms.html">Contact Forms</a></li>
                        <li><a href="blocks-footers.html">Footers</a></li>
                        <li><a href="blocks-galleries.html">Galleries</a></li>
                      </ul>
                    </li>
                    <li>
                      <ul class="rd-megamenu-list">
                        <li><a href="blocks-large-features.html">Large Features</a></li>
                        <li><a href="blocks-maps.html">Maps</a></li>
                        <li><a href="blocks-navigation-bars.html">Navigation Bars</a></li>
                        <li><a href="blocks-pricing.html">Pricing</a></li>
                        <li><a href="blocks-processes.html">Processes</a></li>
                      </ul>
                    </li>
                    <li>
                      <ul class="rd-megamenu-list">
                        <li><a href="blocks-signup-forms.html">Signup Forms</a></li>
                        <li><a href="blocks-sliders.html">Sliders</a></li>
                        <li><a href="blocks-small-features.html">Small Features</a></li>
                        <li><a href="blocks-subscription-forms.html">Subscription Forms</a></li>
                        <li><a href="blocks-tabs.html">Tabs</a></li>
                        <li><a href="blocks-teams.html">Teams</a></li>
                      </ul>
                    </li>
                    <li>
                      <ul class="rd-megamenu-list">
                        <li><a href="blocks-testimonials.html">Testimonials</a></li>
                        <li><a href="blocks-text-layouts.html">Text Layouts</a></li>
                        <li><a href="blocks-titles.html">Titles</a></li>
                        <li><a href="blocks-videos.html">Videos</a></li>
                        <li><a href="blocks-isotope.html">Isotope</a></li>
                      </ul>
                    </li>
                  </ul>
                </li>
                <li><a href="#">Gallery</a>
                  <!-- RD Navbar Dropdown-->
                  <ul class="rd-navbar-dropdown">
                    <li><a href="fullwidth-gallery-hover-title.html">Fullwidth Gallery Hover Title</a>
                    </li>
                    <li><a href="fullwidth-gallery-inside-title.html">Fullwidth Gallery Inside Title</a>
                    </li>
                    <li><a href="grid-album-gallery.html">Grid Album Gallery</a>
                    </li>
                    <li><a href="grid-gallery-hover-title.html">Grid Gallery Hover Title</a>
                    </li>
                    <li><a href="grid-gallery-inside-title.html">Grid Gallery Inside Title</a>
                    </li>
                    <li><a href="grid-gallery-outside-title.html">Grid Gallery Outside Title</a>
                    </li>
                    <li><a href="masonry-gallery-hover-title.html">Masonry Gallery Hover Title</a>
                    </li>
                    <li><a href="masonry-gallery-inside-title.html">Masonry Gallery Inside Title</a>
                    </li>
                    <li><a href="masonry-gallery-outside-title.html">Masonry Gallery Outside Title</a>
                    </li>
                  </ul>
                </li>
                <li><a href="#">Blog</a>
                  <!-- RD Navbar Dropdown-->
                  <ul class="rd-navbar-dropdown">
                    <li><a href="classic-blog.html">Classic Blog</a>
                    </li>
                    <li><a href="grid-blog.html">Grid Blog</a>
                    </li>
                    <li><a href="masonry-blog.html">Masonry Blog</a>
                    </li>
                    <li><a href="modern-blog.html">Modern Blog</a>
                    </li>
                    <li><a href="audio-post.html">Audio Post</a>
                    </li>
                    <li><a href="image-post.html">Image Post</a>
                    </li>
                    <li><a href="single-post.html">Single Post</a>
                    </li>
                    <li><a href="video-post.html">Video Post</a>
                    </li>
                  </ul>
                </li>
                <li class="active"><a href="#">Shop</a>
                  <!-- RD Navbar Dropdown-->
                  <ul class="rd-navbar-dropdown">
                    <li><a href="checkout.html">Checkout</a>
                    </li>
                    <li><a href="product-page.html">Product Page</a>
                    </li>
                    <li><a href="shop-3-columns-layout.html">Shop 3 Columns Layout</a>
                    </li>
                    <li><a href="shop-4-columns-layout.html">Shop 4 Columns Layout</a>
                    </li>
                    <li><a href="shopping-cart.html">Shopping Cart</a>
                    </li>
                  </ul>
                </li>
                <li><a href="#">Pages</a>
                  <!-- RD Navbar Megamenu-->
                  <ul class="rd-navbar-megamenu rd-navbar-megamenu-banner">
                    <li><img src="/images/megamenu-banner-301x510.jpg" alt="" width="301" height="510"/>
                    </li>
                    <li>
                      <ul class="rd-megamenu-list">
                        <li><a href="404-page.html">404 Page</a></li>
                        <li><a href="503-page.html">503 Page</a></li>
                        <li><a href="about-me.html">About Me</a></li>
                        <li><a href="about-us.html">About Us</a></li>
                        <li><a href="careers.html">Careers</a></li>
                        <li><a href="coming-soon.html">Coming Soon</a></li>
                        <li><a href="contacts.html">Contacts</a></li>
                      </ul>
                    </li>
                    <li>
                      <ul class="rd-megamenu-list">
                        <li><a href="contacts-variant-2.html">Contacts variant 2</a></li>
                        <li><a href="login-page.html">Login Page</a></li>
                        <li><a href="our-history.html">Our History</a></li>
                        <li><a href="pricing-page.html">Pricing Page</a></li>
                        <li><a href="registration-page.html">Registration Page</a></li>
                        <li><a href="search-results.html">Search Results</a></li>
                        <li><a href="under-construction.html">Under Construction</a></li>
                      </ul>
                    </li>
                    <li>
                      <ul class="rd-megamenu-list">
                        <li><a href="services.html">Services</a></li>
                        <li><a href="services-variant-2.html">Services variant 2</a></li>
                        <li><a href="single-job.html">Single Job</a></li>
                        <li><a href="single-service.html">Single Service</a></li>
                        <li><a href="single-project.html">Single Project</a></li>
                        <li><a href="single-event.html">Single Event</a></li>
                      </ul>
                    </li>
                  </ul>
                </li>
              </ul>
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
