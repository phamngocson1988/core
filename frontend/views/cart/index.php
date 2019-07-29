<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;
use yii\helpers\ArrayHelper;
use yii\bootstrap\ActiveForm;
use frontend\components\cart\CartItem;
use frontend\components\cart\Cart;
$this->registerJsFile('vendor/assets/global/plugins/jquery-inputmask/jquery.inputmask.bundle.min.js', ['depends' => '\frontend\assets\AppAsset']);
?>

<section class="checkout-page">
  <div class="container">
    <div class="checkout-block">
      <div class="checkout-navigation">
        <div class="row">
          <div class="col col-lg-12 col-md-12 col-sm-12 col-12">
            <div class="checkout-navigation-tabs has-shadow">
              <div class="ck-tab-cart active">
                <span>1</span><span>Cart</span>
              </div>
              <div class="ck-tab-payment-confirm">
                <span>2</span><span>Payment Confirm</span>
              </div>
              <div class="ck-tab-payment-method">
                <span>3</span><span>Payment Methods</span>
              </div>
            </div>
            <div class="checkout-tabs-content">
              <div class="ck-tab-content active" id="ck-cart-box">
                <?php $form = ActiveForm::begin(['id' => 'infor-form', 'action' => ['cart/index']]); ?>
                  <?=Html::hiddenInput('scenario', CartItem::SCENARIO_INFO_CART);?>
                  <?php $item->setScenario(CartItem::SCENARIO_INFO_CART);?>
                  <div class="game-info">
                    <div class="form-group">
                      <label>Game</label>
                      <span><?=$item->title;?></span>
                    </div>
                    <?= $form->field($item, 'platform')->radioList(['android' => 'Android', 'ios' => 'Ios'], ['class' => 'radio-form-control']) ?>
                    <?= $form->field($item, 'character_name')->textInput()->label('Character Name');?>
                    <?= $form->field($item, 'username')->textInput()->label('Account Login');?>
                    <?= $form->field($item, 'password')->textInput()->label('Account Password');?>
                    <?= $form->field($item, 'server')->textInput()->label('Server');?>
                    <?= $form->field($item, 'login_method', ['inputOptions' => ['id' => 'login_method']])->dropDownList(['facebook' => 'Facebook', 'google' => 'Google'], ['prompt'=>'Choose one method'])->label('Login Method');?>
                    <?= $form->field($item, 'recover_code', ['inputOptions' => ['id' => 'recover_code']])->textInput()->label('Recovery Code')->hint('Example: 12345678 11112222 33334444');?>
                    <?= $form->field($item, 'note')->textInput()->label('Note for saler');?>

                    <!-- <div class="form-group">
                      <label>
                        Recovery Code
                        <div class="recovery-code-hint-box">
                          <div class="top">
                            This codes is necessary, unless you would like to wait
                            for a long time.
                          </div>
                          <div class="bottom">
                            <div><strong>How to?</strong></div>
                            <div>Get Google code, <a href="#">click here</a></div>
                            <div>Get Facebook code, <a href="#">click here</a></div>
                          </div>
                        </div>
                      </label>
                      <div class="multi-control">
                        <input class="form-control" type="text">
                        <input class="form-control" type="text">
                      </div>
                    </div> -->
                  </div>
                  <?php ActiveForm::end(); ?>

                  <?php Pjax::begin(); ?>
                  <?php $form = ActiveForm::begin(['options' => ['data-pjax' => 'true']]); ?>
                  <?=Html::hiddenInput('scenario', CartItem::SCENARIO_EDIT_CART);?>
                  <?php $item->setScenario(CartItem::SCENARIO_EDIT_CART);?>
                  <div class="checkout-cart-total">
                    <div class="game-name">
                      <div class="game-image">
                        <img src="<?=$item->getImageUrl('300x300');?>" alt="">
                      </div>
                      <h2><?=$item->getLabel();?></h2>
                    </div>
                    <div class="game-totals">
                      <div class="product-temple-total">
                        <table>
                          <thead>
                            <tr>
                              <th>Unit</th>
                              <th>Quantity</th>
                              <th>Unit Total</th>
                              <th>Price</th>
                            </tr>
                          </thead>
                          <tbody>
                            <tr>
                              <td><?=number_format($item->pack);?> <?=strtoupper($item->unit_name);?></td>
                              <td>
                                <?php if ($item->gameUnits) : ?>
                                <?php 
                                $units = [];
                                foreach ($item->gameUnits as $unit) {
                                    $units[(string)$unit->quantity] = $unit->quantity;
                                }
                                ?>
                                    <?= $form->field($item, 'quantity', [
                                    'options' => ['tag' => false],
                                    'inputOptions' => ['class' => 'form-control txt-qty', 'id' => 'quantity'],
                                    'template' => '{input}'
                                    ])->dropDownList($units);?>
                                <?php else : ?>
                                <div class="quantity-box">
                                    <button class="quantity-minus" type="button">-</button>
                                    <?= $form->field($item, 'quantity', [
                                    'template' => '{input}', 
                                    'options' => ['tag' => false],
                                    'inputOptions' => ['class' => 'txt-qty', 'type' => 'number', 'min' => 0.5, 'step' => 0.5, 'id' => 'quantity'],
                                    ])->textInput() ?>
                                    <button class="quantity-plus" type="button">+</button>
                                </div>
                                <?php endif; ?>
                              </td>
                              <td id="unit"><?=number_format($item->getTotalUnit());?></td>
                              <td>
                                  <?php $showOriginPrice = ($item->original_price && $item->original_price > $item->price);?>
                                  <?php if ($showOriginPrice) : ?>
                                  <div class="origin-price">
                                      $<span id="origin-price"><?=number_format($item->getOriginalPrice() * $item->quantity);?></span>
                                  </div>
                                  <?php endif;?>
                                  <div class="sale-price">
                                      $<span id="price"><?=number_format($item->getTotalPrice());?></span>
                                  </div>
                              </td>
                            </tr>
                            <tr class="instant-total">
                              <td colspan="3">Instant Total:</td>
                              <td class="instant-total-number">$<span id="instant-price"><?=number_format($item->getTotalPrice());?></span></td>
                            </tr>
                          </tbody>
                          <tfoot>
                            <tr>
                              <td colspan="3">Sub Total Unit:</td>
                              <td><span class="subtotal-num"><?=number_format($cart->getSubTotalUnit());?></span></td>
                            </tr>
                            <?php if ($cart->hasPromotion()) : ?>
                            <tr>
                              <td colspan="3">Promotion:</td>
                              <td>+<span class="saving-number"><?=$cart->getPromotionUnit();?></span></td>
                            </tr>
                            <?php endif;?>
                            <tr class="tr-grand-total">
                              <td colspan="3">Grand Total Unit:</td>
                              <td><span><?=number_format($cart->getTotalUnit());?></span></td>
                            </tr>
                          </tfoot>
                        </table>
                      </div>
                      <div class="cart-table">
                        <div class="cart-coupon">
                          <?php if ($cart->hasPromotion()) : ?>
                          <?php $promotion = $cart->getPromotionItem();?>
                          <input type="text" name="promotion_code" id="voucher" class="fl-left" placeholder="Enter your voucher" value="<?=$promotion->code;?>" readonly>
                          <button class="cus-btn yellow fl-left apply-coupon-btn" id="clear_voucher" type="button">Clear</button>
                          <?php else : ?>
                          <input type="text" name="promotion_code" id="voucher" class="fl-left" placeholder="Enter your voucher" value="<?=$promotion_code;?>">
                          <button class="cus-btn yellow fl-left apply-coupon-btn" id="apply_voucher" type="button">Apply</button>
                          <?php endif;?>
                        </div>
                      </div>
                    </div>
                  </div>
                  <?php ActiveForm::end(); ?>
                  <?php Pjax::end(); ?>
                  <div class="ck-submit-form">
                    <a class="btn-product-detail-add-to-cart" href="javascript:;" id="update-cart-button">Submit</a>
                  </div>
                </form>
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
$('body').on('click', "#update-cart-button", function(){
  var form = $('form#infor-form');
  $.ajax({
      url: form.attr('action'),
      type: form.attr('method'),
      dataType : 'json',
      data: form.serialize(),
      success: function (result, textStatus, jqXHR) {
        if (!result.status) {
          if (result.errors) {
            alert(result.errors);
          }
        } else {
          window.location.href = result.checkout_url;
        }
      },
  });
});

