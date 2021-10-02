<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
?>
<!-- Modal Problem -->
<div id="modalProblem" class="modal fade">
  <div class="modal-dialog modal-login ">
    <div class="modal-content">
      <div class="modal-header">
        <div class="avatar">
          <img src="/images/avatar.png" alt="Avatar">
        </div>
        <h3 class="modal-title text-uppercase">Problem to login?</h3>
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
      </div>
      <div class="modal-body">
        <div class="forgot-password">
          <p class="text-center"><b>Forgot password</b> - The reset code will send to your
            email address. If you do not receive the email after
            five minutes, please check your junk mail folder
          </p>
          <?php $emailform = ActiveForm::begin(['action' => $emailUrl, 'id' => $emailFormId]); ?>
            <?= $emailform->field($emailModel, 'email')->textInput(['autofocus' => true, 'placeholder' => 'Email', 'required' => 'required'])->label(false) ?>
            <div class="form-group">
              <button type="submit" class="btn btn-primary btn-lg btn-block login-btn text-uppercase">submit</button>
            </div>
          <?php ActiveForm::end(); ?>
          <div class="text-center">
            <a href="javascript:;" class="show_find_email_button">Forgot Registered Email Address?</a>
          </div>
        </div>
        
        <div class="forgot-password" style="display: none">
          <p><b>Find your registered email</b> - Enter your mobile number</p>
          <?php $phoneform = ActiveForm::begin(['action' => $phoneUrl, 'id' => $phoneFormId]); ?>
            <?= $phoneform->field($phoneModel, 'phone', ['inputOptions' => ['placeholder' => 'Phone']])->widget(\website\widgets\PhoneInputWidget::class)->label(false)->hint('<span id="email_suggestion" style="color: #3ab54a"></span>');?>
            <div class="form-group">
              <button type="submit" class="btn btn-primary btn-lg btn-block login-btn text-uppercase">find email</button>
            </div>
          <?php ActiveForm::end(); ?>
          <div class="text-center">
            <a href="javascript:;" class="show_find_email_button">Back to Forgot Password Form</a>
          </div>
        </div>
        
        <!-- <div class="text-horizontal"><span>or</span></div>
        <div class="text-center">
          <p>Forgot Mobile Number?</p>
          <p><button type="button" data-dismiss="modal" aria-hidden="true" class="btn btn-primary btn-lg btn-block login-btn text-uppercase contact">Contact
            our
            support</button>
          </p>
        </div> -->
      </div>
    </div>
  </div>
</div>
<!--End Modal Problem -->