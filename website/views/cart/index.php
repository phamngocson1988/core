<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;
use yii\helpers\ArrayHelper;
use yii\bootstrap\ActiveForm;
use common\components\helpers\StringHelper;
?>

<div class="container my-5">
  <div class="d-flex multi-step justify-content-between align-items-center active_step2">
    <div class="flex-fill">
      <div class="num"><a href="#">01</a></div>
      <p>Place Order</p>
    </div>
    <div class="flex-fill">
      <div class="num"><a href="#">02</a></div>
      <p>Order Details</p>
    </div>
    <div class="flex-fill">
      <div class="num"><a href="#">03</a></div>
      <p>Payment</p>
    </div>
    <div class="flex-fill">
      <div class="num"><a href="#">04</a></div>
      <p>Completed</p>
    </div>
  </div>
</div>
<div class="container my-5 single-order">
  <?php $form = ActiveForm::begin(['options' => ['id' => 'update-cart-form', 'data-calculatecart-url' => Url::to(['cart/calculate', 'id' => $model->id])]]);?>
  <div class="row">
    <div class="col-md-5 info">
      <p class="lead mb-2">Infomation Character</p>
      <hr/>
      <div class="alert alert-danger d-flex justify-content-between align-items-center d-block w-100" role="alert">
        <img class="icon-md flex-fill mr-3" src="./images/icon/risk.svg"/>
        <div class="flex-fill">WARNING: Please input infomations very carefully or the order may take much longer time to be completed!!</div>
      </div>
      <?= $form->field($model, 'login_method', [
        'inputOptions' => ['id' => 'login_method', 'class' => 'form-control'],
      ])->dropdownList($model->fetchLoginMethod(), ['prompt' => 'Login method'])->label(false) ?>
      <?= $form->field($model, 'character_name')->textInput(['placeholder' => 'Character name'])->label(false) ?>
      <?= $form->field($model, 'username')->textInput(['placeholder' => 'Account Login'])->label(false) ?>
      <?= $form->field($model, 'password')->textInput(['placeholder' => 'Account Password'])->label(false) ?>
      <?= $form->field($model, 'server')->textInput(['placeholder' => 'Server'])->label(false) ?>
      <?= $form->field($model, 'recover_code', [
        'options' => ['class' => 'form-group'],
        'inputOptions' => ['id' => 'recover_code', 'class' => 'form-control'],
        'template' => '<div class="input-group mb-3">{input}<div class="custom-file">
          <input type="file" class="custom-file-input" id="inputGroupFile02" name="recover_file">
          <label class="custom-file-label upload-filename" for="inputGroupFile02" aria-describedby="inputGroupFileAddon02">Upload</label>
        </div></div>{error}'
      ])->textInput(['placeholder' => 'Recovery Code'])->label(false) ?>
      <?= $form->field($model, 'recover_file_id', [
          'options' => ['tag' => false],
          'template' => '{input}',
          'inputOptions' => ['id' => 'recover_file_id']
        ])->hiddenInput()->label(false) ?>
      <p><small>(*) The recovery code should contain 8 degits, and kindly provide at least 3 codes. <br />
        Ex: 12345678 12345678 12345678</small></p>
        <p>
          <a class="text-red mr-4" href="https://youtu.be/F3xMAXFRHNE" target="_blank">How to get Google Code?</a><a class="text-red" href="https://youtu.be/sG1GAcsslzs" target="_blank">How to get Facebook Code?</a>
        </p>
        <?= $form->field($model, 'note')->textInput(['placeholder' => 'Special note (optional)'])->label(false) ?>
        <div class="custom-control custom-checkbox">
          <input type="checkbox" class="custom-control-input" id="policy1">
          <label class="custom-control-label" for="policy1">I’ve read & agreed with <a class="text-red" href="javascript:;" data-toggle="modal" data-target="#disclaimer_policies">Disclaimer policies</a> of service</label>
        </div>
        <div class="custom-control custom-checkbox">
          <input type="checkbox" class="custom-control-input" id="policy2">
          <label class="custom-control-label" for="policy2">By making this purchase, I’m confirming that I totally under-stand <a class="text-red" href="javascript:;" data-toggle="modal" data-target="#noRefundModal">no refund policy</a></label>
        </div>
    </div>
    <div class="col-md-7">
      <p class="lead mb-2">Your cart</p>
      <hr/>
      <!-- ORDER ITEM -->
      <div class="order-item mb-3 d-flex justify-content-between align-items-center">
        <img class="thumb flex-fill mr-3" src="<?=$model->getImageUrl('100x100');?>" />
        <ul class="flex-fill list-unstyled mr-auto mb-0 w-100">
          <li><?=$model->title;?></li>
          <li><span class="text-bold text-green" data-target='total-unit'><?=sprintf("%s %s", number_format($model->getTotalUnit()), strtoupper($model->getUnitName()));?></span></li>
          <li>Version Global</li>
        </ul>
        <div class="flex-fill w-100">
          <div class="d-flex justify-content-between align-items-center">
            <div class="flex-fill w-100 p-2">
              <p class="m-0 text-red font-weight-bold"><strike data-target="origin">$<?=StringHelper::numberFormat($model->getTotalOriginalPrice(), 2);?></strike></p>
              <p class="m-0 text-red font-weight-bold" data-target='price'>$<?=StringHelper::numberFormat($model->getTotalPrice(), 2);?></p>
            </div>
            <div class="flex-fill w-100">
              <?= $form->field($model, 'quantity', [
                'options' => ['class' => 'add-quantity d-flex justify-content-between align-items-center'],
                'template' => '<span class="flex-fill minus"><img class="icon-sm" src="/images/icon/minus.svg"/></span>
                {input}
                <span class="flex-fill plus"><img class="icon-sm" src="/images/icon/plus.svg"/></span>',
                'inputOptions' => ['class' => 'quantity-value flex-fill text-center', 'id' => 'quantity']
              ])->textInput(); ?>
            </div>
            
          </div>
        </div>
      </div><!-- END ORDER ITEM -->
      <div class="row">
        <div class="col-lg-6">
          <?= $form->field($model, 'voucher', [
            'options' => ['class' => 'input-group my-3'],
            'template' => '{input}<div class="input-group-append"><button class="btn btn-warning text-white" type="button" id="apply-voucher-button">Accept</button></div>',
            'inputOptions' => ['class' => 'form-control', 'id' => 'voucher', 'placeholder' => 'Enter promo code here']
          ])->textInput(); ?>
        </div>
        <div class="col-lg-6">
          <?= $form->field($model, 'saler_code', [
            'options' => ['class' => 'input-group my-3'],
            'template' => '{input}<div class="input-group-append"><button class="btn btn-warning text-white" type="button">Submit</button></div>',
            'inputOptions' => ['class' => 'form-control', 'placeholder' => 'Enter Supporter\'s code']
          ])->textInput(); ?>
        </div>
      </div>
      
      <!-- CART SUMMARY -->
      <div class="card card-summary">
        <h5 class="card-header text-uppercase">Card summary</h5>
        <div class="card-body">
          <p class="card-text text-red font-weight-bold">Game: <?=$model->title;?></p>
          <p class="text-green card-text font-weight-bold" id="unit-game"><?=sprintf("%s %s", number_format($model->getUnit()), strtoupper($model->getUnitName()));?> x <span data-target="quantity"><?=$model->quantity;?></span></p>
          <p class="card-text">Version Global</p>
          <h5 class="card-title">Price Details</h5>
          <hr />
          <div class="d-flex">
            <div class="flex-fill w-100">Price</div>
            <div class="flex-fill w-100 text-right" data-target="price">$<?=StringHelper::numberFormat($model->getTotalPrice(), 2);?></div>
          </div>
          <div class="d-flex">
            <div class="flex-fill w-100 text-danger">Discount</div>
            <div class="flex-fill w-100 text-danger text-right">$0</div>
          </div>
          <hr />
          <div class="d-flex mb-3">
            <div class="flex-fill text-red font-weight-bold w-100">Total</div>
            <div class="flex-fill text-red font-weight-bold w-100 text-right" data-target="price">$<?=StringHelper::numberFormat($model->getTotalPrice(), 2);?></div>
          </div>
          <button type="submit" class="btn btn-block btn-payment text-uppercase">Payment method</button>
        </div>
      </div>
      <!-- END SUMMARY -->
    </div>
  </div>
  <?php ActiveForm::end(); ?>
