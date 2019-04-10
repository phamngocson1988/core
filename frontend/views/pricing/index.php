<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\bootstrap\ActiveForm;
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
            <?php foreach ($models as $model) :?>
            <div class="col-md-6 col-xl-3" class="pricing-package">
              <div class="pricing-box pricing-box-xl pricing-box-novi">
                <div class="pricing-box-header">
                  <h4><?=$model->title;?></h4>
                </div>
                <?php $form = ActiveForm::begin([
                  'action' => Url::to(['pricing/add']),
                  'options' => ['class' => 'add-to-cart']
                ]); ?>
                <div class="pricing-box-price">
                  <div class="heading-2"><sup>$</sup><span id="price-<?=$model->id;?>"><?=number_format($model->amount);?></span></div>
                </div>
                <?= $form->field($model, 'id', ['template' => '{input}', 'inputOptions' => ['name' => 'id']])->hiddenInput() ?>
                <?= Html::submitButton('Buy now', ['class' => 'button button-sm button-secondary button-nina', 'onclick' => 'showLoader()']) ?>
                <div class="pricing-box-body">
                  <ul class="pricing-box-list">
                    <li>
                      <div class="unit unit-spacing-sm flex-row align-items-center">
                        <div class="unit-left"><span class="icon novi-icon icon-md-big icon-primary mdi mdi-database"></span></div>
                        <div class="unit-body"><span id="coin-<?=$model->id;?>"><?=number_format($model->num_of_coin);?></span> King Coins</div>
                      </div>
                      <div class="unit unit-spacing-sm flex-row align-items-center">
                        <div class="unit-left"><span class="icon novi-icon icon-md-big icon-primary mdi mdi-cart-outline"></span></div>
                        <div class="unit-body">
                          <div class="form-wrap box-width-1 shop-input">
                            <input class="form-input input-append quantity-control" data-id="<?=$model->id;?>" data-price="<?=$model->amount;?>" data-coin="<?=$model->num_of_coin;?>" type="number" min="1" max="300" value="1" name="qt">
                          </div>
                        </div>
                      </div>
                      <div class="unit unit-spacing-sm flex-row align-items-center">
                        <div class="rd-mailform rd-mailform-inline rd-mailform-sm rd-mailform-inline-modern">
                          <div class="rd-mailform-inline-inner">
                            <?= $form->field($discount, 'code', [
                              'options' => ['class' => 'form-wrap'],
                              'inputOptions' => ['class' => 'form-input voucher'],
                              'labelOptions' => ['class' => 'form-label'],
                              'errorOptions' => ['tag' => 'span', 'class' => 'form-validation'],
                              'template' => '{input}{error}{label}'
                            ])->textInput()->label('voucher'); ?>
                            <button class="button form-button button-sm button-secondary button-nina apply_voucher">Apply</button>
                          </div>
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
