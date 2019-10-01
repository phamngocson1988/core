<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\bootstrap\ActiveForm;
use unclead\multipleinput\TabularInput;
use unclead\multipleinput\TabularColumn;
use frontend\components\cart\CartItem;
?>
<section class="topup-page">
  <div class="container">
    <div class="small-container">
      <div class="row">
        <div class="col col-lg-12 col-md-12 col-sm-12 col-12">
          <div class="affiliate-top no-mar-bot">
            <div class="has-left-border has-shadow no-mar-top">
              Bulk of "<?=$title;?>" Order
            </div>
          </div>
          
        </div>
      </div>
    </div>
  </div>
</section>
<?php $form = ActiveForm::begin(['id' => 'checkout-form']); ?>
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
              'min' => 0,
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
                      'title' => 'Order detail',
                      'type' => 'textarea', //TabularColumn::TYPE_TEXT_INPUT,
                      // 'headerOptions' => ['width' => '50%'],
                      'columnOptions' => ['style' => 'padding-left:20px; padding-right: 20px;'],
                      'attributeOptions' => [
                          'enableClientValidation' => true,
                          'validateOnChange' => true,
                      ],
                      'value' => function($data) {
                          return $data->raw;
                      },
                      'options' => ['rows' => "3"],
                      'enableError' => true
                  ],
                  [
                    'name'  => 'quantity',
                    'title' => 'Quantity',
                    'type'  => TabularColumn::TYPE_DROPDOWN,
                    'headerOptions' => ['width' => '20%'],
                    'columnOptions' => ['style' => 'padding-left:20px; padding-right: 20px;'],
                    'items' => CartItem::$quantites,
                    'options' => ['class' => 'quantity', 'style' => 'text-align: center; text-align-last: center;'],
                    'value' => function($data) {
                        return $data->quantity;
                    },
                  ],
                  [
                    'name' => 'unit_price',
                    'title' => 'Unit Price',
                    'type'  => TabularColumn::TYPE_STATIC,
                    'headerOptions' => ['width' => '10%', 'class' => 'is-desktop'],
                    // 'value' => function($data) {
                    //   return $data->getPrice();
                    // },
                    'defaultValue' => $default_price,
                    'options' => ['class' => 'price is-desktop'],
                  ],
                  [
                    'name' => 'amount',
                    'title' => 'Amount',
                    'type'  => \unclead\multipleinput\MultipleInputColumn::TYPE_STATIC,
                    'headerOptions' => ['width' => '10%'],
                    // 'value' => function($data) {
                    //     return $data->quantity * $data->unit_price;
                    // },
                    'defaultValue' => $default_price,
                    'options' => ['class' => 'total'],
                  ],
              ],
            ])?>
            <table style="border-right: 0">
              <thead>
                <tr>
                  <td style="border: 0"><strong>Total Amount:</td>
                  <td width="20%" style="border: 0"><span id="total-quantity"></span></td>
                  <td width="10%" style="border: 0"></td>
                  <td width="10%" style="border: 0"><span id="total-price is-desktop"></span></td>
                  <td width="10%" style="border: 0"></td>
                </tr>
              </thead>
            </table>
            <?=Html::submitButton('Check Out', ['id' => 'checkout', 'class' => 'cus-btn yellow fl-right', 'data-price' => $balance, 'onClick' => 'showLoader()']);?>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<div class="modal fade" id="alert" role="dialog">
    <div class="modal-dialog">
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body" style="text-align: center;">Not enough balance. Click to topup <a href="<?=Url::to(['topup/index']);?>" class="normal-link">here!</a></div>
      </div>
    </div>
  </div>
<?php ActiveForm::end(); ?>
<?php
$script = <<< JS
$('body').on('change', ".quantity", function(){
  var parent = $(this).closest('tr');
  var _p = parent.find('.price').html();
  var _q = $(this).val();
  $(this).closest('tr').find('.total').html(_p * _q);
  calculateTotal();
});
$('.quantity').trigger('change');

$('#checkout-form').on('submit', function() {
  var wallet = $('#checkout').data('price');
  var totalPrice = getTotalPrice();
  if (!totalPrice) return;
  if (totalPrice > wallet) {
    // show popup
    $("#alert").modal({backdrop:false});
    hideLoader();
    return false;
  }
  return true;
})

function calculateTotal() {
  $('#total-quantity').html(getTotalQuantity());
  $('#total-price').html(getTotalPrice());
};

function getTotalQuantity() {
  var totalQuantity = 0;
  $('.quantity').each(function(){
    totalQuantity += parseFloat($(this).val());
  });
  return totalQuantity;
}

function getTotalPrice() {
  var totalPrice = 0;
  $('.total').each(function(){
    totalPrice += parseFloat($(this).text());
  });
  return totalPrice;
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
