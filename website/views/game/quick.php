<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
$calculateUrl = Url::to(['cart/calculate-bulk', 'id' => $model->id]);
$addCartUrl = Url::to(['cart/bulk', 'id' => $model->id]);
$orderLink = Url::to(['order/index']);
$templateFile = Yii::$app->settings->get('ImportSettingForm', 'import_reseller_template');
?>
<div class="container my-5 single-order">
  <div class="row">
    <div class="col-md-12">
      <h1 class="text-uppercase text-red">Quick buy For "<?=$model->title;?>"</h1>
    </div>
    <?= Html::beginForm(['game/quick', 'id' => $model->id, 'slug' => $model->slug], 'POST', ['enctype' => 'multipart/form-data']); ?>
    <?=Html::fileInput('excel', null, ['accept' => '.xlsx', 'style' => 'display: none', 'id' => 'upload-excel-input']);?>
    <?= Html::endForm(); ?>
    <div class="col-md-12" id="bulk-cart">
      <div style="display:flex; justify-content: space-between;">
        <p class="lead mb-2">Your cart</p>
        <div>
          <a href="<?=$templateFile;?>" class="btn btn-primary" role="button" aria-pressed="true">
            <img class="icon-btn" src="/images/icon/more.svg"/> Download
          </a>
          <button type="button" class="btn btn-green" id="upload-excel-button">
            <img class="icon-btn" src="/images/icon/more.svg"/> Upload
          </button>
        </div>
      </div>
    <hr/>
      <!-- ORDER ITEM -->
      <?php foreach ($initData as $data) : ?>
      <?php $form = ActiveForm::begin(['action' => $addCartUrl, 'options' => ['class' => 'row-item-form']]);?>
      <div class="order-item mb-3 d-flex justify-content-between align-items-center">
        <img class="thumb flex-fill mr-3" src="<?=$model->getImageUrl('150x150');?>" />
        <ul class="flex-fill list-unstyled mr-auto mb-0 w-100">
          <li><?=$model->title;?></li>
          <li><span class="text-bold text-red game-unit"><?=sprintf("%s %s", number_format($model->getUnit()), strtoupper($model->getUnitName()));?></span></li>
          <li><?=$version;?></li>
        </ul>
        <div class="flex-fill w-100">
          <div class="d-flex justify-content-between align-items-center">
            <div class="flex-fill w-100 p-2">
              <p class="m-0 text-red font-weight-bold"><strike>$<span class="game-original-price"><?=number_format($model->getOriginalPrice() * (int)$data[0]);?></span></strike></p>
              <p class="m-0 text-red font-weight-bold">$<span class="game-price"><?=number_format($model->getPrice() * (int)$data[0]);?></span></p>
            </div>
            <div class="flex-fill w-100">
              <div class="add-quantity d-flex justify-content-between align-items-center">
                <span class="flex-fill minus">
                  <img class="icon-sm" src="/images/icon/minus.svg"/>
                </span>
                <?= $form->field($model, 'quantity', [
                  'options' => ['tag' => false],
                  'template' => '{input}',
                  'inputOptions' => ['class' => 'quantity-value flex-fill text-center', 'name' => 'quantity', 'value' => $data[0]]
                ])->textInput()->label(false) ?>
                <span class="flex-fill plus">
                  <img class="icon-sm" src="/images/icon/plus.svg"/>
                </span>
              </div>
            </div>
            <div class="flex-fill w-100 p-2 text-center">
              <a href="javascript:;" class="trash"><img class="icon-sm" src="/images/icon/trash-can.svg"/></a>
            </div>
          </div>
        </div>
        <?= $form->field($model, 'raw', [
          'options' => ['class' => 'flex-fill w-100'],
          'template' => '{label}{input}',
          'inputOptions' => ['class' => 'form-control raw', 'rows' => '3', 'placeholder' => 'Enter infomation here ...', 'name' => 'raw', 'value' => $data[1]]
        ])->textArea() ?>
      </div><!-- END ORDER ITEM -->
      <?php ActiveForm::end(); ?>
      <?php endforeach; ?>
    </div>
    <div class="col-md-12 text-right">
      <button type="button" class="btn btn-red" id="add-row">
        <img class="icon-btn" src="/images/icon/more.svg"/> Add order
      </button>
    </div>
    <div class="col-md-6">
      <!-- CART SUMMARY -->
      <div class="card card-summary">
        <h5 class="card-header text-uppercase">Cart summary</h5>
        <div class="card-body">
          <p class="card-text text-red font-weight-bold">Game: <?=$model->title;?> (<?=$package;?>)</p>
          <p class="text-red card-text font-weight-bold"><?=sprintf("%s %s", number_format($model->getUnit()), strtoupper($model->getUnitName()));?> x <span class="summary-quantity">1</span></p>
          <p class="card-text"><?=$version;?></p>
          <h5 class="card-title">Price Details</h5>
          <hr />
          <div class="d-flex">
            <div class="flex-fill w-100">Total Order</div>
            <div class="flex-fill w-100 text-right summary-total-order">1</div>
          </div>
          <div class="d-flex">
            <div class="flex-fill w-100">Total Pack</div>
            <div class="flex-fill w-100 text-right summary-quantity">1</div>
          </div>
          <div class="d-flex">
            <div class="flex-fill w-100">Price</div>
            <div class="flex-fill w-100 text-right summary-price">$100.0</div>
          </div>
          <hr />
          <div class="d-flex mb-3">
            <div class="flex-fill text-red font-weight-bold w-100">Total</div>
            <div class="flex-fill text-red font-weight-bold w-100 text-right summary-price">$43.0</div>
          </div>
          <a href="javascript:;" class="btn btn-block btn-payment text-uppercase" id="checkout-button">Check out</a>
        </div>
      </div>
      <!-- END SUMMARY -->
    </div>
  </div>
