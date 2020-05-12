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
                <td><?=$item->getPricing()->title;?></td>
                <td>$<?=number_format($item->getPrice());?></td>
                <td>$<?=number_format($item->getTotalPrice());?></td>
                <td><?=number_format($item->getPricing()->num_of_coin);?></td>
                <td><?=number_format($item->getPricing()->num_of_coin * $item->quantity);?></td>
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
                <?= $form->field($discount, 'code', [
                  'options' => ['class' => 'form-wrap'],
                  'inputOptions' => ['class' => 'form-input', 'id' => 'voucher', 'readonly' => $cart->hasDiscount()],
                  'labelOptions' => ['class' => 'form-label'],
                  'errorOptions' => ['tag' => 'span', 'class' => 'form-validation'],
                  'template' => '{input}{error}{label}'
                ])->textInput()->label('Enter your voucher'); ?>
                <?php if ($cart->hasDiscount()) : ?>
                <button id="remove_voucher" class="button form-button button-sm button-secondary button-nina">Remove</button>
                <?php else : ?>
                <button id="apply_voucher" class="button form-button button-sm button-secondary button-nina">Apply</button>
                <?php endif;?>
              </div>
            </div>
          </div>
          <div class="cells-sm-5 col-xl-6 col-xxl-5 text-md-right">
            <ul class="inline-list">
              <li class="text-middle">
                <div class="heading-5 text-regular">$<?=number_format($cart->getTotalPrice());?></div>
              </li>
              <li class="text-middle"><a class="button button-secondary button-nina" href="<?=Url::to(['pricing/checkout']);?>">checkout</a></li>
            </ul>
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
