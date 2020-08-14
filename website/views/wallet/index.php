<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\bootstrap\ActiveForm;
use website\widgets\LinkPager;
use yii\widgets\Pjax;
$user = Yii::$app->user->getIdentity();
$setting = Yii::$app->settings;
$kCoinBanner = $setting->get('ApplicationSettingForm', 'kcoin_banner', '/images/sidebar-ads.jpg');
$kCoinBannerLink = $setting->get('ApplicationSettingForm', 'kcoin_banner_link', 'javascript:;');
?>
<div class="container my-5 my-wallet">
  <div class="row">
    <div class="col-md-12 mb-3">
      <h1 class="text-uppercase text-red">kcoin wallet</h1>
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="/">Home</a></li>
          <li class="breadcrumb-item active" aria-current="page">Wallet</li>
        </ol>
      </nav>
    </div>
    <div class="col-md-8">
      <div class="row">
        <div class="col-md-12 mb-4">
          <div class="card-balance row">
            <div class="col-md-6 p-4 bg-blue">
              <p class="mb-2">Balance</p>
              <div class="balance-val"><?=number_format($user->walletBalance());?> <span style="font-size: 16px;">Kcoin</span></div>
            </div>
            <div class="col-md-6 p-4 bg-green">
              <p class="mb-2">Add Kcoin to Wallet</p>
              <div class="input-group inp-deposit">
                <input type="number" step="100" id="quantity" value="0" class="form-control">
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-6">
          <p class="lead mb-2">Payment method</p>
          <div class="btn-group-toggle multi-choose multi-choose-payment d-flex flex-wrap" data-toggle="buttons">
            <?php foreach ($paygates as $paygate) : ?>
            <label class="btn flex-fill btn-secondary">
              <input type="radio" name="identifier" value="<?=$paygate->identifier;?>" data-currency="<?=$paygate->currency;?>" data-exchange-rate="<?=$paygate->currency;?>" data-transfer_fee="<?=$paygate->transfer_fee;?>" data-transfer_fee_type="<?=$paygate->transfer_fee_type;?>" autocomplete="off">
              <img class="icon" src="<?=$paygate->getImageUrl();?>" />
            </label>
            <?php endforeach;?>
          </div>
          <div class="input-group my-3">
            <input type="text" class="form-control" id="voucher" data-valid="0" placeholder="Enter promo code here"
              aria-label="Enter promo code here" aria-describedby="button-addon2">
            <div class="input-group-append">
              <button class="btn btn-warning text-white" type="button" id="applyVocherBtn">Accept</button>
            </div>
          </div>
        </div>
        <div class="col-md-6">
          <!-- CART SUMMARY -->
          <p class="lead mb-2">Billing infomation</p>
          <div class="card card-summary">
            <div class="card-body" id="subtotal-kingcoin-row">
              <div class="d-flex">
                <div class="flex-fill w-100">Kcoin</div>
                <div class="flex-fill w-100 text-right font-weight-bold" id="subtotal-kingcoin">0 KC</div>
              </div>
              <div class="d-flex" id="bonus-kingcoin-row">
                <div class="flex-fill w-100">Bonus</div>
                <div class="flex-fill w-100 text-right font-weight-bold" id="bonus-kingcoin">0 KC</div>
              </div>
              <div class="d-flex" id="total-kingcoin-row">
                <div class="flex-fill w-100">Total KC</div>
                <div class="flex-fill w-100 text-right text-red" id="total-kingcoin">0 KC</div>
              </div>
              <hr />
              <div class="d-flex" id="subtotal-payment-row">
                <div class="flex-fill w-100">Subtotal</div>
                <div class="flex-fill text-red font-weight-bold w-100 text-right" id="subtotal-payment">$0.0</div>
              </div>
              <div class="d-flex" id="transfer-fee-row">
                <div class="flex-fill w-100">Transfer fee</div>
                <div class="flex-fill text-red font-weight-bold w-100 text-right" id="transfer-fee">$0.0</div>
              </div>
              <div class="d-flex" id="total-payment-row">
                <div class="flex-fill w-100">Total payment</div>
                <div class="flex-fill text-red font-weight-bold w-100 text-right" id="total-payment">$0.0</div>
              </div>
              <!-- <a href="#" data-toggle="modal" data-target="#paymentGame" class="mt-3 btn btn-block btn-payment">Pay now</a> -->
              <button id="payNowButton" class="mt-3 btn btn-block btn-payment">Pay now</button>
            </div>
          </div>
          <!-- END SUMMARY -->
        </div>
        <div class="col-md-12 mt-3">
          <p class="lead py-2">Pending transaction</p>
          <div class="table-wrapper table-responsive bg-white">
            <table class="table table-hover table-transaction">
              <thead>
                <tr>
                  <th scope="col">No</th>
                  <th scope="col">Method</th>
                  <th scope="col">Total KC</th>
                  <th scope="col">Status</th>
                  <th scope="col">Transaction</th>
                  <th scope="col">Receipt</th>
                </tr>
              </thead>
              <tbody>
                <?php if (!$pendings) : ?>
                <tr><td colspan="6" class="text-center">No data found</td></tr>
                <?php endif;?>
                <?php foreach ($pendings as $pending) : ?>
                <tr>
                  <th scope="row">
                    <a href="javascript:;" class="view-detail" data-id="<?=$pending->id;?>"><?=$pending->getId();?></a>
                    <span class="date-time"><?=$pending->created_at;?></span>
                  </th>
                  <td><?=$pending->payment_method;?></td>
                  <td class="text-center"><span class="text-red"><?=number_format($pending->total_coin);?> KC</span></td>
                  <td class="text-center"><?=$pending->status;?></td>
                  <td>
                    <?php if (!$pending->payment_id) : ?>
                    <button type="button" class="btn btn-red text-uppercase view-detail" data-id="<?=$pending->id;?>">Submit</button>
                    <?php else :?>
                    <?=$pending->payment_id;?>
                    <?php endif;?>
                  </td>
                  <td>
                    <button type="button" class="btn btn-upload view-detail" data-id="<?=$pending->id;?>">Upload</button>
                    <?php if ($pending->evidence) : ?>
                      <a href="<?=$pending->evidence;?>" target="_blank">View</a>
                    <?php endif;?>
                  </td>
                </tr>
                <?php endforeach;?>
              </tbody>
            </table>
          </div>
          <!-- END TABLE -->
        </div>
      </div>
    </div>
    <?php if ($kCoinBanner) : ?>
    <div class="col-md-4">
      <a href="<?=$kCoinBannerLink;?>"><img src="<?=$kCoinBanner;?>" /></a>
    </div>
    <?php endif;?>
  </div>

  <!-- Transaction History Table -->
  <hr class="my-5" />
  <div class="d-flex bd-highlight justify-content-between align-items-center orders-history-wrapper mb-3">
    <p class="lead mb-0">Transaction history</p>
    <?php $form = ActiveForm::begin(['method' => 'get']);?>
    <div class="d-flex ml-auto">
      <?= $form->field($search, 'start_date', [
        'options' => ['class' => 'flex-fill d-flex align-items-center mr-3'],
        'labelOptions' => ['class' => 'd-block w-100 mr-2 mb-0'],
        'inputOptions' => ['class' => 'form-control', 'type' => 'date', 'name' => 'start_date', 'data-date-format'=>"DD MMMM YYYY"]
      ])->textInput()->label('Start date') ?>
      <?= $form->field($search, 'end_date', [
        'options' => ['class' => 'flex-fill d-flex align-items-center mr-3'],
        'labelOptions' => ['class' => 'd-block w-100 mr-2 mb-0'],
        'inputOptions' => ['class' => 'form-control', 'type' => 'date', 'name' => 'end_date']
      ])->textInput()->label('End date') ?>
      <?= $form->field($search, 'status', [
        'options' => ['class' => 'flex-fill d-flex align-items-center mr-3'],
        'labelOptions' => ['class' => 'd-block w-100 mr-2 mb-0'],
        'inputOptions' => ['class' => 'form-control', 'name' => 'status']
      ])->dropdownList($search->fetchStatusList(), ['prompt' => 'Select status'])->label('Status') ?>
      <div class="flex-fill">
        <button class="btn btn-primary" type="submit">Filter</button>
      </div>
    </div>
    <?php ActiveForm::end(); ?>
  </div>
  <div class="table-wrapper table-responsive bg-white">
    <table class="table table-hover">
      <thead>
        <tr>
          <th scope="col">ID</th>
          <th scope="col">Method</th>
          <th scope="col">Total KC</th>
          <th scope="col">Status</th>
          <th scope="col">Receipt</th>
          <th scope="col">Transaction number</th>
          <th scope="col">Details</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($transactions as $transaction): ?>
        <tr>
          <th scope="row">
            <a href="javascript:;" class="view-detail" data-id="<?=$transaction->id;?>"><?=$transaction->getId();?></a>
            <span class="date-time"><?=$transaction->created_at;?></span>
          </th>
          <td><?=$transaction->payment_method;?></td>
          <td class="text-center"><span class="text-red"><?=number_format($transaction->total_coin);?> KC</span></td>
          <td class="text-center"><?=$transaction->status;?></td>
          <td>
            <?php if ($transaction->evidence) : ?>
            <a href="<?=$transaction->evidence;?>" class="view-detail" target="_blank" class="text-red" >View invoice</a>
            <?php endif;?>
          </td>
          <td><?=$transaction->payment_id;?></td>
          <td><?=$transaction->remark;?></td>
        </tr>
        <?php endforeach;?>
        <tr>
          <th scope="row" class="text-right">TOTAL</th>
          <th scope="row" class="text-right"></th>
          <td colspan="5"><b class="text-red"><?=number_format($search->getCommand()->sum('total_coin'), 1);?> KC</b></td>
        </tr>
      </tbody>
    </table>
  </div>
  <nav aria-label="Page navigation" class="mt-2 mb-5">
    <?=LinkPager::widget([
      'pagination' => $pages,
      'options' => ['class' => 'pagination justify-content-end']
    ]);?>
  </nav>
