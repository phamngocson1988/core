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
          <h3>pricing</h3>
          <div class="row row-50 justify-content-sm-center">
            <!-- Pricing Box XL-->
            <?php foreach ($models as $model) :?>
            <div class="col-md-6 col-xl-3">
              <div class="pricing-box pricing-box-xl pricing-box-novi">
                <div class="pricing-box-header">
                  <h4><?=$model->title;?></h4>
                </div>
                <div class="pricing-box-price">
                  <div class="heading-2"><sup>$</sup><?=number_format($model->amount);?></div>
                </div>
                <?php $form = ActiveForm::begin(['id' => 'form-signup', 'class' => 'rd-mailform form-fix', 'action' => Url::to(['pricing/purchase'])]); ?>
                <?= $form->field($model, 'id', ['template' => '{input}', 'inputOptions' => ['name' => 'id']])->hiddenInput() ?>
                <?= Html::submitButton('Pay by Paypal', ['class' => 'button button-sm button-secondary button-nina', 'onclick' => 'showLoader()']) ?>
                <?php ActiveForm::end();?>
                <div class="pricing-box-body">
                  <ul class="pricing-box-list">
                    <li>
                      <div class="unit unit-spacing-sm flex-row align-items-center">
                        <div class="unit-left"><span class="icon novi-icon icon-md-big icon-primary mdi mdi-database"></span></div>
                        <div class="unit-body"><span><?=number_format($model->num_of_coin);?> King Coins</span></div>
                      </div>
                    </li>
                  </ul>
                </div>
              </div>
            </div>
            <?php endforeach;?>
          </div>
        </div>
      </section>
    </div>
  </div>
</section>