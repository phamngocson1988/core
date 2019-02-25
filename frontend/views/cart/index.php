<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
?>

<section class="section section-lg bg-default">
  <div class="container container-wide">
    <div class="row row-fix justify-content-lg-center">
      <div class="col-xl-11 col-xxl-8">
        <div class="table-novi table-custom-responsive table-shop-responsive">
          <table class="table-custom table-shop table">
            <thead>
              <tr>
                <th>#</th>
                <th>Game</th>
                <th>Package</th>
                <th>Price</th>
                <th>Quantity</th>
                <th></th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($items as $item) :?>
              <tr>
                <td>1</td>
                <td>
                  <div class="unit flex-row align-items-center">
                    <div class="unit-left"><a href="product-page.html"><img src="/images/book-01-54x71.jpg" alt="" width="54" height="71"/></a></div>
                    <div class="unit-body"><a class="text-gray-darker" style="white-space: normal;" href="javascript:;"><?=$item->getGame()->title;?></a></div>
                  </div>
                </td>
                <td><?=$item->getLabel();?></td>
                <td><?=$item->getPrice();?></td>
                <td>
                  <div class="form-wrap box-width-1 shop-input">
                    <input class="form-input input-append" id="form-element-stepper-1" type="number" min="1" max="300" value="<?=$item->quantity;?>">
                  </div>
                </td>
                <td> <a class="icon mdi mdi-close icon-md-middle icon-gray-1" href="#"></a></td>
              </tr>
              <?php endforeach;?>
            </tbody>
          </table>
        </div>
        <div class="row row-fix justify-content-between align-items-md-center text-center">
          <div class="col-md-7 col-xl-6 cell-xxl-5">
            <!-- RD Mailform: Subscribe-->
            <form class="rd-mailform form-fix rd-mailform-inline rd-mailform-sm rd-mailform-inline-modern" data-form-type="subscribe" method="post">
              <div class="rd-mailform-inline-inner">
                <div class="form-wrap">
                  <input class="form-input" type="text" name="text" data-constraints="@Required" id="subscribe-form-email-1">
                  <label class="form-label" for="subscribe-form-email-1">Coupon Code</label>
                </div>
                <button class="button form-button button-sm button-secondary button-nina" type="submit">apply coupon</button>
              </div>
            </form>
          </div>
          <div class="cells-sm-5 col-xl-6 col-xxl-5 text-md-right">
            <ul class="inline-list">
              <li class="text-middle">
                <div class="heading-5 text-regular">$58.00</div>
              </li>
              <li class="text-middle"><a class="button button-secondary button-nina" href="checkout.html">checkout</a></li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
