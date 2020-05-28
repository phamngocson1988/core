<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\bootstrap\ActiveForm;
$user = Yii::$app->user->getIdentity();
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
                <input type="number" step="100" id="quantity" value="100" class="form-control">
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
              <button class="btn btn-warning text-white" type="button" id="button-addon2" id="applyVocherBtn">Accept</button>
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
                <div class="flex-fill w-100 text-right font-weight-bold" id="subtotal-kingcoin">100 KC</div>
              </div>
              <div class="d-flex" id="bonus-kingcoin-row">
                <div class="flex-fill w-100">Bonus</div>
                <div class="flex-fill w-100 text-right font-weight-bold" id="bonus-kingcoin">10 KC</div>
              </div>
              <div class="d-flex" id="total-kingcoin-row">
                <div class="flex-fill w-100">Total KC</div>
                <div class="flex-fill w-100 text-right text-red" id="total-kingcoin">110 KC</div>
              </div>
              <hr />
              <div class="d-flex" id="subtotal-payment-row">
                <div class="flex-fill w-100">Subtotal</div>
                <div class="flex-fill text-red font-weight-bold w-100 text-right" id="subtotal-payment">$100.0</div>
              </div>
              <div class="d-flex" id="transfer-fee-row">
                <div class="flex-fill w-100">Transfer fee</div>
                <div class="flex-fill text-red font-weight-bold w-100 text-right" id="transfer-fee">$1.0</div>
              </div>
              <div class="d-flex" id="total-payment-row">
                <div class="flex-fill w-100">Total payment</div>
                <div class="flex-fill text-red font-weight-bold w-100 text-right" id="total-payment">$101.0</div>
              </div>
              <a href="#" data-toggle="modal" data-target="#paymentGame" class="mt-3 btn btn-block btn-payment">Pay
                now</a>
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
                  <th scope="col">Action</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <th scope="row">
                    <a href="#" data-toggle="modal" data-target="#detailOrder">#12345678</a>
                    <span class="date-time">2020-03-06 20:48</span>
                  </th>
                  <td>Paypal</td>
                  <td class="text-center"><span class="text-red">100 KC</span></td>
                  <td class="text-center">Pending</td>
                  <td><button type="button" class="btn btn-red text-uppercase">Submit</button></td>
                  <td><button type="button" class="btn btn-upload">Upload</button></td>
                  <td class="text-center"><img class="icon-sm btn-delete" src="/images/icon/trash-can.svg" /></td>
                </tr>
                <tr>
                  <th scope="row">
                    <a href="#" data-toggle="modal" data-target="#detailOrder">#12345678</a>
                    <span class="date-time">2020-03-06 20:48</span>
                  </th>
                  <td>Striller</td>
                  <td class="text-center"><span class="text-red">100 KC</span></td>
                  <td class="text-center">Unpaid</td>
                  <td>123456789</td>
                  <td><button type="button" class="btn btn-upload">Upload</button></td>
                  <td class="text-center"><img class="icon-sm btn-delete" src="/images/icon/trash-can.svg" /></td>
                </tr>
              </tbody>
            </table>
          </div>
          <!-- END TABLE -->
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <img src="/images/sidebar-ads.jpg" />
    </div>
  </div>

  <!-- Transaction History Table -->
  <hr class="my-5" />
  <div class="d-flex bd-highlight justify-content-between align-items-center orders-history-wrapper mb-3">
    <p class="lead mb-0">Transaction history</p>
    <div class="d-flex ml-auto">
      <div class="flex-fill d-flex align-items-center mr-3">
        <label class="d-block w-100 mr-2 mb-0">Start date</label>
        <input class="form-control" type="date" id="birthday" name="birthday" min="2017-04-01" max="2017-04-30">
      </div>
      <div class="flex-fill d-flex align-items-center mr-3">
        <label class="d-block w-100 mr-2 mb-0">End date</label>
        <input class="form-control" type="date" id="birthday" name="birthday" min="2017-04-01" max="2017-04-30">
      </div>
      <div class="flex-fill d-flex align-items-center mr-3">
        <label class="d-block w-100 mr-2 mb-0">Status</label>
        <select class="form-control" id="status">
          <option>1</option>
          <option>2</option>
          <option>3</option>
          <option>4</option>
          <option>5</option>
        </select>
      </div>
      <div class="flex-fill">
        <a class="btn btn-primary" href="#" role="button">Filter</a>
      </div>
    </div>
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
        <tr>
          <th scope="row">
            <a href="#">#12345678</a>
            <span class="date-time">2020-03-06 20:48</span>
          </th>
          <td>Paypal</td>
          <td class="text-center"><span class="text-red">100 KC</span></td>
          <td class="text-center">Paid</td>
          <td><a href="#" class="text-red" >View invoice</span></td>
          <td>
            123456789
          </td>
          <td>
            Transaction ID: 123456789
          </td>
        </tr>
        <tr>
          <th scope="row">
            <a href="#">#12345678</a>
            <span class="date-time">2020-03-06 20:48</span>
          </th>
          <td>Paypal</td>
          <td class="text-center"><span class="text-red">100 KC</span></td>
          <td class="text-center">Paid</td>
          <td><a href="#" class="text-red" >View invoice</span></td>
          <td>
            123456789
          </td>
          <td>
            Transaction ID: 123456789
          </td>
        </tr>
        <tr>
          <th scope="row" class="text-right">TOTAL</th>
          <td colspan="6"><b class="text-red">200 KC</b></td>
        </tr>
      </tbody>
    </table>
  </div>
  <nav aria-label="Page navigation" class="mt-2 mb-5">
    <ul class="pagination justify-content-end">
      <li class="page-item disabled">
        <a class="page-link" href="#" tabindex="-1">
          <img class="icon" src="/images/icon/back.svg"/>
        </a>
      </li>
      <li class="page-item"><a class="page-link" href="#">1</a></li>
      <li class="page-item"><a class="page-link" href="#">2</a></li>
      <li class="page-item"><a class="page-link" href="#">3</a></li>
      <li class="page-item">
        <a class="page-link" href="#">
          <img class="icon" src="/images/icon/next.svg"/>
        </a>
      </li>
    </ul>
  </nav>