</div>
<!-- END Transaction History Table -->

<!-- Modal order detail-->
<div class="modal fade modal-kg" id="paymentGame" tabindex="-1" role="dialog"
  aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog  modal-lg modal-dialog-centered" role="document">
    <div class="modal-content">
      
    </div>
  </div>
</div>
<!-- end modal order detail -->
<?php
$script = <<< JS
$("input[name=identifier]:first").trigger('click');
function Calculator() {
  // Elements
  var quantityElement = $('#quantity');
  var voucherElement = $('#voucher');
  var paygateElement = $( "input[name=identifier]:checked" );
  // Calculate
  var quantity = quantityElement.val();
  var voucher = voucherElement.val();
  var paygate = paygateElement.val();

  $.ajax({
      url: '/wallet/calculate.html',
      type: "POST",
      dataType: 'json',
      data: {
        quantity: quantity,
        voucher: voucher,
        paygate: paygate,
      },
      success: function (result, textStatus, jqXHR) {
          console.log('Calculator', result);
          if (result.status) {
            ShowSummary(result.data)
          } else {
            toastr.error(result.errors.join('<br/>')); 
          }
      },
  });
};

function Purchase() {
  // Elements
  var quantityElement = $('#quantity');
  var voucherElement = $('#voucher');
  var paygateElement = $( "input[name=identifier]:checked" );
  // Calculate
  var quantity = quantityElement.val();
  var voucher = voucherElement.val();
  var paygate = paygateElement.val();

  $.ajax({
      url: '/wallet/purchase.html',
      type: "POST",
      dataType: 'json',
      data: {
        quantity: quantity,
        voucher: voucher,
        paygate: paygate,
      },
      success: function (result, textStatus, jqXHR) {
          console.log('Purchase', result);
          if (result.status) {
            ShowTransaction(result.data)
          } else {
            toastr.error(result.errors.join('<br/>')); 
          }
      },
  });
};

