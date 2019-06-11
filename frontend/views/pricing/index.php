<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\bootstrap\ActiveForm;
use yii\widgets\Pjax;
?>
<section class="section-lg text-center bg-default">
  <!-- Style switcher-->
  <div class="style-switcher" data-container="">
    <div class="style-switcher-container">
      <div class="style-switcher-toggle-wrap"> 
      </div>
      <section class="section section-lg bg-default novi-background bg-cover text-center bg-gray-darker">
        <div class="container container-wide">
          <h3>pricing packages</h3>
          <div class="row row-50 justify-content-sm-center">
            <!-- Pricing Box XL-->
            <?php foreach ($items as $item) :?>
            <div class="col-md-6 col-xl-3" class="pricing-package">s
              <div class="pricing-box pricing-box-xl pricing-box-novi">
                <div class="pricing-box-header">
                  <h4><?=$item->getPricing()->title;?></h4>
                </div>
                <?php $form = ActiveForm::begin([
                  'action' => Url::to(['pricing/add']),
                  'options' => ['class' => 'add-to-cart']
                ]); ?>
                <div class="pricing-box-price">
                  <div class="heading-2"><sup>$</sup><span id="price-<?=$item->getPricing()->id;?>"><?=number_format($item->getPricing()->amount);?></span></div>
                </div>
                <?= $form->field($item, 'pricing_id', ['template' => '{input}'])->hiddenInput() ?>
                <?= Html::submitButton('Buy now', ['class' => 'button button-sm button-secondary button-nina', 'onclick' => 'showLoader()']) ?>
                <div class="pricing-box-body">
                  <ul class="pricing-box-list">
                    <li>
                      <div class="unit unit-spacing-sm flex-row align-items-center">
                        <div class="unit-left"><span class="icon novi-icon icon-md-big icon-primary mdi mdi-database"></span></div>
                        <div class="unit-body"><span id="coin-<?=$item->getPricing()->id;?>"><?=number_format($item->getPricing()->num_of_coin);?></span> King Coins</div>
                      </div>
                      <div class="unit unit-spacing-sm flex-row align-items-center">
                        <div class="unit-left"><span class="icon novi-icon icon-md-big icon-primary mdi mdi-cart-outline"></span></div>
                        <div class="unit-body">
                          <?= $form->field($item, 'quantity', [
                          'options' => ['class' => 'form-wrap box-width-1 shop-input'],
                          'inputOptions' => ['class' => 'form-input input-append', 'type' => 'number', 'min' => 1, 'value' => 1, 'id' => 'quantity'],
                          'template' => '{input}'
                        ])->textInput() ?>
                        </div>
                      </div>
                    </li>
                  </ul>
                </div>
                <?php ActiveForm::end();?>
              </div>
            </div>
            <?php endforeach;?>
          </div>
        </div>
      </section>
    </div>
  </div>
</section>
<?php
$script = <<< JS
var complainForm = new AjaxFormSubmit({element: 'form.add-to-cart'});
complainForm.success = function (data, form) {
  window.location.href = "[:checkout_url]";
}

$(".quantity-control").on("change", function(){
  var id = $(this).data("id");
  var price = $(this).data("price");
  var coin = $(this).data("coin");
  var qt = $(this).val();
  $(this).closest("form").find("#price-" + id).html(formatMoney(price * qt, 0));
  $(this).closest("form").find("#coin-" + id).html(formatMoney(coin * qt, 0));
});
JS;
$script = str_replace("[:checkout_url]", Url::to(['pricing/confirm']), $script);
$this->registerJs($script);
?>
