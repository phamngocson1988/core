<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\bootstrap\ActiveForm;
use unclead\multipleinput\MultipleInput;
use unclead\multipleinput\TabularInput;
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
          <?= TabularInput::widget([
          'models' => $models,
          'cloneButton' => true,
          'columns' => [
            [
              'name'  => 'raw',
              'title' => 'Raw account data',
              'type'  => \unclead\multipleinput\MultipleInputColumn::TYPE_TEXT_INPUT,
            ],
            [
              'name'  => 'quantity',
              'title' => 'Quantity',
              'type'  => \unclead\multipleinput\MultipleInputColumn::TYPE_DROPDOWN,
              'defaultValue' => 1,
              'items' => CartItem::$quantites,
              'options' => ['class' => 'quantity']
            ],
            [
              'title' => 'Price',
              'type'  => \unclead\multipleinput\MultipleInputColumn::TYPE_STATIC,
              'value' => function($data) {
                  return $data->getPrice();
              },
              'options' => ['class' => 'price'],
            ],
            [
              'title' => 'Total Price',
              'type'  => \unclead\multipleinput\MultipleInputColumn::TYPE_STATIC,
              'value' => function($data) {
                  return $data->getTotalPrice();
              },
              'options' => ['class' => 'total'],
            ],
          ],
          ]); ?>
        </div>
        <input type="submit" value="Send"/>
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
});
JS;
$this->registerJs($script);
?>
