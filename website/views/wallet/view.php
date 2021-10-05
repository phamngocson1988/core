<?php 
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use common\components\helpers\StringHelper;
?>
<div class="modal-header d-block">
  <h2 class="modal-title text-center w-100 text-red text-uppercase">Payment Kcoin</h2>
  <p class="text-center d-block">Transaction ID: <?=$payment->getId();?></p>
  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
    <span aria-hidden="true">&times;</span>
  </button>
</div>
<div class="modal-body">
  <div class="row">
    <div class="col-md-6 border-right">
      <p><span class="list-item">Kcoin:</span><b><?=StringHelper::numberFormat($payment->coin, 2);?> KC</b></p>
      <p><span class="list-item">Bonus:</span><b><?=StringHelper::numberFormat($payment->promotion_coin, 2);?> KC</b></p>
      <p><span class="list-item">Total KC:</span><b class="text-red"><?=StringHelper::numberFormat($payment->total_coin, 2);?> KC</b></p>
      <hr />
      <p><span class="list-item">Subtotal:</span><b class="text-red"><?=StringHelper::numberFormat($payment->price, 2);?> USD</b></p>
      <p><span class="list-item">Transfer fee:</span><b class="text-red"><?=StringHelper::numberFormat($payment->total_fee, 2);?> USD</b></p>
      <p><span class="list-item">Total payment:</span><b class="text-red"><?=StringHelper::numberFormat($payment->total_price, 2);?> USD</b></p>
    </div>
    <div class="col-md-6">
      <?=$payment->getPaymentData();?>
    </div>
    <?php if ($payment->payment_type == 'online') : ?>
    <?php $paymentData = json_decode($payment->payment_data, true);?>
    <?php if (in_array($payment->payment_method, ['coinspaid'])) : ?>
    <?php $paymentLink = $paymentData['hosted_url'] ? $paymentData['hosted_url'] : '';?>
    <div class="col-md-12">
      <?php if ($paymentLink) : ?>
      <div class="text-center btn-wrapper d-block mt-5" role="group">
        <a type="button" class="btn text-uppercase" style="width: auto" href="<?=$paymentLink;?>" target="_blank">PROCEED WITH PAYMENT</button>
      </div>
      <?php endif;?>
    </div>
    <?php elseif (in_array($payment->payment_method, ['webmoney'])) : ?>
    <div class="col-md-12">
      <?php $paymentLink = $paymentData['paygate_url'];?>
      <?php unset($paymentData['paygate_url']);?>
      <div class="text-center btn-wrapper d-block mt-5" role="group">
      <form method="POST" action="<?=$paymentLink;?>" accept-charset="utf-8">
        <?php foreach ($paymentData as $paymentKey => $paymentValue) : ?>
        <input type="hidden" name="<?=$paymentKey;?>" value="<?=$paymentValue;?>"/>
        <?php endforeach;?>
        <input type="submit" class="btn text-uppercase" style="width: auto" value="PROCEED WITH PAYMENT" />
      </form>
      </div>
    </div>
    <?php endif;?>
    <?php else: ?>
    <div class="col-md-12">
      <p class="text-center font-weight-bold mt-5 mb-0">Kindly submit Transaction Number after you do payment
        successfully</p>
      <p class="font-italic text-center"><small>Payment will be auto-confirmed, please make sure Transaction
          Number is correct</small></p>
      <?php $form = ActiveForm::begin(['action' => Url::to(['wallet/update', 'id' => $payment->id]), 'id' => 'update-payment-form']);?>
      <?= $form->field($model, 'payment_id', [
        'template' => '{input}',
        'inputOptions' => ['class' => 'form-control input-number', 'aria-describedby' => 'emailHelp', 'placeholder' => 'Enter transaction number here...', 'disabled' => (boolean)$model->payment_id]
      ])->textInput()->label(false) ?>

      <div class="text-center btn-wrapper d-block" role="group">
        <button type="button" class="btn text-uppercase" id="update-payment-button">Submit</button>
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
    <?php endif;?>
  </div>
</div>