</div>

<?php 
$noRefundContent = Yii::$app->settings->get('TermsConditionForm', 'no_refund');
$disclaimerPolicies = Yii::$app->settings->get('TermsConditionForm', 'disclaimer_policies');
?>
<div class="modal fade" id="noRefundModal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">No Refund Policy</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <?=$noRefundContent;?>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="disclaimer_policies" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Disclaimer policies</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <?=$disclaimerPolicies;?>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<?php
$script = <<< JS
// Review Form
function calculateCart() {
  var form = $('form#update-cart-form');
  var calculateUrl = form.data('calculatecart-url');
  $.ajax({
      url: calculateUrl,
      type: 'POST',
      dataType : 'json',
      data: form.serialize(),
      success: function (result, textStatus, jqXHR) {
        if (result.status == false) {
            toastr.error(result.errors);
        } else {
            $('[data-target="price"]').html('$' + result.data.amount);
            $('[data-target="origin"]').html('$' + result.data.origin);
            $('[data-target="total-unit"]').html(result.data.unit);
            $('[data-target="quantity"]').html($('#quantity').val());
        }
      },
  });
}
$('#quantity').on('change', function() {  
  calculateCart();
});

$('form#update-cart-form').on('submit', function() {
  if (!$('#policy1').is(':checked') || !$('#policy2').is(':checked')) {
    toastr.error('You need to agree with our policies');
    return false;
  }
});

// upload image
var uploadRecover = new AjaxUploadFile({
  file_element: '#inputGroupFile02',
});
uploadRecover.callback = function(result) {
  result.forEach(function(element) {
    $('#recover_file_id').val(element.id);
  });
};

$('form#update-cart-form').on('click', '.minus', function () {
  var _input = $(this).parent().find('input');
  var count = parseInt(_input.val()) - 1;
  count = count < 1 ? 1 : count;
  _input.val(count);
  _input.change();
  return false;
});

$('form#update-cart-form').on('click', '.plus', function () {
  var _input = $(this).parent().find('input');
  _input.val(parseInt(_input.val()) + 1);
  _input.change();
  return false;
});
$('form#update-cart-form').on('click', '#apply-voucher-button', function() {
  console.log('voucher', $('#voucher').val());
  if (!$('#voucher').val()) return;
  calculateCart();
});
JS;
$this->registerJs($script);
?>