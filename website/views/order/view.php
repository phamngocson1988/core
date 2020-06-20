<?php 
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
?>
<div class="modal-header d-block">
  <h2 class="modal-title text-center w-100 text-red text-uppercase">Payment game</h2>
  <p class="text-center d-block">Order ID: #<?=$order->id;?></p>
  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
    <span aria-hidden="true">&times;</span>
  </button>
</div>
<div class="modal-body">
  <div class="row">
    <div class="col-md-6 border-right">
      <p><span class="list-item">Game:</span><b><?=$order->game_title;?></b></p>
      <p><span class="list-item">Version:</span><b>Global</b></p>
      <p><span class="list-item">Total Unit:</span><b class="text-red"><?=sprintf("%s %s", number_format($order->total_unit), $order->unit_name);?></b></p>
      <hr />
      <p><span class="list-item">Payment:</span><b class="text-red"><?=sprintf("%s %s", number_format($order->sub_total_price, 1), $order->currency);?></b></p>
      <p><span class="list-item">Transfer fee:</span><b class="text-red"><?=sprintf("%s %s", number_format($order->total_fee, 1), $order->currency);?></b></p>
    </div>
    <div class="col-md-6">
      <?=$paygate->content;?>
    </div>
    <div class="col-md-12">
      <p class="text-center font-weight-bold mt-5 mb-0">Kindly submit Transaction Number after you do payment successfully</p>
      <p class="font-italic text-center"><small>Payment will be auto-confirmed, please make sure Transaction Number is correct</small></p>
      <?php $form = ActiveForm::begin(['action' => Url::to(['order/update', 'id' => $order->id]), 'id' => 'update-payment-form']);?>
      <?= $form->field($model, 'payment_id', [
        'template' => '{input}',
        'inputOptions' => ['class' => 'form-control input-number', 'aria-describedby' => 'emailHelp', 'placeholder' => 'Enter transaction number here...', 'disabled' => $model->payment_id]
      ])->textInput()->label(false) ?>
      <div class="text-center btn-wrapper d-block" role="group">
        <button type="button" id="update-payment-button" class="btn text-uppercase">Submit</button>
        <label class="btn text-uppercase btn-upload">
          Upload picture <input type="file" name="evidence" hidden accept='image/*'>
        </label>
        <?=$form->field($model, 'evidence', [
          'options' => ['tag' => false],          
          'template' => '{input}',
        ])->hiddenInput()->label(false) ?>
      </div>
      <?php ActiveForm::end(); ?>
      <p class="text-center">
        <a class="link-dark" href="google.com" target="_blank">How to get Transaction Number?</a>
      </p>
    </div>
  </div>
</div>