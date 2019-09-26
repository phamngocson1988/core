<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;
use yii\helpers\ArrayHelper;
use yii\bootstrap\ActiveForm;
use frontend\components\cart\CartItem;
use frontend\components\cart\Cart;
use frontend\widgets\RadioListInput;

$this->registerJsFile('vendor/assets/global/plugins/jquery-inputmask/jquery.inputmask.bundle.min.js', ['depends' => '\frontend\assets\AppAsset']);
?>

<section class="checkout-page">
  <div class="container">
    <div class="checkout-block">
      <div class="checkout-navigation">
        <div class="row">
          <div class="col col-lg-12 col-md-12 col-sm-12 col-12">
            <div class="checkout-navigation-tabs has-shadow">
              <div class="ck-tab ck-tab-cart active">
                <span>1</span><span>Cart</span>
              </div>
              <div class="ck-tab ck-tab-payment-confirm">
                <span>2</span><span>Payment Confirm</span>
              </div>
              <div class="ck-tab ck-tab-payment-method">
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

                  <?=$form->field($item, 'platform', [
                      'labelOptions' => ['class' => 't-flex-item-center radio'],
                    ])->widget(RadioListInput::className(), [
                    'items' => ['android' => 'Android', 'ios' => 'IOS'],
                    'options' => ['class' => 'radio-form-control']
                  ])?>

                  <?= $form->field($item, 'character_name', [
                    'template' => '{input}{label}{error}',
                    'options' => ['class' => 'form-group t-input'],
                    'labelOptions' => ['class' => 'placeholder'],
                    'inputOptions' => ['placeholder' => ' ']
                  ])->textInput()->label('Character Name');?>
                  
                 
                  <?= $form->field($item, 'login_method', [
                    'options' => ['class' => 'form-group t-input t-select'],
                    'inputOptions' => ['id' => 'login_method', 'style' => 'padding: .375rem .30rem'],
                    'labelOptions' => ['class' => 'mobile-hide']
                  ])->dropDownList(['account' => 'Game account', 'facebook' => 'Facebook', 'google' => 'Google'], ['prompt' => 'Login Method'])->label('Login Method');?>

                  <?= $form->field($item, 'username', [
                    'template' => '{input}{label}{error}',
                    'options' => ['class' => 'form-group t-input'],
                    'labelOptions' => ['class' => 'placeholder'],
                    'inputOptions' => ['placeholder' => ' ']
                  ])->textInput()->label('Account Login');?>

                  <?= $form->field($item, 'password', [
                    'template' => '{input}{label}{error}',
                    'options' => ['class' => 'form-group t-input'],
                    'labelOptions' => ['class' => 'placeholder'],
                    'inputOptions' => ['placeholder' => ' ']
                  ])->textInput()->label('Account Password');?>

                  <?= $form->field($item, 'server', [
                    'template' => '{input}{label}{error}',
                    'options' => ['class' => 'form-group t-input'],
                    'labelOptions' => ['class' => 'placeholder'],
                    'inputOptions' => ['placeholder' => ' ']
                  ])->textInput()->label('Server');?>

                  <?= $form->field($item, 'saler_code', [
                    'options' => ['tag' => false],
                    'inputOptions' => ['id' => 'saler_code'],
                    'template' => '{input}'
                  ])->hiddenInput(); ?>

                  <?= $form->field($item, 'recover_code', [
                    'template' => '{input}{label}{error}',
                    'options' => ['class' => 'form-group t-input'],
                    'labelOptions' => ['class' => 'placeholder'],
                    'inputOptions' => ['placeholder' => ' ', 'id' => 'recover_code'],
                    'hintOptions' => ['style' => 'font-size: 12px; color: #6f5e5e; padding-left: 37%']
                  ])->textInput()->label('Recovery Code')->hint('The recovery code should contain 6 or 8 digits, and kindly provide at cleast 3 codes. Ex: 12345678 13456578 12252546');?>

                  <?= $form->field($item, 'note', [
                    'template' => '{input}{label}{error}',
                    'options' => ['class' => 'form-group t-input'],
                    'labelOptions' => ['class' => 'placeholder'],
                    'inputOptions' => ['placeholder' => ' ']
                  ])->textInput()->label('Special note (optional)');?>

                  <div class="form-group">
                    <label class="control-label" for="cartitem-note"></label>
                    <div class="recovery-code-guid-box">
                      <div class="top">
                          The recovery code is very necessary, kindly support!
                      </div><br/>
                      <div class="bottom">
                          <div>How to?</div>
                          <div>Get Google code, <a href="https://youtu.be/F3xMAXFRHNE" target="_blank" style="color: blue;">click here</a></div>
                          <div>Get Facebook code, <a href="https://youtu.be/sG1GAcsslzs" target="_blank" style="color: blue;">click here</a></div>
                      </div>
                    </div>
                  </div>
                </div>

                <?php ActiveForm::end(); ?>

                <?php Pjax::begin(); ?>
                <?php $form = ActiveForm::begin(['options' => ['data-pjax' => 'true']]); ?>
                <?=Html::hiddenInput('scenario', CartItem::SCENARIO_EDIT_CART);?>
                <?php $item->setScenario(CartItem::SCENARIO_EDIT_CART);?>
                <div class="checkout-cart-total">
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
                              <?= $form->field($item, 'quantity', [
                              'options' => ['tag' => false],
                              'inputOptions' => ['class' => 'form-control txt-qty', 'id' => 'quantity'],
                              'template' => '{input}'
                              ])->dropDownList(CartItem::$quantites);?>
                            </td>
                            <td id="unit"><?=number_format($item->getTotalUnit());?></td>
                            <td>
                                <?php $showOriginPrice = ($item->original_price && $item->original_price > $item->getPrice());?>
                                <?php if ($showOriginPrice) : ?>
                                <div class="origin-price">
                                    $<span id="origin-price"><?=number_format($item->getOriginalPrice() * $item->quantity, 1);?></span>
                                </div>
                                <?php endif;?>
                                <div class="sale-price">
                                    $<span id="price"><?=number_format($item->getTotalPrice(), 1);?></span>
                                </div>
                            </td>
                          </tr>
                          <tr class="instant-total">
                            <td colspan="3">Instant Total:</td>
                            <td class="instant-total-number">$<span id="instant-price"><?=number_format($item->getTotalPrice(), 1);?></span></td>
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
                        <?php $saler_code = Yii::$app->session->get('saler_code');?>
                        <?=Html::input('text', 'saler_code', $saler_code, [
                          'readonly' => (boolean)$saler_code, 
                          'placeholder' => "Enter supporter's code",
                          'class' => 'fl-left',
                          'id' => 'saler_code_value'
                        ]);?>
                      </div>
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
                <div class="ck-submit-form cart-term-condition">
                  <div class="terms-row">
                      <input type="checkbox" style="margin-right: 7px;margin-top: 4px;" id="policy_risk"><span>I confirm that by making this purchase I understand and agree with the <a href="<?=Url::to(['site/term', 'slug' => 'risk']);?>" target="_blank" style="color: blue">lossing sharing term</a></span>
                  </div>
                  <div class="terms-row">
                      <input type="checkbox" style="margin-right: 7px;margin-top: 4px;" id="policy_no_refund"><span>By making this purchase, I'm confirming that I totally understand <a href="<?=Url::to(['site/term', 'slug' => 'no_refund']);?>" target="_blank" style="color: blue">no refund policy</a></span>
                  </div>
                </div>

                <div class="ck-submit-form">
                  <a class="btn-product-detail-add-to-cart" href="javascript:;" id="update-cart-button">Submit</a>
                </div>
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
  if (!$('#policy_risk').is(':checked')) {
    alert('You need to agree with our lossing sharing term.');
    return false;
  }
  if (!$('#policy_no_refund').is(':checked')) {
    alert('You need to agree with our no refund policy.');
    return false;
  }
  $('form#infor-form').submit();
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
$("#ck-cart-box").on('blur', '#saler_code_value', function(){
  console.log($(this).val());
  $('#saler_code').val($(this).val());
});

JS;
$this->registerJs($script);
?>
