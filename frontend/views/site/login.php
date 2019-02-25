<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Login';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="section-lg text-center">
  <div class="container">
    <h3>Đăng nhập</h3>
    <div class="row row-fix justify-content-sm-center">
      <div class="col-md-8 col-lg-6 col-xl-4">
        <!-- RD Mailform-->
        <?php $form = ActiveForm::begin(['id' => 'form-signup', 'class' => 'rd-mailform form-fix']); ?>
          <?= $form->field($model, 'username', [
            'options' => ['class' => 'form-wrap form-wrap-validation'],
            'inputOptions' => ['class' => 'form-input'],
            'labelOptions' => ['class' => 'form-label'],
            'errorOptions' => ['tag' => 'span', 'class' => 'form-validation'],
            'template' => '{input}{label}{hint}{error}'
          ])->textInput(['autofocus' => true]) ?>

          <?= $form->field($model, 'password', [
            'options' => ['class' => 'form-wrap form-wrap-validation'],
            'inputOptions' => ['class' => 'form-input'],
            'labelOptions' => ['class' => 'form-label'],
            'errorOptions' => ['tag' => 'span', 'class' => 'form-validation'],
            'template' => '{input}{label}{hint}{error}'
          ])->passwordInput() ?>

          <div class="form-button">
            <?= Html::submitButton('Signup', ['class' => 'button button-block button-secondary button-nina', 'name' => 'Đăng ký']) ?>
          </div>
        <?php ActiveForm::end(); ?>
      </div>
    </div>
    <p class="offset-custom-1 text-gray-light">Ghé thăm chúng tôi</p>
    <div class="group-xs group-middle"><a class="icon icon-md-smaller icon-circle icon-filled mdi mdi-facebook" href="#"></a><a class="icon icon-md-smaller icon-circle icon-filled mdi mdi-twitter" href="#"></a><a class="icon icon-md-smaller icon-circle icon-filled mdi mdi-google" href="#"></a></div>
  </div>
</div>
