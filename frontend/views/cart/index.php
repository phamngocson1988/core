<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\bootstrap\ActiveForm;
?>
<?php $form = ActiveForm::begin(['id' => 'update-cart', 'action' => ['cart/update']]); ?>
<?= $form->field($item, 'scenario', [
  'options' => ['tag' => false],
  'template' => '{input}'
])->hiddenInput()->label(false) ?>
<section class="section section-lg bg-default">
  <div class="container container-wide">
    <div class="row row-fix justify-content-lg-center">
      <div class="col-xl-11 col-xxl-8">
        <div class="table-novi table-custom-responsive table-shop-responsive">
          <table class="table-custom table-shop table">
            <thead>
              <tr>
                <th style="width: 30%;">Game</th>
                <th style="width: 30%;">Package</th>
                <th style="width: 10%;">King Coin</th>
                <th style="width: 10%;"><?=ucfirst($item->unit_name);?></th>
                <th style="width: 20%;">Quantity</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>
                  <div class="unit flex-row align-items-center">
                    <div class="unit-left"><a href="<?=Url::to(['game/view', 'id' => $item->id]);?>"><img src="<?=$item->getImageUrl('71x71');?>" alt="" width="54" height="71"/></a></div>
                    <div class="unit-body"><a class="text-gray-darker" style="white-space: normal;" href="javascript:;"><?=$item->getLabel();?></a></div>
                  </div>
                </td>
                <td>
                  <?php 
                  $metaData = [];
                  foreach ($item->products as $product) {
                    $metaData[$product->id] = ['data-price' => $product->price, 'data-unit' => $product->unit];
                  }?>

                  <?= $form->field($item, 'product_id', [
                    'options' => ['tag' => false],
                    'inputOptions' => ['class' => 'form-input select-filter', 'id' => 'products'],
                    'template' => '{input}'
                  ])->dropDownList(ArrayHelper::map($item->products, 'id', 'title'), ['options' => $metaData]) ?>
                </td>
                <td id="price">0</td>
                <td id="unit">0</td>
                <td>
                  <?= $form->field($item, 'quantity', [
                    'options' => ['class' => 'form-wrap box-width-1 shop-input'],
                    'inputOptions' => ['class' => 'form-input input-append',
                      'id' => 'quantity', 
                      'type' => 'number', 
                      'min' => '1', 
                    ],
                    'template' => '{input}'
                  ])->textInput() ?>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="section section-lg bg-default novi-background bg-cover text-center">
  <!-- section wave-->
  <div class="container">
    <div class="row row-fix justify-content-sm-center">
      <div class="col-md-10 col-xl-8">
        <h3>Account Information</h3>
        <div class="row row-fix row-20">
          <div class="col-md-6">
            <?= $form->field($item, 'username', [
              'options' => ['class' => 'form-wrap form-wrap-validation'],
              'inputOptions' => ['class' => 'form-input'],
              'labelOptions' => ['class' => 'form-label-outside'],
              'errorOptions' => ['tag' => 'span', 'class' => 'form-validation'],
              'template' => '{label}{input}{error}'
            ])->textInput() ?>
          </div>
          <div class="col-md-6">
            <?= $form->field($item, 'password', [
              'options' => ['class' => 'form-wrap form-wrap-validation'],
              'inputOptions' => ['class' => 'form-input'],
              'labelOptions' => ['class' => 'form-label-outside'],
              'errorOptions' => ['tag' => 'span', 'class' => 'form-validation'],
              'template' => '{label}{input}{error}'
            ])->textInput() ?>
          </div>
          <div class="col-md-6">
            <?= $form->field($item, 'character_name', [
              'options' => ['class' => 'form-wrap form-wrap-validation'],
              'inputOptions' => ['class' => 'form-input'],
              'labelOptions' => ['class' => 'form-label-outside'],
              'errorOptions' => ['tag' => 'span', 'class' => 'form-validation'],
              'template' => '{label}{input}{error}'
            ])->textInput() ?>
          </div>
          <div class="col-md-6">
            <?= $form->field($item, 'recover_code', [
              'options' => ['class' => 'form-wrap form-wrap-validation'],
              'inputOptions' => ['class' => 'form-input'],
              'labelOptions' => ['class' => 'form-label-outside'],
              'errorOptions' => ['tag' => 'span', 'class' => 'form-validation'],
              'template' => '{label}{input}{error}'
            ])->textInput() ?>
          </div>
          <div class="col-md-6">
            <?= $form->field($item, 'server', [
              'options' => ['class' => 'form-wrap form-wrap-validation'],
              'inputOptions' => ['class' => 'form-input'],
              'labelOptions' => ['class' => 'form-label-outside'],
              'errorOptions' => ['tag' => 'span', 'class' => 'form-validation'],
              'template' => '{label}{input}{error}'
            ])->textInput() ?>
          </div>
          <div class="col-md-6">
            <?= $form->field($item, 'note', [
              'options' => ['class' => 'form-wrap form-wrap-validation'],
              'inputOptions' => ['class' => 'form-input'],
              'labelOptions' => ['class' => 'form-label-outside'],
              'errorOptions' => ['tag' => 'span', 'class' => 'form-validation'],
              'template' => '{label}{input}{error}'
            ])->textInput() ?>
          </div>
          <div class="col-md-6">
            <?= $form->field($item, 'platform', [
              'options' => ['class' => 'form-wrap form-wrap-validation'],
              'inputOptions' => ['class' => 'form-input-outside select-filter'],
              'errorOptions' => ['tag' => 'span', 'class' => 'form-validation'],
              'labelOptions' => ['class' => 'form-label-outside'],
              'template' => '{label}{input}{hint}{error}'
            ])->dropDownList(['android' => 'Android', 'ios' => 'Ios']) ?>
          </div>
          <div class="col-md-6">
            <?= $form->field($item, 'login_method', [
              'options' => ['class' => 'form-wrap form-wrap-validation'],
              'inputOptions' => ['class' => 'form-input-outside select-filter'],
              'errorOptions' => ['tag' => 'span', 'class' => 'form-validation'],
              'labelOptions' => ['class' => 'form-label-outside'],
              'template' => '{label}{input}{hint}{error}'
            ])->dropDownList(['facebook' => 'Facebook', 'google' => 'Google']) ?>
          </div>
          <div class="col-md-12">
            <article class="inline-message">
              <p><strong style="color: red">*** Important Notes:</strong></p>
              <p>For the fastest process, kindly double check the provided details,ensure that: <strong>"Server"</strong>, <strong>"Login method"</strong> & <strong>"Recovery code"</strong> are provided.</p>
              <p>Thanks for your cooperation!</p>
            </article>
          </div>
          <div class="col-lg-12 offset-custom-1">
            <div class="form-button text-md-right">
              <?= Html::submitButton('checkout', ['class' => 'button button-secondary button-nina']) ?>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<?php ActiveForm::end(); ?>

<?php
$script = <<< JS
var complainForm = new AjaxFormSubmit({element: 'form#update-cart'});
complainForm.success = function (data, form) {
  window.location.href = "[:checkout_url]";
}
complainForm.error = function (errors) {
  console.log(errors);
}

$("#products, #quantity").on('change', function(){
  updatePrice();
});

function updatePrice() {
  var price = $("#products").find("option:selected").data('price');
  var unit = $("#products").find("option:selected").data('unit');
  var quantity = $("#quantity").val();
  var totalPrice = price * quantity;
  var totalUnit = unit * quantity;
  $("#price").html('(K) ' + formatMoney(totalPrice, 0));
  $("#unit").html(formatMoney(totalUnit, 0));
}

$("#products").trigger('change');
JS;
$script = str_replace("[:checkout_url]", Url::to(['cart/checkout']), $script);
$this->registerJs($script);
?>