function ShowSummary(data) {
  // Elements
  var quantityElement = $('#quantity');
  var voucherElement = $('#voucher');
  var paygateElement = $( "input[name=identifier]:checked" );
  // Html
  var subTotalKingcoinRow = $("#subtotal-kingcoin-row");
  var subTotalKingcoin = $("#subtotal-kingcoin");
  var bonusKingcoinRow = $("#bonus-kingcoin-row");
  var bonusKingcoin = $("#bonus-kingcoin");
  var totalKingcoinRow = $("#total-kingcoin-row");
  var totalKingcoin = $("#total-kingcoin");

  var subTotalPaymentRow = $("#subtotal-payment-row");
  var subTotalPayment = $("#subtotal-payment");
  var totalPaymentRow = $("#total-payment-row");
  var totalPayment = $("#total-payment");
  var transferFeeRow = $("#transfer-fee-row");
  var transferFee = $("#transfer-fee");

  // bonusKingcoin: 0
  // subTotalKingcoin: "200"
  // subTotalPayment: "200"
  // totalKingcoin: "200"
  // totalPayment: "200"
  // transferFee: "3.0"
  // voucherApply: false

  bonusKingcoin.html(data.bonusKingcoin + ' KC');
  totalKingcoin.html(data.totalKingcoin + ' KC');
  subTotalKingcoin.html(data.subTotalKingcoin + ' KC');

  subTotalPayment.html('$' + data.subTotalPayment);
  transferFee.html('$' + data.transferFee);
  totalPayment.html('$' + data.totalPayment);
}

