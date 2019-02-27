<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
$cart = Yii::$app->cart;
?>
<section class="section section-lg bg-default novi-background bg-cover text-center">
  <!-- section wave-->
  <div class="container">
    <div class="row row-fix justify-content-sm-center">
      <div class="col-md-10 col-xl-8">
        <h3>Payment</h3>
        <div class="row row-fix row-20">
          <div class="table-checkout text-left">
            <div class="table-novi table-custom-responsive">
              <table class="table-custom">
                <tbody>
                  <?php foreach ($cart->getItems() as $item) :?>
                  <tr>
                    <td><?=$item->getLabel();?></td>
                    <td>$<?=$item->getTotalPrice();?></td>
                  </tr>
                  <?php endforeach;?>
                  <tr>
                    <td>Total</td>
                    <td>$<?=Yii::$app->cart->getTotalPrice();?></td>
                  </tr>
                </tbody>
              </table>
            </div>
            <div class="form-wrap">
              <ul class="radio-group">
                <li>
                  <label class="radio-inline">
                    <input type="radio" name="radio-group" checked="checked">Paypal
                  </label>
                </li>
              </ul>
            </div>
            <?php $form = ActiveForm::begin(['id' => 'form-signup', 'class' => 'rd-mailform form-fix', 'action' => Url::to(['cart/purchase'])]); ?>
            <?= Html::submitButton('place order', ['class' => 'button button-secondary button-nina']) ?>
            <?php ActiveForm::end();?>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>