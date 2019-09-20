<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\bootstrap\ActiveForm;
use unclead\multipleinput\TabularInput;
use unclead\multipleinput\TabularColumn;
use frontend\components\cart\CartItem;
use yii\widgets\Pjax;
?>
<section class="topup-page">
  <div class="container">
    <div class="small-container">
      <div class="row">
        <div class="col col-lg-12 col-md-12 col-sm-12 col-12">
          <div class="affiliate-top no-mar-bot">
            <div class="has-left-border has-shadow no-mar-top">
              Import your list
            </div>
          </div>
          
        </div>
      </div>
    </div>
  </div>
</section>
<?php Pjax::begin(); ?>
<?php $form = ActiveForm::begin(); ?>
<section class="topup-page">
  <div class="container">
    <div class="small-container">
      <div class="row">
        <div class="col col-lg-12 col-md-12 col-sm-12 col-12">
          <div class="cart-table">
            <?= TabularInput::widget([
              'id' => 'bulk',
              'models' => $models,
              'modelClass' => CartItem::class,
              'min' => 1,
              'iconSource' => TabularInput::ICONS_SOURCE_FONTAWESOME,
              'addButtonPosition' => [
                  TabularInput::POS_HEADER,
                  TabularInput::POS_ROW
              ],
              'layoutConfig' => [
                  'offsetClass'   => 'col-sm-offset-4',
                  'labelClass'    => 'col-sm-2',
                  'wrapperClass'  => 'col-sm-10',
                  'errorClass'    => 'col-sm-4'
              ],
              'attributeOptions' => [
                  'enableAjaxValidation'   => true,
                  'enableClientValidation' => false,
                  'validateOnChange'       => false,
                  'validateOnSubmit'       => true,
                  'validateOnBlur'         => false,
              ],
              'form' => $form,
              'columns' => [
                  [
                      'name' => 'raw',
                      'title' => 'Raw',
                      'type' => TabularColumn::TYPE_TEXT_INPUT,
                      'headerOptions' => ['width' => '50%'],
                      'columnOptions' => ['style' => 'padding-left:20px; padding-right: 20px;'],
                      'attributeOptions' => [
                          'enableClientValidation' => true,
                          'validateOnChange' => true,
                      ],
                      'value' => function($data) {
                          return $data->raw;
                      },
                      'enableError' => true
                  ],
                  [
                    'name'  => 'quantity',
                    'title' => 'Quantity',
                    'type'  => TabularColumn::TYPE_DROPDOWN,
                    'headerOptions' => ['width' => '20%'],
                    'columnOptions' => ['style' => 'padding-left:20px; padding-right: 20px;'],
                    'items' => CartItem::$quantites,
                    'options' => ['class' => 'quantity'],
                    'value' => function($data) {
                        return $data->quantity;
                    },
                  ],
                  [
                    'name' => 'price1',
                    'title' => 'Price',
                    'type'  => TabularColumn::TYPE_STATIC,
                    'headerOptions' => ['width' => '10%'],
                    'value' => function($data) {
                      return $data->getPrice();
                    },
                    'options' => ['class' => 'price'],
                  ],
                  [
                    'name' => 'total',
                    'title' => 'Total Price',
                    'type'  => \unclead\multipleinput\MultipleInputColumn::TYPE_STATIC,
                    'headerOptions' => ['width' => '10%'],
                    'value' => function($data) {
                        return $data->getTotalPrice();
                    },
                    'options' => ['class' => 'total'],
                  ],
              ],
            ])?>
            <table style="border-right: 0">
              <thead>
                <tr>
                  <td width="50%" style="border: 0"><strong>Total:</td>
                  <td width="20%" style="border: 0"><span id="total-quantity"></span></td>
                  <td width="10%" style="border: 0"></td>
                  <td width="10%" style="border: 0"><span id="total-price"></span></td>
                  <td width="10%" style="border: 0"></td>
                </tr>
              </thead>
            </table>
            <div class="cart-coupon" style="text-align: center;">
              <?=Html::submitButton('Check Out', ['class' => 'cus-btn yellow fl-right', 'id' => 'submit', 'data-price' => $balance, 'onClick' => 'showLoader()']);?>
              <a href="<?=Url::to(['topup/index']);?>" id="topup" class="cus-btn yellow fl-right">Balance - <?=number_format($balance);?> Kcoins. Need to topup? Click here</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<?php ActiveForm::end(); ?>
<?php Pjax::end(); ?>
<?php
$script = <<< JS
$('body').on('change', ".quantity", function(){
  var parent = $(this).closest('tr');
  var _p = parent.find('.price').html();
  var _q = $(this).val();
  $(this).closest('tr').find('.total').html(_p * _q);
  calculateTotal();
});

function calculateTotal() {
  var totalQuantity = 0;
  $('.quantity').each(function(){
    totalQuantity += parseFloat($(this).val());
  });
  $('#total-quantity').html(totalQuantity);

  var totalPrice = 0;
  $('.total').each(function(){
    totalPrice += parseFloat($(this).text());
  });
  $('#total-price').html(totalPrice);
  var wallet = $('#submit').data('price');
  toggleCheckout(wallet >= totalPrice);
};

function toggleCheckout(flag) {
  if (!flag) {
    $('#submit').hide();
    $('#topup').show();
  } 
  else {
    $('#submit').show();
    $('#topup').hide();
  }
}

jQuery('#bulk').on('afterInit', function(){
  console.log('calls on after initialization event');
  calculateTotal();
}).on('afterAddRow', function(e, row, currentIndex) {
  console.log('calls on after add row event');
  calculateTotal();
}).on('afterDeleteRow', function(e, row, currentIndex){
  console.log('calls on after remove row event');
  calculateTotal();
});
JS;
$this->registerJs($script);
?>
