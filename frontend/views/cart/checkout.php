<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
?>
<section class="section section-lg bg-default">
  <!-- section wave-->
  <div class="container container-wide">
    <div class="row row-fix row-50 justify-content-lg-center">
      <div class="col-lg-11 col-xl-10 col-xxl-6">
        <div class="tabs-custom tabs-horizontal tabs-line text-center" id="tabs-1">
          <!-- Nav tabs-->
          <ul class="nav nav-tabs nav-tabs-checkout">
            <li class="nav-item"><a class="nav-link active" href="#tabs-1-1" data-toggle="tab">Account</a></li>
            <li class="nav-item"><a class="nav-link" href="#tabs-1-2" data-toggle="tab">Payment</a></li>
          </ul>
          <!-- Tab panes-->
          <div class="tab-content">
            <div class="tab-pane fade show active" id="tabs-1-1">
              <?php $form = ActiveForm::begin(['id' => 'form-checkout', 'class' => 'rd-mailform text-left form-fix']); ?>
                <div class="row row-20 row-fix">
                  <div class="col-md-6">
                    <?= $form->field($model, 'username', [
                      'options' => ['class' => 'form-wrap form-wrap-validation'],
                      'inputOptions' => ['class' => 'form-input'],
                      'labelOptions' => ['class' => 'form-label-outside'],
                      'errorOptions' => ['tag' => 'span', 'class' => 'form-validation'],
                      'template' => '{label}{input}{error}'
                    ])->textInput(['autofocus' => true]) ?>
                  </div>
                  <div class="col-md-6">
                    <?= $form->field($model, 'password', [
                      'options' => ['class' => 'form-wrap form-wrap-validation'],
                      'inputOptions' => ['class' => 'form-input'],
                      'labelOptions' => ['class' => 'form-label-outside'],
                      'errorOptions' => ['tag' => 'span', 'class' => 'form-validation'],
                      'template' => '{label}{input}{error}'
                    ])->textInput() ?>
                  </div>
                  <div class="col-md-6">
                    <?= $form->field($model, 'character_name', [
                      'options' => ['class' => 'form-wrap form-wrap-validation'],
                      'inputOptions' => ['class' => 'form-input'],
                      'labelOptions' => ['class' => 'form-label-outside'],
                      'errorOptions' => ['tag' => 'span', 'class' => 'form-validation'],
                      'template' => '{label}{input}{error}'
                    ])->textInput() ?>
                  </div>
                  <div class="col-md-6">
                    <?= $form->field($model, 'recover_code', [
                      'options' => ['class' => 'form-wrap form-wrap-validation'],
                      'inputOptions' => ['class' => 'form-input'],
                      'labelOptions' => ['class' => 'form-label-outside'],
                      'errorOptions' => ['tag' => 'span', 'class' => 'form-validation'],
                      'template' => '{label}{input}{error}'
                    ])->textInput() ?>
                  </div>
                  <div class="col-md-6">
                    <?= $form->field($model, 'server', [
                      'options' => ['class' => 'form-wrap form-wrap-validation'],
                      'inputOptions' => ['class' => 'form-input'],
                      'labelOptions' => ['class' => 'form-label-outside'],
                      'errorOptions' => ['tag' => 'span', 'class' => 'form-validation'],
                      'template' => '{label}{input}{error}'
                    ])->textInput() ?>
                  </div>
                  <div class="col-md-6">
                    <?= $form->field($model, 'note', [
                      'options' => ['class' => 'form-wrap form-wrap-validation'],
                      'inputOptions' => ['class' => 'form-input'],
                      'labelOptions' => ['class' => 'form-label-outside'],
                      'errorOptions' => ['tag' => 'span', 'class' => 'form-validation'],
                      'template' => '{label}{input}{error}'
                    ])->textInput() ?>
                  </div>
                  <div class="col-lg-12 offset-custom-1">
                    <div class="form-button text-md-right">
                      <?= Html::submitButton('checkout', ['class' => 'button button-secondary button-nina', 'name' => 'checkout']) ?>
                    </div>
                  </div>
                </div>
              <?php ActiveForm::end(); ?>
            </div>
            <div class="tab-pane fade" id="tabs-1-2">
              <div class="table-checkout text-left">
                <div class="table-novi table-custom-responsive">
                  <table class="table-custom">
                    <tbody>
                      <tr>
                        <td>Cart Subtotal</td>
                        <td>$58.00</td>
                      </tr>
                      <tr>
                        <td>Total</td>
                        <td>$58.00</td>
                      </tr>
                    </tbody>
                  </table>
                </div>
                <div class="form-wrap">
                  <ul class="radio-group">
                    <li>
                      <label class="radio-inline">
                        <input type="radio" name="radio-group">Paypal
                      </label>
                    </li>
                  </ul>
                </div><a class="button button-secondary button-nina" href="#">place order</a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>