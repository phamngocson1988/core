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
<section class="section section-lg bg-default">
  <div class="container container-wide">
    <div class="row row-fix justify-content-lg-center">
      <div class="col-xl-11 col-xxl-8">
        <div class="table-novi table-custom-responsive table-shop-responsive">
          <table class="table-custom table-shop table">
            <thead>
              <tr>
                <th style="width: 30%;">Game</th>
                <th style="width: 10%;">King Coin</th>
                <th style="width: 10%;"><?=ucfirst($item->getUnitName());?></th>
                <th style="width: 20%;">Quantity</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>
                  <div class="unit flex-row align-items-center">
                    <div class="unit-left"><a href="<?=Url::to(['game/view', 'id' => $item->game_id]);?>"><img src="<?=$item->getGame()->getImageUrl('71x71');?>" alt="" width="54" height="71"/></a></div>
                    <div class="unit-body"><a class="text-gray-darker" style="white-space: normal;" href="javascript:;"><?=$item->getLabel();?></a></div>
                  </div>
                </td>
                <td id="price"><?=number_format($item->getTotalPrice());?></td>
                <td id="unit"><?=number_format($item->getTotalPack());?></td>
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
          <div class="cells-sm-2 col-xl-3 col-xxl-2 text-md-right">
            <div class="heading-5 text-regular">Sub total: <span><?=number_format($cart->getSubTotalPrice());?></span></div>
          </div>
          <div class="cells-sm-3 col-xl-3 col-xxl-3 text-md-right">
            <div class="heading-5 text-regular">Total: <span><?=number_format($cart->getTotalPrice());?></span></div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<?php ActiveForm::end(); ?>
<?php Pjax::end(); ?>

<?php $form = ActiveForm::begin(['id' => 'update-cart', 'action' => ['cart/update']]); ?>
<section class="section section-lg bg-default novi-background bg-cover text-center">
  <!-- section wave-->
  <div class="container">
    <div class="row row-fix justify-content-sm-center">
      <div class="col-md-10 col-xl-8">
        <h3>Account Information</h3>
        <div class="row row-fix row-20">
          <div class="col-md-6">
            <?= $form->field($item, 'username', [
              'options' => ['class' => 'form-wrap form-wrap-validation'],
              'inputOptions' => ['class' => 'form-input'],
              'labelOptions' => ['class' => 'form-label-outside'],
              'errorOptions' => ['tag' => 'span', 'class' => 'form-validation'],
              'template' => '{label}{input}{error}'
            ])->textInput() ?>
          </div>
          <div class="col-md-6">
            <?= $form->field($item, 'password', [
              'options' => ['class' => 'form-wrap form-wrap-validation'],
              'inputOptions' => ['class' => 'form-input'],
              'labelOptions' => ['class' => 'form-label-outside'],
              'errorOptions' => ['tag' => 'span', 'class' => 'form-validation'],
              'template' => '{label}{input}{error}'
            ])->textInput() ?>
          </div>
          <div class="col-md-6">
            <?= $form->field($item, 'character_name', [
              'options' => ['class' => 'form-wrap form-wrap-validation'],
              'inputOptions' => ['class' => 'form-input'],
              'labelOptions' => ['class' => 'form-label-outside'],
              'errorOptions' => ['tag' => 'span', 'class' => 'form-validation'],
              'template' => '{label}{input}{error}'
            ])->textInput() ?>
          </div>
          <div class="col-md-6">
            <?= $form->field($item, 'recover_code', [
              'options' => ['class' => 'form-wrap form-wrap-validation'],
              'inputOptions' => ['class' => 'form-input'],
              'labelOptions' => ['class' => 'form-label-outside'],
              'errorOptions' => ['tag' => 'span', 'class' => 'form-validation'],
              'template' => '{label}{input}{error}'
            ])->textInput() ?>
          </div>
          <div class="col-md-6">
            <?= $form->field($item, 'server', [
              'options' => ['class' => 'form-wrap form-wrap-validation'],
              'inputOptions' => ['class' => 'form-input'],
              'labelOptions' => ['class' => 'form-label-outside'],
              'errorOptions' => ['tag' => 'span', 'class' => 'form-validation'],
              'template' => '{label}{input}{error}'
            ])->textInput() ?>
          </div>
          <div class="col-md-6">
            <?= $form->field($item, 'note', [
              'options' => ['class' => 'form-wrap form-wrap-validation'],
              'inputOptions' => ['class' => 'form-input'],
              'labelOptions' => ['class' => 'form-label-outside'],
              'errorOptions' => ['tag' => 'span', 'class' => 'form-validation'],
              'template' => '{label}{input}{error}'
            ])->textInput() ?>
          </div>
          <div class="col-md-6">
            <?= $form->field($item, 'platform', [
              'options' => ['class' => 'form-wrap form-wrap-validation'],
              'inputOptions' => ['class' => 'form-input-outside select-filter'],
              'errorOptions' => ['tag' => 'span', 'class' => 'form-validation'],
              'labelOptions' => ['class' => 'form-label-outside'],
              'template' => '{label}{input}{hint}{error}'
            ])->dropDownList(['android' => 'Android', 'ios' => 'Ios']) ?>
          </div>
          <div class="col-md-6">
            <?= $form->field($item, 'login_method', [
              'options' => ['class' => 'form-wrap form-wrap-validation'],
              'inputOptions' => ['class' => 'form-input-outside select-filter'],
              'errorOptions' => ['tag' => 'span', 'class' => 'form-validation'],
              'labelOptions' => ['class' => 'form-label-outside'],
              'template' => '{label}{input}{hint}{error}'
            ])->dropDownList(['facebook' => 'Facebook', 'google' => 'Google']) ?>
          </div>
          <div class="col-md-12">
            <article class="inline-message">
              <p><strong style="color: red">*** Important Notes:</strong></p>
              <p>For the fastest process, kindly double check the provided details,ensure that: <strong>"Server"</strong>, <strong>"Login method"</strong> & <strong>"Recovery code"</strong> are provided.</p>
              <p>Thanks for your cooperation!</p>
            </article>
          </div>
          <div class="col-lg-12 offset-custom-1">
            <div class="form-button text-md-right">
              <?= Html::submitButton('checkout', ['class' => 'button button-secondary button-nina', 'id' => 'update-cart-button']) ?>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<?php ActiveForm::end(); ?>
<?php
$script = <<< JS
$('body').on('click', "#update-cart-button", function(){
  var form = $(this).closest('form');
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