</div>
<?php
$script = <<< JS
// Review Form
function calculateCart(form) {
  $.ajax({
      url: '$calculateUrl',
      type: 'POST',
      dataType : 'json',
      data: form.serialize(),
      success: function (result, textStatus, jqXHR) {
        if (result.status == false) {
            toastr.error(result.errors);
        } else {
            form.find('.game-price').html(result.data.amount);
            form.find('.game-original-price').html(result.data.origin);
            form.find('.game-unit').html(result.data.unit);
            renderSummary();
        }
      },
  });
}
function purchase() {
  var forms = $('#bulk-cart').find('form.row-item-form');
  if (!forms.length) return;
  var form = forms[0];
  $.ajax({
      url: $(form).attr('action'),
      type: 'POST',
      dataType : 'json',
      data: $(form).serialize(),
      success: function (result, textStatus, jqXHR) {
        if (result.status == false) {
          toastr.error(result.errors);
        } else {
          $(form).remove();
          purchase();
        }
      },
  });
}
function renderSummary() {
  console.log('renderSummary');
  var items = $('#bulk-cart').find('.quantity-value');
  var prices = $('#bulk-cart').find('.game-price');
  
  var quantity = 0;
  var price = 0;
  
  $.each(items, function( index, item ) {
    var q = $(item).val();
    if (isNaN(q)) q = 0;
    console.log('item', q, parseFloat(q));
    quantity += parseFloat(q);
  });
  quantity = parseFloat(quantity).toFixed(2);

  $.each(prices, function( index, pItem ) {
    var q = $(pItem).html();
    q=q.replace(/\,/g,'');
    if (isNaN(q)) q = 0;
    price += parseFloat(q);
  });

  $('.summary-quantity').html(quantity);
  $('.summary-price').html('$' + formatMoney(price));
  $('.summary-total-order').html(items.length);

  // Check wallet
  var balance = parseFloat('$balance');
  if (price > balance) {
    $('#checkout-button').hide();
  } else {
    $('#checkout-button').show();
  }
}


$('#bulk-cart').on('change', '.quantity-value', function() {  
  var form = $(this).closest('form.row-item-form');
  standarizeQuantity(this);
  calculateCart(form);
});
function standarizeQuantity(ele) {
  if (!validateQuantity(ele)) {
    $(ele).val(1);
  }
}
function validateQuantity(ele) {
    var num = $(ele).val();
    num = parseFloat(num);
    $(ele).val(num);
    if (num <= 0) {
        return false;
    }
    if (isNaN(num)) {
        return false;
    }
    return true;
}
$('#bulk-cart').on('click', '.trash', function() {
  var len = $('#bulk-cart').find('form.row-item-form').length;
  if (len > 1) {
    $(this).closest('form.row-item-form').remove();
    renderSummary();
  }
});
$('#bulk-cart').on('click', '.minus', function () {
  var _input = $(this).parent().find('input');
  var count = parseInt(_input.val()) - 1;
  count = count < 1 ? 1 : count;
  _input.val(count);
  _input.change();
  return false;
});

$('#bulk-cart').on('click', '.plus', function () {
  var _input = $(this).parent().find('input');
  _input.val(parseInt(_input.val()) + 1);
  _input.change();
  return false;
});

$('#add-row').on('click', function() {
  var newId = Date.now();
  var row = $('#bulk-cart').find('form.row-item-form:last').clone();
  $(row).find('.raw').val('');
  $(row).attr('id', newId);
  $(row).appendTo('#bulk-cart');
  $(row).find('.quantity-value').val(1).change();
  renderSummary();
});
renderSummary();

function getFormData(form){
    var unindexed_array = form.serializeArray();
    var indexed_array = {};

    $.map(unindexed_array, function(n, i){
        indexed_array[n['name']] = n['value'];
    });

    return indexed_array;
}
$('#checkout-button').on('click', function(e) {
  if ($(this).hasClass('inprogress')) return;
  showLoader();
  $(this).addClass('inprogress');
  var that = this;
  e.stopPropagation();
  e.stopImmediatePropagation();
  e.preventDefault();
  var forms = $('#bulk-cart').find('form.row-item-form');
  var payload = {};
  $.each(forms, function( index, form ) {
    // purchase(form);
    payload[$(form).attr('id')] = getFormData($(form));
  });
  console.log(payload);
  $.ajax({
      url: '$addCartUrl',
      type: 'POST',
      dataType : 'json',
      data: payload,
      success: function (result, textStatus, jqXHR) {
        if (result.status == false) {
          toastr.error(result.errors);
          $(that).removeClass('inprogress');
          hideLoader();
        } else {
          toastr.success(result.success); 
          window.location.href = "$orderLink";
        }
      },
      error: function(data) {
        hideLoader();
      }
  });
  return false;
});

// Upload CSV
$('#upload-excel-button').click(function() {
  console.log('click');
  $('#upload-excel-input').trigger('click');
});
$('#upload-excel-input').on('change', function() {
  $(this).closest('form').submit();
});
JS;
$this->registerJs($script);
?>