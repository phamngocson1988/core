<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
?>
<section class="page-title">
  <div class="container">
    <div class="row">
      <div class="col col-sm-12">
        <div class="page-title-content text-center">
          <img src="/images/text-your-account.png" alt="">
        </div>
        <div class="page-title-sub">
          <p>Change password</p>
        </div>
      </div>
    </div>
  </div>
</section>
<section class="profile-page">
  <div class="container-fluid">
    <div class="row">
      <?php require_once(Yii::$app->basePath . '/views/user/_left_menu.php');?>
      <div class="wrap-profile-right col col-lg-8 col-md-9 col-sm-12 col-12">
        <div class="profile-right" style="width: 100%;">
          <div class="profile-password">
            <?php $form = ActiveForm::begin(['options' => ['class' => 'form-horizontal']]); ?>
              <?= $form->field($model, 'old_password')->passwordInput(['autofocus' => true]) ?>
              <?= $form->field($model, 'new_password')->passwordInput() ?>
              <?= $form->field($model, 're_password')->passwordInput() ?>
              <?= Html::submitButton('Update', ['class' => 'btn-product-detail-add-to-cart has-shadow']) ?>
            <?php ActiveForm::end();?>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>