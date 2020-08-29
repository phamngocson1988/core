<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
$this->title = 'Reset password';
?>
<main>
  <section class="section-module">
    <div class="container">
      <h1 class="sec-title">Type new password</h1>
      <div class="sec-content">
        <div class="mod-column form-complaints">
          <?php $form = ActiveForm::begin(); ?>
          <div class="widget-box mb-5 p-3 p-md-4">
            <div class="mb-5">
              <div class="row mb-3">
                <?= $form->field($model, 'password', [
                  'options' => ['class' => 'col-sm-12 mb-sm-0 mb-3'],
                  'labelOptions' => ['class' => 'text-uppercase mb-2']
                ])->textInput();?>
              </div>
            </div>
            <div class="text-center">
              <button class="btn btn-primary pl-3 pr-3" type="submit">Change password</button>
            </div>
          </div>
          <?php ActiveForm::end();?>
        </div>
      </div>
    </div>
  </section>
</main>