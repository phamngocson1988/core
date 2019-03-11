<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
$cart = Yii::$app->cart;
?>
<section class="section section-lg bg-default novi-background bg-cover">
  <!-- section wave-->
  <div class="container grid-demonstration">
    <h3 class="text-center">Confirmation</h3>
    <div class="row">
      <div class="col-6">
        <div class="box-classic box-bordered box-novi">
          <div class="box-classic-content">
            <table class="table-custom table-hover">
              <tbody>
                <?php foreach ($cart->getItems() as $item) :?>
                <tr>
                  <td>
                    <?=$item->getLabel();?><br/>
                    <small>Quantity <?=$item->quantity;?></small>
                    <small><?=$item->getUnitName();?> <?=number_format($item->getTotalUnitGame());?></small>
                  </td>
                  <td>$<?=$item->getTotalPrice();?></td>
                </tr>
                <?php endforeach;?>
                <tr>
                  <td>Sub total</td>
                  <td>$<?=$cart->getSubTotalPrice();?></td>
                </tr>
                <tr>
                  <td>Tax/Fee</td>
                  <td>$<?=$cart->getTotalFee();?></td>
                </tr>
                <tr>
                  <td>Total</td>
                  <td>$<?=$cart->getTotalPrice();?></td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
      <div class="col-6">
         <div class="box-classic box-bordered box-novi">
          <div class="box-classic-content">
            <table class="table-custom table-hover">
              <tbody>
                <?php foreach ($cart->getItems() as $item) :?>
                <tr>
                  <td>Username</td>
                  <td><?=$item->username;?></td>
                </tr>
                <tr>
                  <td>Password</td>
                  <td><?=$item->password;?></td>
                </tr>
                <tr>
                  <td>Character name</td>
                  <td><?=$item->character_name;?></td>
                </tr>
                <?php if ($item->recover_code):?>
                <tr>
                  <td>Recover Code</td>
                  <td><?=$item->recover_code;?></td>
                </tr>
                <?php endif;?>
                <?php if ($item->server):?>
                <tr>
                  <td>Server</td>
                  <td><?=$item->server;?></td>
                </tr>
                <?php endif;?>
                <?php if ($item->note):?>
                <tr>
                  <td>Note</td>
                  <td><?=$item->note;?></td>
                </tr>
                <?php endif;?>
                <?php endforeach;?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-12">
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
          <?= Html::submitButton('place order', ['class' => 'button button-secondary button-nina', 'onclick' => 'showLoader()']) ?>
          <?php ActiveForm::end();?>
      </div>
    </div>
  </div>
</section>