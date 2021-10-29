<?php
use yii\helpers\Html;
use yii\helpers\Url;
?>
<div class="container my-5 my-wallet">
  <div class="row">
    <div class="col-md-12 mb-3">
      <h1 class="text-uppercase text-red text-center"><?=$user->getName();?></h1>
    </div>
    <div class="col-md-8 offset-md-2">
      <div class="row">
        <div class="col-md-12 mb-4">
          <div class="card-balance row">
            <div class="col-md-6 p-4 bg-blue">
              <p class="mb-2">Your name</p>
              <div class="input-group inp-deposit">
                <input type="text" step="100" id="customer_name" class="form-control" placeholder="Type your name">
              </div>
            </div>
            <div class="col-md-6 p-4 bg-green">
              <p class="mb-2">Amount (USD)</p>
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
              <img class="icon" src="<?=$paygate->getImageUrl(null, '/images/noimage.png');?>" />
            </label>
            <?php endforeach;?>
          </div>
        </div>
        <div class="col-md-6">
          <!-- CART SUMMARY -->
          <p class="lead mb-2">Billing infomation</p>
          <div class="card card-summary">
            <div class="card-body" id="subtotal-kingcoin-row">
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
              <button id="payNowButton" class="mt-3 btn btn-block btn-payment">Pay now</button>
            </div>
          </div>
          <!-- END SUMMARY -->
        </div>
      </div>
    </div>
  </div>

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
  var paygateElement = $( "input[name=identifier]:checked" );
  // Calculate
  var quantity = quantityElement.val();
  var paygate = paygateElement.val();

  $.ajax({
      url: '/wallet/calculate.html',
      type: "POST",
      dataType: 'json',
      data: {
        quantity: quantity,
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
  var nameElement = $('#customer_name');
  var paygateElement = $( "input[name=identifier]:checked" );
  // Calculate
  var quantity = quantityElement.val();
  var paygate = paygateElement.val();
  var name = nameElement.val();
  showLoader();
  $.ajax({
      url: '/wallet/purchase.html',
      type: "POST",
      dataType: 'json',
      data: {
        quantity: quantity,
        paygate: paygate,
        name: name
      },
      success: function (result, textStatus, jqXHR) {
          console.log('Purchase', result);
          if (result.status) {
            ShowTransaction(result.data)
          } else {
            toastr.error(result.errors.join('<br/>')); 
          }
      },
      complete: function(data) {
        hideLoader();
      }
  });
};

function ShowSummary(data) {
  // Elements
  var quantityElement = $('#quantity');
  var paygateElement = $( "input[name=identifier]:checked" );
  // Html
  var subTotalPaymentRow = $("#subtotal-payment-row");
  var subTotalPayment = $("#subtotal-payment");
  var totalPaymentRow = $("#total-payment-row");
  var totalPayment = $("#total-payment");
  var transferFeeRow = $("#transfer-fee-row");
  var transferFee = $("#transfer-fee");

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
  $(this).val(Math.floor($(this).val()));
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
          setTimeout(() => {  
              location.reload();
          }, 2000);
          toastr.success(result.message);
        }
      },
  });
});

JS;
$this->registerJs($script);
?>
