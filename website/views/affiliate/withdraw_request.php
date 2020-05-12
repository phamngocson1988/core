<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use frontend\models\UserAffiliate;
use yii\captcha\Captcha;
use frontend\behaviors\UserCommissionBehavior;

$this->title = 'Request withdraw commission';
$this->params['breadcrumbs'][] = $this->title;
$user->attachBehavior('commission', UserCommissionBehavior::className());
?>

<section class="page-title">
  <div class="container">
    <div class="row">
      <div class="col col-sm-12">
        <div class="page-title-content text-center pad-bot">
          <div class="page-title-image">
            <img src="/images/text-affiliate.png" alt="">
          </div>
          <p class="no-upper">Link & Earn</p>
          <p class="small-txt">Earn up to <span>20%</span> of Kinggems Net Profit....</p>
        </div>
      </div>
    </div>
  </div>
</section>
<section class="register-page">
  <div class="container">
    <div class="small-container">
      <div class="row">
        <div class="col col-12 col-lg-7 col-md-7 col-sm-12">
          <div class="register-block">
            <?php $form = ActiveForm::begin(['id' => 'form-signup', 'options' => ['autocomplete' => 'off']]); ?>
              <div class="form-group">
                <label class="control-label">Available amount</label>
                <label class="control-label">$<?=$user->getAvailabelCommission();?></label>
              </div>
              <?= $form->field($model, 'amount', ['inputOptions' => ['type' => 'number']])->textInput()->label('Amount you wish to withdraw <span class="required">*</span>') ?>
              <div class="register-action">
                <a href="<?=Url::to(['affiliate/withdraw']);?>" class="cus-btn has-shadow" style="background-color: #ccc">Back</a>
                <button type="submit" class="cus-btn yellow has-shadow">Sent request!</button>
              </div>
            <?php ActiveForm::end(); ?>
          </div>
        </div>
        <div class="col col-12 col-lg-1 col-md-1 col-sm-12"></div>
        <div class="col col-12 col-lg-4 col-md-4 col-sm-12">
          <?php echo $this->render('@frontend/views/site/_reg_deposit.php');?>
        </div>
      </div>
    </div>
  </div>
</section>