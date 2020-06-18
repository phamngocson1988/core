<?php 
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
?>
<div class="modal-header d-block">
  <h2 class="modal-title text-center w-100 text-red text-uppercase">Payment game</h2>
  <p class="text-center d-block">Order ID: #12345678</p>
  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
    <span aria-hidden="true">&times;</span>
  </button>
</div>
<div class="modal-body">
  <div class="row">
    <div class="col-md-6 border-right">
      <p><span class="list-item">Game:</span><b>State of Survival - Discard (Pack Offer)</b></p>
      <p><span class="list-item">Version:</span><b>Global</b></p>
      <p><span class="list-item">Total Unit:</span><b class="text-red">100 GEMS</b></p>
      <hr />
      <p><span class="list-item">Payment:</span><b class="text-red">100 USD</b></p>
      <p><span class="list-item">Transfer fee:</span><b class="text-red">1 USD</b></p>
    </div>
    <div class="col-md-6">
      <img class="payment-logo" src="./images/icon/skrill.svg"/>
      <h3 class="text-red pt-3">Recipient Account</h3>
      <p><span class="list-item">Account Email:</span><b>leohuynh.huynhgia@gmail.com</b></p>
      <p><span class="list-item">Account ID:</span><b>19216811</b></p>
      <p><span class="list-item">Account Holder:</span><b>huynhkhaihung</b></p>
    </div>
    <div class="col-md-12">
      <p class="text-center font-weight-bold mt-5 mb-0">Kindly submit Transaction Number after you do payment successfully</p>
      <p class="font-italic text-center"><small>Payment will be auto-confirmed, please make sure Transaction Number is correct</small></p>
      <div class="form-group">
        <input type="number" class="form-control input-number" id="" aria-describedby="emailHelp" placeholder="Enter transaction number here...">
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