<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use common\models\User;
?>
<section class="page-title">
  <div class="container">
    <div class="row">
      <div class="col col-sm-12">
        <div class="page-title-content text-center">
          <img src="/images/text-your-account.png" alt="">
        </div>
        <div class="page-title-sub">
          <p>Manage your account</p>
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
        <div class="profile-right">
          <div class="profile-top">
            <p class="profile-name"><?=$model->name;?></p>
            <div class="wrap-info">
              <span>
                <p>Birthday</p>
                <p><?=$model->birthday;?></p>
              </span>
              <span>
                <p>Address</p>
                <p><?=$model->address;?></p>
              </span>
              <span>
                <p>Phone</p>
                <p><?=$model->phone;?></p>
              </span>
              <span>
                <p>Email</p>
                <p><?=$model->email;?></p>
              </span>
            </div>
            <a class="btn-product-detail-add-to-cart has-shadow" href="javascript:;" id='edit-button'>edit your account</a>
          </div>
          <div class="profile-coin">
            <div class="left-coin">
              <p class="profile-title-coin">KINGGEM COINS INFORMATION</p>
              <span>
                <p>Full name</p>
                <p>Mail</p>
                <p>Phone</p>
                <p>Account balance</p>
              </span>
              <span>
                <p><?=$model->name;?></p>
                <p><?=$model->email;?></p>
                <p><?=$model->phone;?></p>
                <p><?=number_format($model->getWalletAmount());?></p>
              </span>
            </div>
            <div class="right-coin">
              <img src="/images/logo-king-coin.png" alt="">
            </div>
          </div>
          <div class="profile-edit" style="display: none">
            <?php $form = ActiveForm::begin(); ?>
              <?= $form->field($model, 'name');?>
              <?= $form->field($model, 'address');?>
              <?= Html::submitButton('Submit', ['class' => 'btn-product-detail-add-to-cart has-shadow']) ?>
            <?php ActiveForm::end();?>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<?php
$script = <<< JS
$('#edit-button').on('click', function(){
  $('.profile-top, .profile-coin').hide();
  $('.profile-edit').show();
})
JS;
$this->registerJs($script);
?>
