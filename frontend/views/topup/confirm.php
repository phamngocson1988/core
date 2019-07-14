<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;
use yii\helpers\ArrayHelper;
use yii\bootstrap\ActiveForm;

$item = $cart->getItem();
?>

<?php Pjax::begin(); ?>
<?php $form = ActiveForm::begin(['options' => ['data-pjax' => 'true']]); ?>
<?= $form->field($item, 'scenario', [
  'options' => ['tag' => false],
  'template' => '{input}'
])->hiddenInput()->label(false) ?>
<section class="section section-lg bg-default">
  <div class="container container-wide">
    <div class="row row-fix justify-content-lg-center">
      <div class="col-xl-11 col-xxl-8">
        <div class="table-novi table-custom-responsive table-shop-responsive">
          <table class="table-custom table-shop table">
            <thead>
              <tr>
                <th style="width: 30%;">Pricing Package</th>
                <th style="width: 10%;">Price</th>
                <th style="width: 10%;">Total Price</th>
                <th style="width: 10%;">Coins</th>
                <th style="width: 10%;">Total Coins</th>
                <th style="width: 20%;">Quantity</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td><?=$item->title;?></td>
                <td>$<?=number_format($item->getPrice());?></td>
                <td>$<?=number_format($item->getTotalPrice());?></td>
                <td><?=number_format($item->num_of_coin);?></td>
                <td><?=number_format($item->num_of_coin * $item->quantity);?></td>
                <td>
                  <?= $form->field($item, 'quantity', [
                    'options' => ['class' => 'form-wrap box-width-1 shop-input'],
                    'inputOptions' => ['class' => 'form-input input-append',
                      'id' => 'quantity', 
                      'type' => 'number', 
                      'min' => '1', 
                    ],
                    'template' => '{input}'
                  ])->textInput() ?>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
        <div class="row row-fix justify-content-between align-items-md-center text-center">
          <div class="col-md-7 col-xl-6 cell-xxl-5">
            <!-- RD Mailform: Subscribe-->
            <div class="rd-mailform rd-mailform-inline rd-mailform-sm rd-mailform-inline-modern">
              <div class="rd-mailform-inline-inner">
                <input name="promotion_code" value="<?=$promotion_code;?>" id="voucher"/>
                <button id="apply_voucher" class="button form-button button-sm button-secondary button-nina">Apply</button>
              </div>
            </div>
          </div>
          <div class="cells-sm-2 col-xl-3 col-xxl-2 text-md-right">
            <div class="heading-5 text-regular">Sub total Coin: <span><?=number_format($cart->getSubTotalCoin());?></span></div>
          </div>
          <div class="cells-sm-3 col-xl-3 col-xxl-3 text-md-right">
            <div class="heading-5 text-regular">Total Coin: <span><?=number_format($cart->getTotalCoin());?> </span></div>
            <div class="heading-5 text-regular">Promotion Coin: <span><?=$cart->getPromotionCoin();?> </span></div>
            <div class="heading-5 text-regular"><a href="<?=Url::to(['topup/checkout']);?>" data-pjax=false>Checkout</a></div>
            
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
$('body').on('click', '#remove_voucher', function(e){
  e.preventDefault();
  e.stopImmediatePropagation();
  $('#voucher').val('').prop('disabled', false);
  $(this).closest('form').submit();
  return false;

})

JS;
$this->registerJs($script);
?>
