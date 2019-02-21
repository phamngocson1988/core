<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
?>
<!-- Product Page-->
<section class="section section-lg bg-default">
  <!-- section wave-->
  <div class="section-wave">
    <svg x="0px" y="0px" width="1920px" height="45px" viewbox="0 0 1920 45" preserveAspectRatio="none">
      <path d="M1920,0c-82.8,0-108.8,44.4-192,44.4c-78.8,0-116.5-43.7-192-43.7 c-77.1,0-115.9,44.4-192,44.4c-78.2,0-114.6-44.4-192-44.4c-78.4,0-115.3,44.4-192,44.4C883.1,45,841,0.6,768,0.6 C691,0.6,652.8,45,576,45C502.4,45,461.9,0.6,385,0.6C306.5,0.6,267.9,45,191,45C115.1,45,78,0.6,0,0.6V45h1920V0z"></path>
    </svg>
  </div>
  <div class="container container-bigger product-single">
    <div class="row row-fix justify-content-sm-center justify-content-lg-between row-30 align-items-lg-center">
      <div class="col-lg-5 col-xl-6 col-xxl-5">
        <div class="product-single-preview">
          <div class="unit flex-column flex-md-row align-items-md-center unit-spacing-md-midle unit--inverse unit-sm">
            <div class="unit-body">
              <ul class="product-thumbnails">
                <li class="active" data-large-image="/images/shop-01-420x550.png"><img src="/images/shop-01-54x71.png" alt="" width="54" height="71"></li>
                <li data-large-image="/images/shop-02-420x550.png"><img src="/images/shop-02-10x71.png" alt="" width="10" height="71"></li>
              </ul>
            </div>
            <div class="unit-right product-single-image">
              <div class="product-single-image-element"><img class="product-image-area animateImageIn" src="/images/shop-01-420x550.png" alt=""></div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-lg-7 col-xl-6 col-xxl-6 text-center text-lg-left">
        <div class="heading-5">Joanne Schultz</div>
        <h3>immutable laws of marketing</h3>
        <div class="divider divider-default"></div>
        <p class="text-spacing-sm">In this book, written by a renowned marketing expert, you will find a compendium of twenty-two innovative rules for understanding and succeeding in the international marketplace. From the Law of Leadership to The Law of the Category, and The Law of the Mind, these valuable insights present a clear path to successful products.</p>
        <ul class="inline-list">
          <li class="text-center"><span class="icon novi-icon icon-md mdi mdi-star text-secondary-3"></span>
            <p class="text-spacing-sm offset-0">Bestseller<br>2016</p>
          </li>
          <li class="text-center"><span class="icon novi-icon icon-md mdi mdi-trophy text-secondary-3"></span>
            <p class="text-spacing-sm offset-0">Bestseller<br>2016</p>
          </li>
        </ul>
        <ul class="inline-list">
          <li class="text-middle">
            <h6>$29.00</h6>
          </li>
          <li class="text-middle"><a class="button button-sm button-secondary button-nina" href="shopping-cart.html">add to cart</a></li>
          <li class="text-middle"><a class="button button-sm button-default-outline button-nina" href="#">add to wishlist</a></li>
        </ul>
      </div>
    </div>
  </div>
</section>

<!-- Similar products-->
<section class="section section-lg bg-gray-lighter text-center">
  <div class="container container-wide">
    <h3>Similar products</h3>
    <div class="divider divider-default"></div>
    <div class="row row-30">
      <div class="col-md-6 col-lg-4 col-xl-3 col-xxl-3">
        <div class="product">
          <div class="product-image"><a href="product-page.html"><img src="/images/book-05-188x246.jpg" alt="" width="188" height="246"/></a></div>
          <div class="product-title">
            <h5><a href="product-page.html">Tools That Reveal Why Your<br class="d-none d-xxl-block">&nbsp;
                Users Abandon Your Website</a></h5>
          </div>
          <div class="product-price">
            <h6>$25.00</h6>
          </div>
          <div class="product-button"><a class="button button-secondary button-nina" href="shopping-cart.html">add to cart</a></div>
        </div>
      </div>
      <div class="col-md-6 col-lg-4 col-xl-3 col-xxl-3">
        <div class="product">
          <div class="product-image"><a href="product-page.html"><img src="/images/book-06-188x246.jpg" alt="" width="188" height="246"/></a></div>
          <div class="product-title">
            <h5><a href="product-page.html">Dangerous Side Effects<br class="d-none d-xxl-block">&nbsp;
                of Bad Customer Service</a></h5>
          </div>
          <div class="product-price">
            <h6>$21.00</h6>
          </div>
          <div class="product-button"><a class="button button-secondary button-nina" href="shopping-cart.html">add to cart</a></div>
        </div>
      </div>
      <div class="col-md-6 col-lg-4 col-xl-3 col-xxl-3">
        <div class="product">
          <div class="product-image"><a href="product-page.html"><img src="/images/book-07-188x246.jpg" alt="" width="188" height="246"/></a></div>
          <div class="product-title">
            <h5><a href="product-page.html">Intro Guide to UX Reviews<br class="d-none d-xxl-block">&nbsp;
                for Web Designers</a></h5>
          </div>
          <div class="product-price">
                  <h6>$29.00</h6>
                </div>
                <div class="product-button"><a class="button button-secondary button-nina" href="shopping-cart.html">add to cart</a></div>
              </div>
            </div>
            <div class="col-md-6 col-lg-4 col-xl-3 col-xxl-3">
              <div class="product">
                <div class="product-image"><a href="product-page.html"><img src="/images/book-08-188x246.jpg" alt="" width="188" height="246"/></a></div>
                <div class="product-title">
                  <h5><a href="product-page.html">How to Create a Web Design<br class="d-none d-xxl-block">&nbsp;
                      Style Guide</a></h5>
                </div>
                <div class="product-price">
                  <h6>$29.00</h6>
                </div>
                <div class="product-button"><a class="button button-secondary button-nina" href="shopping-cart.html">add to cart</a></div>
              </div>
            </div>
          </div>
        </div>
      </section>

<?php
$script = <<< JS
var f = AjaxFormSubmit();
f.success = function(data, form) {
    alert('success');
    console.log(data);
};
JS;
$this->registerJs($script);
?>
