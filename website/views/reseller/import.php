<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\bootstrap\ActiveForm;
?>
<?php $form = ActiveForm::begin([
    'action' => ['reseller/import', 'id' => $id],
    'options' => ['enctype' => 'multipart/form-data', 'id' => 'upload-form']
]); ?>
<section class="topup-page">
  <div class="container">
    <div class="small-container">
      <div class="row">
        <div class="col col-lg-12 col-md-12 col-sm-12 col-12">
          <div class="affiliate-top no-mar-bot">
            <div class="has-left-border has-shadow no-mar-top">
              Import your list
              <?=Html::fileInput("template", null, ['id' => 'file_upload', 'style' => 'display:none']);?>
              <div class="top-bar-action">
                <?=Html::a('Upload', 'javascript:;', ['class' => 'upload-btn', 'id' => 'upload-button']);?>
                <?=Html::a('Download', Url::to(['reseller/download']), ['class' => 'download-btn']);?>
              </div>
            </div>
          </div>
          
        </div>
      </div>
    </div>
  </div>
</section>

<?php ActiveForm::end();?>
<?php $form = ActiveForm::begin(['action' => ['reseller/purchase', 'id' => $id]]); ?>
<section class="topup-page">
  <div class="container">
    <div class="small-container">
      <div class="row">
        <div class="col col-lg-6 col-md-6 col-sm-12 col-12">
          <div class="affiliate-top no-mar-bot">
            <div class="has-left-border has-shadow no-mar-top">
              VALID
            </div>
          </div>
          <div class="cart-table">
            <?php if (!$valid_records) :?>
            <table>
              <thead>
                <tr>
                  <th>No</th>
                  <th>Quantity</th>
                  <th>Coin</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td colspan="3">No records</td>
                </tr>
              </tbody>
            </table>
            <?php else :?>
            <table>
              <thead>
                <tr>
                  <th>No</th>
                  <th>Quantity</th>
                  <th>Coin</th>
                </tr>
              </thead>
              <tbody>
                <?php $totalPrice = 0;?>
                <?php foreach ($valid_records as $no => $record) :?>
                <tr>
                  <td>
                    <?=$record->no;?>
                    <?=Html::hiddenInput("import[$no][quantity]", $record->quantity);?>
                    <?=Html::hiddenInput("import[$no][username]", $record->username);?>
                    <?=Html::hiddenInput("import[$no][password]", $record->password);?>
                    <?=Html::hiddenInput("import[$no][character_name]", $record->character_name);?>
                    <?=Html::hiddenInput("import[$no][recover_code]", $record->recover_code);?>
                    <?=Html::hiddenInput("import[$no][server]", $record->server);?>
                    <?=Html::hiddenInput("import[$no][note]", $record->note);?>
                    <?=Html::hiddenInput("import[$no][login_method]", $record->login_method);?>
                    <?=Html::hiddenInput("import[$no][platform]", $record->platform);?>
                    <?php $totalPrice += $record->getTotalPrice();?>
                  </td>
                  <td><?=number_format($record->quantity);?></td>
                  <td><?=number_format($record->getTotalPrice());?></td>
                </tr>
                <?php endforeach; ?>
              </tbody>
              <thead>
                <tr>
                  <th colspan="2"></th>
                  <th><?=number_format($totalPrice);?></th>
                </tr>
              </thead>
            </table>
            <div class="cart-coupon">
              <?php $user = Yii::$app->user->getIdentity();?>
              <?php $balance = $user->getWalletAmount(); ?>
              <?php if ($totalPrice > $balance) : ?>
              <?=Html::a("Your balance now is " . number_format($balance) . ". Topup your wallet", Url::to(['topup/index']), ['class' => 'cus-btn yellow fl-right']);?>
              <?php else : ?>
              <?=Html::submitButton('Check Out', ['class' => 'cus-btn yellow fl-right']);?>
              <?php endif;?>
            </div>
            <?php endif;?>
          </div>
        </div>
        <div class="col col-lg-6 col-md-6 col-sm-12 col-12">
          <div class="affiliate-top no-mar-bot">
            <div class="has-left-border has-shadow no-mar-top">
              INVALID
            </div>
          </div>
          <div class="cart-table">
            <table>
              <tr>
                  <th>No</th>
                  <th>Error</th>
              </tr>
              <?php if (!$invalid_records) :?>
              <tr>
                <td colspan="2">No records</td>
              </tr>
              <?php else : ?>
              <?php foreach ($invalid_records as $record) :?>
              <tr>
                <td><?=$record->no;?></td>
                <td><?php 
                $errors = $record->getErrorSummary(true);
                echo $errors[0];
                ?></td>
              </tr>
              <?php endforeach; ?>
              <?php endif;?>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<?php ActiveForm::end(); ?>
<?php
$script = <<< JS
$('#upload-form').on('submit', function() {
  if (!document.getElementById("file_upload").files.length) return false;
});
$('#file_upload').on('change', function() {
  $(this).closest('form').submit();
});
$('#upload-button').on('click', function() {
  $('#file_upload').trigger('click');
});
JS;
$this->registerJs($script);
?>