$('body').on('change', "#quantity", function(){
  $(this).closest('form').submit();
});
$('body').on('click', '#apply_voucher', function(e){
  e.preventDefault();
  e.stopImmediatePropagation();
  if ($('#voucher').val()) $(this).closest('form').submit();
  return false;
});
$('body').on('click', '#clear_voucher', function(e){
  e.preventDefault();
  e.stopImmediatePropagation();
  if ($('#voucher').val('')) $(this).closest('form').submit();
  return false;
});


$('.product-temple-total .quantity-box button.quantity-plus').click(function(){
    var _qty = $('.product-temple-total .quantity-box input').val();
    var step = 0.5;
    if (_qty < 10) {
        _qty = parseFloat(_qty) + step;
        $('.product-temple-total .quantity-box input').val(_qty);
        $("#quantity").trigger('change');
    }
});

$('.product-temple-total .quantity-box button.quantity-minus').click(function(){
    var _qty = $('.product-temple-total .quantity-box input').val();
    _qty = parseFloat(_qty);
    var step = 0.5;
    if (_qty > step) {
        _qty = _qty - step;
        $('.product-temple-total .quantity-box input').val(_qty);
        $("#quantity").trigger('change');
    } 
});
$("#quantity").trigger('change');
JS;
$this->registerJs($script);
?>