</div>
<!-- END Transaction History Table -->

<!-- Modal order detail-->
<div class="modal fade modal-kg" id="paymentGame" tabindex="-1" role="dialog"
  aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog  modal-lg modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header d-block">
        <h2 class="modal-title text-center w-100 text-red text-uppercase">Payment Kcoin</h2>
        <p class="text-center d-block">Transaction ID: T12345678</p>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-6 border-right">
            <p><span class="list-item">Kcoin:</span><b>100 KC</b></p>
            <p><span class="list-item">Bonus:</span><b>10 KC</b></p>
            <p><span class="list-item">Total KC:</span><b class="text-red">110 KC</b></p>
            <hr />
            <p><span class="list-item">Subtotal:</span><b class="text-red">100 USD</b></p>
            <p><span class="list-item">Transfer fee:</span><b class="text-red">1 USD</b></p>
            <p><span class="list-item">Total payment:</span><b class="text-red">101 USD</b></p>
          </div>
          <div class="col-md-6">
            <img class="payment-logo" src="/images/icon/skrill.svg" />
            <h3 class="text-red pt-3">Recipient Account</h3>
            <p><span class="list-item">Account Email:</span><b>leohuynh.huynhgia@gmail.com</b></p>
            <p><span class="list-item">Account ID:</span><b>19216811</b></p>
            <p><span class="list-item">Account Holder:</span><b>huynhkhaihung</b></p>
          </div>
          <div class="col-md-12">
            <p class="text-center font-weight-bold mt-5 mb-0">Kindly submit Transaction Number after you do payment
              successfully</p>
            <p class="font-italic text-center"><small>Payment will be auto-confirmed, please make sure Transaction
                Number is correct</small></p>
            <div class="form-group">
              <input type="number" class="form-control input-number" id="" aria-describedby="emailHelp"
                placeholder="Enter transaction number here...">
            </div>
            <div class="text-center btn-wrapper d-block" role="group">
              <button type="button" class="btn text-uppercase">Submit</button>
              <label class="btn text-uppercase btn-upload">
                Upload picture <input type="file" hidden>
              </label>
            </div>
            <p class="text-center">
              <a class="link-dark" href="#">How to get Transaction Number?</a>
            </p>
          </div>
        </div>

      </div>
      <!-- <div class="modal-footer">
      <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      <button type="button" class="btn btn-primary">Save changes</button>
    </div> -->
    </div>
  </div>
</div>
<!-- end modal order detail -->
<?php
$script = <<< JS
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
            ShowSummary(result)
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
  var subtotalKingcoinRow = $("#subtotal-kingcoin-row");
  var subtotalKingcoin = $("#subtotal-kingcoin");
  var bonusKingcoinRow = $("#bonus-kingcoin-row");
  var bonusKingcoin = $("#bonus-kingcoin");
  var totalKingcoinRow = $("#total-kingcoin-row");
  var totalKingcoin = $("#total-kingcoin");
  var subtotalPaymentRow = $("#subtotal-payment-row");
  var subtotalPayment = $("#subtotal-payment");
  var totalPaymentRow = $("#total-payment-row");
  var totalPayment = $("#total-payment");
  var transferFeeRow = $("#transfer-fee-row");
  var transferFee = $("#transfer-fee");
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
})
JS;
$this->registerJs($script);
?>