function ShowTransaction(id) {
  $.ajax({
      url: '/wallet/view.html',
      type: "GET",
      dataType: 'json',
      data: {
        id: id,
      },
      success: function (result, textStatus, jqXHR) {
          console.log('ShowTransaction', result);
          if (result.status) {
            $('#paymentGame .modal-content').html(result.data);
            $('#paymentGame').modal('show');
          } else {
            toastr.error(result.errors); 
          }
      },
  });
}

$('#quantity').on('change', function(e) {
  Calculator();
});
$('#applyVocherBtn').on('click', function(e) {
  Calculator();
});
$( "input[name=identifier]:radio" ).on('change', function(e) {
  console.log('radio click');
  Calculator();
});
$( "#payNowButton").on('click', function() {
  Purchase();
});

$('#paymentGame').on('click', '#update-payment-button', function(e) {
  e.preventDefault();
  var baseForm = $(this).closest('form');
  var url = baseForm.attr('action');
  var form = new FormData();
  baseForm.find('input').each(function( index ) {
    var elementName = $(this).attr('name');
    if ($(this).attr('type') == 'file') {
      $.each(this.files, function( index, value ) {
        form.append(elementName, value);
      });
    } else {
      form.append(elementName, $(this).val());
    }
  });
  $.ajax({
      url: url,
      type: "POST",
      processData: false, // important
      contentType: false, // important
      dataType : 'json',
      data: form,
      success: function (result, textStatus, jqXHR) {
        if (!result.status) {
          toastr.error(result.errors);
        } else {
          toastr.success(result.message);
        }
      },
  });
});
// ShowTransaction(16870763); 

// open view detail
$(".view-detail").on('click', function() {
  var id = $(this).data('id');
  ShowTransaction(id); 
});
JS;
$this->registerJs($script);
?>
