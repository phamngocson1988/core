<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
?>
<section class="section section-lg bg-default novi-background bg-cover text-center">
  <div class="container">
    <div class="row row-fix justify-content-sm-center">
      <div class="col-md-10 col-xl-8">
        <h3>Change Password</h3>
        <!-- RD Mailform-->
        <?php $form = ActiveForm::begin(); ?>
          <div class="row row-fix row-20">
            <div class="col-sm-12">
              <?= $form->field($model, 'old_password', [
                'options' => ['class' => 'form-wrap form-wrap-validation'],
                'inputOptions' => ['class' => 'form-input'],
                'labelOptions' => ['class' => 'form-label-outside'],
                'errorOptions' => ['tag' => 'span', 'class' => 'form-validation'],
                'template' => '{label}{input}{hint}{error}'
              ])->passwordInput(['autofocus' => true]) ?>

              <?= $form->field($model, 'new_password', [
                'options' => ['class' => 'form-wrap form-wrap-validation'],
                'inputOptions' => ['class' => 'form-input'],
                'labelOptions' => ['class' => 'form-label-outside'],
                'errorOptions' => ['tag' => 'span', 'class' => 'form-validation'],
                'template' => '{label}{input}{hint}{error}'
              ])->passwordInput() ?>
              <?= $form->field($model, 're_password', [
                'options' => ['class' => 'form-wrap form-wrap-validation'],
                'inputOptions' => ['class' => 'form-input'],
                'labelOptions' => ['class' => 'form-label-outside'],
                'errorOptions' => ['tag' => 'span', 'class' => 'form-validation'],
                'template' => '{label}{input}{hint}{error}'
              ])->passwordInput() ?>
            </div>
            <div class="col-lg-12 offset-custom-1">
              <div class="form-button">
                <?= Html::submitButton('Update', ['class' => 'button button-secondary button-nina']) ?>
              </div>
            </div>
          </div>
        <?php ActiveForm::end();?>
      </div>
    </div>
  </div>
</section>