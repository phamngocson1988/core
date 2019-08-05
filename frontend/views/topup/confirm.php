<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;
use yii\helpers\ArrayHelper;
use yii\bootstrap\ActiveForm;
?>

<?php Pjax::begin(); ?>
<?php $form = ActiveForm::begin(['options' => ['data-pjax' => 'true', 'autocomplete' => 'off']]); ?>
<?= $form->field($item, 'scenario', [
  'options' => ['tag' => false],
  'template' => '{input}'
])->hiddenInput()->label(false) ?>
<section class="topup-page">
  <div class="container">
    <div class="small-container">
      <div class="row">
        <div class="col col-lg-12 col-md-12 col-sm-12 col-12">
          <div class="affiliate-top no-mar-bot">
            <div class="has-left-border has-shadow no-mar-top">
              MY SHOPPING KINGCOINS
            </div>
          </div>
          <div class="cart-table">
            <table class="responsive-table">
              <thead>
                <tr>
                  <th>Pricing Package</th>
                  <th>Coins</th>
                  <th>Quantity</th>
                  <th>Total Coins</th>
                  <th>Price</th>
                  <th>Total Price</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td data-title="Pricing Package"><?=$item->title;?></td>
                  <td data-title="Coins"><?=number_format($item->getCoin());?></td>
                  <td data-title="Quantity">
                    <?= $form->field($item, 'quantity', [
                      'options' => ['class' => 'quantity-box'],
                      'inputOptions' => [
                        'id' => 'quantity', 
                        'type' => 'number', 
                        'min' => '1', 
                      ],
                      'template' => '<button type="button" class="minus">-</button>{input}<button type="button" class="plus">+</button>'
                    ])->textInput() ?>
                  </td>
                  <td data-title="Total Coins"><?=number_format($item->getTotalCoin());?> <?= $cart->hasPromotion() ? '(+' . $cart->getPromotionCoin() . ')' : '';?></td>
                  <td data-title="Price">$<?=number_format($item->getPrice());?></td>
                  <td data-title="Total Price" class="co-orange">$<?=number_format($item->getTotalPrice());?></td>
                </tr>
              </tbody>
            </table>
            <div class="cart-coupon">
              <?php if ($cart->hasPromotion()) : ?>
              <?php $promotion = $cart->getPromotionItem();?>
              <input type="text" name="promotion_code" id="voucher" class="fl-left" placeholder="Enter your voucher" value="<?=$promotion->code;?>" readonly>
              <button class="cus-btn yellow fl-left apply-coupon-btn" id="clear_voucher" type="button">Clear</button>
              <?php else : ?>
              <input type="text" name="promotion_code" id="voucher" class="fl-left" placeholder="Enter your voucher" value="<?=$promotion_code;?>">
              <button class="cus-btn yellow fl-left apply-coupon-btn" id="apply_voucher" type="button">Apply</button>
              <?php endif;?>
              <a href="<?=Url::to(['topup/checkout']);?>" data-pjax=false class="cus-btn yellow fl-right topup-cart-submit-btn">Check Out</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<?php ActiveForm::end(); ?>
<?php Pjax::end(); ?>

<?php
$script = <<< JS
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
$('body').on('click', '#remove_voucher', function(e){
  e.preventDefault();
  e.stopImmediatePropagation();
  $('#voucher').val('').prop('disabled', false);
  $(this).closest('form').submit();
  return false;
});

$('body').on('click', '.quantity-box button.plus', function(){
    var _qty = $('#quantity').val();
    _qty = parseInt(_qty)+1;
    $('#quantity').val(_qty);
    $("#quantity").trigger('change');
});

$('body').on('click', '.quantity-box button.minus', function(){
    var _qty = $('#quantity').val();
    if(parseInt(_qty) > 1){
        _qty = parseInt(_qty)-1;
    }
    $('#quantity').val(_qty);
    $("#quantity").trigger('change');
});

JS;
$this->registerJs($script);
?>
