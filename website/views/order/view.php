<?php 
use yii\bootstrap\ActiveForm;
use common\components\helpers\StringHelper;
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
      <p><span class="list-item">Total Unit:</span><b class="text-red"><?=sprintf("%s %s", StringHelper::numberFormat($order->total_unit), $order->unit_name);?></b></p>
      <hr />
      <?php if ($order->total_discount) : ?>
      <p><span class="list-item">Discount:</span><b class="text-red"><?=sprintf("(%s) %s", StringHelper::numberFormat($order->total_discount, 2), 'USD');?></b></p>
      <?php endif;?>
      <p><span class="list-item">Transfer fee:</span><b class="text-red"><?=sprintf("%s %s", StringHelper::numberFormat($order->total_fee, 2), 'USD');?></b></p>
      <hr />
      <p><span class="list-item">Final Payment:</span><b class="text-red"><?=sprintf("%s %s", StringHelper::numberFormat($order->total_price, 2), 'USD');?></b></p>
    </div>
    <div class="col-md-6">
      <?=$order->getPaymentData();?>
    </div>
    <?php if ($order->payment_type == 'online') : ?>
    <?php $paymentData = json_decode($order->payment_data, true);?>
    <?php if (in_array($order->payment_method, ['coinspaid'])) : ?>
    <?php $paymentLink = $paymentData['hosted_url'] ? $paymentData['hosted_url'] : '';?>
    <div class="col-md-12">
      <?php if ($paymentLink) : ?>
      <div class="text-center btn-wrapper d-block mt-5" role="group">
        <a type="button" class="btn text-uppercase" style="width: auto" href="<?=$paymentLink;?>" target="_blank">PROCEED WITH PAYMENT</a>
      </div>
      <?php endif;?>
    </div>
    <?php elseif (in_array($order->payment_method, ['binance'])) : ?>
    <?php $paymentLink = $paymentData['qrcodeLink'] ? $paymentData['qrcodeLink'] : '';?>
    <div class="col-md-12">
      <?php if ($paymentLink) : ?>
      <div class="text-center btn-wrapper d-block mt-5" role="group">
        <img src="<?=$paymentLink;?>" alt="PROCEED WITH PAYMENT" width="300" height="300">
      </div>
      <?php endif;?>
    </div>
    <?php elseif (in_array($order->payment_method, ['webmoney'])) : ?>
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
    <?php else : ?>
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
    <?php endif;?>
  </div>
</div>