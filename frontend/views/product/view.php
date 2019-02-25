<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
?>
<!-- Product Page-->
<section class="section section-lg bg-default">
  <!-- section wave-->
  <div class="container container-bigger product-single">
    <div class="row row-fix justify-content-sm-center justify-content-lg-between row-30 align-items-lg-center">
      <div class="col-lg-5 col-xl-6 col-xxl-5">
        <div class="product-single-preview">
          <div class="unit flex-column flex-md-row align-items-md-center unit-spacing-md-midle unit--inverse unit-sm">
            <div class="unit-body">
              <ul class="product-thumbnails">
                <!-- <li class="active" data-large-image="<?=$model->getImageUrl('420x550');?>"><img src="<?=$model->getImageUrl('54x71');?>" alt="" width="54" height="71"></li> -->
                <li class="active" data-large-image="/images/shop-01-420x550.png"><img src="/images/shop-01-54x71.png" alt="" width="54" height="71"></li>
                <li data-large-image="/images/shop-02-420x550.png"><img src="/images/shop-02-10x71.png" alt="" width="10" height="71"></li>
              </ul>
            </div>
            <div class="unit-right product-single-image">
                    <!-- <div class="product-single-image-element"><img class="product-image-area animateImageIn" src="/images/shop-01-420x550.png" alt=""></div> -->
              <div class="product-single-image-element"><img class="product-image-area animateImageIn" src="<?=$model->getImageUrl('420x550');?>" alt=""></div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-lg-7 col-xl-6 col-xxl-6 text-center text-lg-left">
        <div class="heading-5">Joanne Schultz</div>
        <h3><?=$model->title;?></h3>
        <div class="divider divider-default"></div>
        <p class="text-spacing-sm"><?php //$model->excerpt;?></p>
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

<section class="section section-lg bg-default">
  <!-- section wave-->
  <div class="container container-wide">
    <div class="row row-fix row-50 justify-content-lg-center">
      <div class="col-lg-11 col-xl-10 col-xxl-6">
        <div class="tabs-custom tabs-horizontal tabs-line text-center" id="tabs-1">
          <!-- Nav tabs-->
          <ul class="nav nav-tabs nav-tabs-checkout">
            <li class="nav-item"><a class="nav-link active" href="#tabs-1-1" data-toggle="tab">Shipping Info</a></li>
            <li class="nav-item"><a class="nav-link" href="#tabs-1-2" data-toggle="tab">Payment Method</a></li>
          </ul>
          <!-- Tab panes-->
          <div class="tab-content">
            <div class="tab-pane fade show active" id="tabs-1-1">
              <form class="rd-mailform text-left form-fix">
                <div class="row row-20 row-fix">
                  <div class="col-md-6">
                    <div class="form-wrap form-wrap-validation">
                      <label class="form-label-outside" for="forms-3-name">First name</label>
                      <input class="form-input" id="forms-3-name" type="text" name="name" data-constraints="@Required">
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-wrap form-wrap-validation">
                      <label class="form-label-outside" for="forms-3-last-name">Last name</label>
                      <input class="form-input" id="forms-3-last-name" type="text" name="last-name" data-constraints="@Required">
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-wrap form-wrap-validation">
                      <label class="form-label-outside" for="forms-3-company">Company</label>
                      <input class="form-input" id="forms-3-company" type="text" name="company">
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-wrap form-wrap-validation">
                      <label class="form-label-outside" for="forms-3-city">City</label>
                      <input class="form-input" id="forms-3-city" type="text" name="city">
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-wrap form-wrap-validation">
                      <label class="form-label-outside" for="form-1-email">E-mail</label>
                      <input class="form-input" id="form-1-email" type="email" name="email" data-constraints="@Email @Required">
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-wrap form-wrap-validation">
                      <label class="form-label-outside" for="form-1-phone">Phone</label>
                      <input class="form-input" id="form-1-phone" type="text" name="phone" data-constraints="@Numeric @Required">
                    </div>
                  </div>
                  <div class="col-sm-12">
                    <div class="form-wrap form-wrap-validation">
                      <label class="form-label-outside" for="forms-3-street-address">Address</label>
                      <input class="form-input" id="forms-3-street-address" type="text" name="street-address">
                    </div>
                  </div>
                  <div class="col-lg-12 offset-custom-1">
                    <div class="form-button text-md-right">
                      <button class="button button-secondary button-nina" type="submit">checkout</button>
                    </div>
                  </div>
                </div>
              </form>
            </div>
            <div class="tab-pane fade" id="tabs-1-2">
              <div class="table-checkout text-left">
                <div class="table-novi table-custom-responsive">
                  <table class="table-custom">
                    <tbody>
                      <tr>
                        <td>Cart Subtotal</td>
                        <td>$58.00</td>
                      </tr>
                      <tr>
                        <td>Shipping</td>
                        <td>Free Delivery</td>
                      </tr>
                      <tr>
                        <td>Total</td>
                        <td>$58.00</td>
                      </tr>
                    </tbody>
                  </table>
                </div>
                <div class="form-wrap">
                  <ul class="radio-group">
                    <li>
                      <label class="radio-inline">
                        <input type="radio" name="radio-group" checked="">Direct Bank Transfer
                      </label>
                    </li>
                    <li>
                      <label class="radio-inline">
                        <input type="radio" name="radio-group">Cheque Payment
                      </label>
                    </li>
                    <li>
                      <label class="radio-inline">
                        <input type="radio" name="radio-group">Paypal
                      </label>
                    </li>
                  </ul>
                </div><a class="button button-secondary button-nina" href="#">place order</a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<?php
$script = <<< JS
//var f = AjaxFormSubmit();
JS;
$this->registerJs($script);
?>
