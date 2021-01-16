<?php 
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\helpers\Html;
?>
<section class="verify-code">
  <div class="container">
    <div class="small-container">
      <div class="row">
        <div class="col col-lg-12 col-md-12 col-sm-12 col-12">
          <div class="title has-left-border has-shadow">
            verify your email
          </div>
          <div class="row wrap-code-verify">
            <div class="col-12 col-md-4 left-code">
              <img src="/images/email.png" alt="">
            </div>
            <div class="col-12 col-md-8 right-code">
              <p>An email has sent to <span><?=$user->email;?></span><br>
                Click to the link in email to activate your account.
              </p>
              <!-- <p>Didn’t get the code? <a href="" class="red">Resend code</a></p> -->
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>