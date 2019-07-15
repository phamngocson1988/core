<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;
use yii\helpers\ArrayHelper;
use yii\bootstrap\ActiveForm;
use frontend\components\cart\CartItem;
use frontend\components\cart\Cart;

$item = $cart->getItem();
?>

<?php $form = ActiveForm::begin(['id' => 'update-cart1']); ?>
<?=Html::hiddenInput('scenario', CartItem::SCENARIO_RECEPTION_CART);?>
<section class="section section-lg bg-default novi-background bg-cover text-center">
  <!-- section wave-->
  <div class="container">
    <div class="row row-fix justify-content-sm-center">
      <div class="col-md-10 col-xl-8">
        <h3>Reception Email</h3>
        <div class="row row-fix row-20">
          <div class="col-md-6">
            <?= $form->field($item, 'reception_email', [
              'options' => ['class' => 'form-wrap form-wrap-validation'],
              'inputOptions' => ['class' => 'form-input'],
              'labelOptions' => ['class' => 'form-label-outside'],
              'errorOptions' => ['tag' => 'span', 'class' => 'form-validation'],
              'template' => '{label}{input}{error}'
            ])->textInput() ?>
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
$('body').on('click', "#update-cart-button1", function(){
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
