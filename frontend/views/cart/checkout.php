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
                  <td>(K)<?=$item->getTotalPrice();?></td>
                </tr>
                <?php endforeach;?>
                <tr>
                  <td>Sub total</td>
                  <td>(K)<?=$cart->getSubTotalPrice();?></td>
                </tr>
                <tr>
                  <td>Total</td>
                  <td>(K)<?=$cart->getTotalPrice();?></td>
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
                <tr>
                  <td>Platform</td>
                  <td><?=$item->platform;?></td>
                </tr>
                <tr>
                  <td>Login method</td>
                  <td><?=$item->login_method;?></td>
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
    <?php if ($can_place_order) :?>
    <div class="row">
      <div class="col-12">
        <div class="form-wrap">
          <ul class="radio-group">
            <li>
              <label class="radio-inline">
              <input type="radio" name="radio-group" checked="checked">Pay by King Coin
              </label>
            </li>
          </ul>
        </div>
        <?php $form = ActiveForm::begin(['id' => 'form-signup', 'action' => Url::to(['cart/purchase'])]); ?>
        <?php 
        $items = $cart->getItems();
        $item = reset($items);
        ?>
        <?= Html::a('Go back', Url::to(['cart/index', 'pid' => $item->getUniqueId(), 'qt' => $item->quantity]), ['class' => 'button button-primary button-nina']) ?>
        <?= Html::submitButton('place order', ['class' => 'button button-secondary button-nina', 'onclick' => 'showLoader()']) ?>
        <?php ActiveForm::end();?>
      </div>
    </div>
    <?php else: ?>
    <div class="row">
      <div class="col-12">
        <div class="form-wrap">
          <h5>Your wallet is not enough King Coin</h5>
        </div>
        <?= Html::a('go to topup', Url::to(['pricing/index']), ['class' => 'button button-secondary button-nina']) ?>
      </div>
    </div>
    <?php endif;?>
  </div>
</section>