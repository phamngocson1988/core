<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
$this->title = 'User Profile';
$user = Yii::$app->user->getIdentity();
?>
<main>
  <section class="section-profile">
    <div class="container sec-content">
      <div class="mod-column">
        <div class="widget-box group-tabs">
          <ul class="list-tabs nav nav-pills" role="tablist">
            <li role="presentation"><a class="active" href="#editprofile" aria-controls="editprofile" role="tab" data-toggle="tab">EDIT PROFILE</a></li>
            <li role="presentation"><a href="#accountsettings" aria-controls="accountsettings" role="tab" data-toggle="tab">ACCOUNT SETTINGS</a></li>
            <li role="presentation"><a href="#notifications" aria-controls="notifications" role="tab" data-toggle="tab">NOTIFICATIONS</a></li>
          </ul>
          <div class="tab-content p-3 pb-md-5">
            <div class="tab-pane active" id="editprofile" role="tabpanel">
              <p>To customize your profile information, please enter your personal details, such as your name, country of residence and gender</p>
              <?php $form = ActiveForm::begin(['action' => Url::to(['profile/update-profile']), 'options' => ['id' => 'edit-profile-form']]); ?>
                <div class="row">
                  <div class="col-sm-6 col-md-6 col-lg-4">
                  	<?= $form->field($editProfileForm, 'firstname', [
	                    'options' => ['class' => 'mb-3'],
	                    'labelOptions' => ['class' => 'mb-2 text-uppercase'],
	                    'inputOptions' => ['class' => 'form-control btn-block']
	                  ])->textInput(['placeholder' => 'First name']);?>
                    <?= $form->field($editProfileForm, 'lastname', [
	                    'options' => ['class' => 'mb-3'],
	                    'labelOptions' => ['class' => 'mb-2 text-uppercase'],
	                    'inputOptions' => ['class' => 'form-control btn-block']
	                  ])->textInput(['placeholder' => 'Last name']);?>
	                  <?= $form->field($editProfileForm, 'country', [
	                    'options' => ['class' => 'mb-3'],
	                    'labelOptions' => ['class' => 'mb-2 text-uppercase'],
	                    'inputOptions' => ['class' => 'form-control btn-block']
	                  ])->dropdownList($editProfileForm->fetchCountry(), ['prompt' => Yii::t('app', 'select_country')]);?>
                  </div>
                  <div class="col-sm-6 col-md-6 col-lg-4 mb-3">
                    <?= $form->field($editProfileForm, 'gender', [
	                    'options' => ['class' => 'mb-3'],
	                    'labelOptions' => ['class' => 'mb-2 text-uppercase'],
	                    'inputOptions' => ['class' => 'form-control btn-block']
	                  ])->dropdownList($editProfileForm->fetchGender(), ['prompt' => Yii::t('app', 'select_gender')]);?>
                  </div>
                </div>
                <div class="row">
                  <div class="col-sm-6 col-md-6 col-lg-4">
                    <button class="btn btn-primary btn-block" type="submit">SAVE CHANGES</button>
                  </div>
                </div>
              <?php ActiveForm::end();?>
            </div>
            <div class="tab-pane" id="accountsettings" role="tabpanel">
              <div class="row">
                <div class="col-sm-6 order-sm-1">
                  <p class="border-bottom text-uppercase">CHANGE EMAIL</p>
                  <p>To change your email address, please enter your preferred email address and confirm the change.</p>
                </div>
                <div class="col-sm-6 order-sm-3 mb-sm-0 mb-4">
                  <?php $form = ActiveForm::begin(['action' => Url::to(['profile/update-email']),'options' => ['class' => 'form-change', 'id' => 'update-email-form']]); ?>
                  	<?= $form->field($updateEmailForm, 'new_email', [
	                    'options' => ['class' => 'mb-3'],
	                    'labelOptions' => ['class' => 'mb-2 text-uppercase'],
	                    'inputOptions' => ['class' => 'form-control btn-block']
	                  ])->textInput(['placeholder' => 'Enter your new email address']);?>

	                  <?= $form->field($updateEmailForm, 'confirm_email', [
	                    'options' => ['class' => 'mb-3'],
	                    'labelOptions' => ['class' => 'mb-2 text-uppercase'],
	                    'inputOptions' => ['class' => 'form-control btn-block']
	                  ])->textInput(['placeholder' => 'Re-type your new email address']);?>

	                  <?= $form->field($updateEmailForm, 'password', [
	                    'options' => ['class' => 'mb-3'],
	                    'labelOptions' => ['class' => 'mb-2 text-uppercase'],
	                    'inputOptions' => ['class' => 'form-control btn-block']
	                  ])->passwordInput(['placeholder' => 'Enter your password']);?>
                    <button class="btn btn-primary btn-block" type="submit">SAVE CHANGES</button>
                  <?php ActiveForm::end();?>
                </div>
                <div class="col-sm-6 order-sm-2">
                  <p class="border-bottom text-uppercase">CHANGE PASSWORD</p>
                  <p>To change your password, make sure you enter both your current password and your new one. To complete the action, please confirm your new password and save your changes.</p>
                </div>
                <div class="col-sm-6 order-sm-4">
                  <?php $form = ActiveForm::begin(['action' => Url::to(['profile/update-password']),'options' => ['class' => 'form-change', 'id' => 'update-password-form']]); ?>
                  	<?= $form->field($changePasswordForm, 'old_password', [
	                    'options' => ['class' => 'mb-3'],
	                    'labelOptions' => ['class' => 'mb-2 text-uppercase'],
	                    'inputOptions' => ['class' => 'form-control btn-block']
	                  ])->passwordInput(['placeholder' => 'Enter your current password']);?>
	                  <?= $form->field($changePasswordForm, 'new_password', [
	                    'options' => ['class' => 'mb-3'],
	                    'labelOptions' => ['class' => 'mb-2 text-uppercase'],
	                    'inputOptions' => ['class' => 'form-control btn-block']
	                  ])->passwordInput(['placeholder' => 'Enter your new password']);?>
	                  <?= $form->field($changePasswordForm, 're_password', [
	                    'options' => ['class' => 'mb-3'],
	                    'labelOptions' => ['class' => 'mb-2 text-uppercase'],
	                    'inputOptions' => ['class' => 'form-control btn-block']
	                  ])->passwordInput(['placeholder' => 'Confirm new password']);?>
                    <button class="btn btn-primary btn-block" type="submit">SAVE CHANGES</button>
                  <?php ActiveForm::end();?>
                </div>
              </div>
            </div>
            <div class="tab-pane" id="notifications" role="tabpanel">
              <p>Here you can handle your subscriptions. Choose whether you would like to get updates on latest bonuses, complaints, player reviews and news at your favourite online operators.</p>
              <ul class="notifications-icon">
                <li class="notifications-icon-email">Receive emails</li>
                <li class="notifications-icon-onsite">Receive on-site notifications</li>
              </ul>
              <ul class="notifications-list">
                <li>
                  <div class="notifications-thead">
                    <div class="col-first">MY REVIEWS</div>
                    <div class="col-second">RECEIVE EMAILS</div>
                    <div class="col-third">RECEIVE ON-SITE NOTIFICATIONS</div>
                  </div>
                  <div class="notifications-wrap">
                    <div class="notifications-flex">
                      <div class="col-first">Operators responded</div>
                      <div class="col-second">
                        <label class="notifications-check-email">
                          <input type="checkbox" checked><span></span>
                        </label>
                      </div>
                      <div class="col-third">
                        <label class="notifications-check-onsite">
                          <input type="checkbox" checked><span></span>
                        </label>
                      </div>
                    </div>
                    <div class="notifications-flex">
                      <div class="col-first">New player review</div>
                      <div class="col-second">
                        <label class="notifications-check-email">
                          <input type="checkbox" checked><span></span>
                        </label>
                      </div>
                      <div class="col-third">
                        <label class="notifications-check-onsite">
                          <input type="checkbox"><span></span>
                        </label>
                      </div>
                    </div>
                  </div>
                </li>
                <li>
                  <div class="notifications-thead">
                    <div class="col-first">MY REVIEWS</div>
                    <div class="col-second">RECEIVE EMAILS</div>
                    <div class="col-third">RECEIVE ON-SITE NOTIFICATIONS</div>
                  </div>
                  <div class="notifications-wrap">
                    <div class="notifications-flex">
                      <div class="col-first">Operators responded</div>
                      <div class="col-second">
                        <label class="notifications-check-email">
                          <input type="checkbox" checked><span></span>
                        </label>
                      </div>
                      <div class="col-third">
                        <label class="notifications-check-onsite">
                          <input type="checkbox" checked><span></span>
                        </label>
                      </div>
                    </div>
                    <div class="notifications-flex">
                      <div class="col-first">New player review</div>
                      <div class="col-second">
                        <label class="notifications-check-email">
                          <input type="checkbox" checked><span></span>
                        </label>
                      </div>
                      <div class="col-third">
                        <label class="notifications-check-onsite">
                          <input type="checkbox"><span>  </span>
                        </label>
                      </div>
                    </div>
                  </div>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </div>
      <div class="mod-sidebar">
        <div class="sidebar-col">
          <div class="widget-box"><a class="btn-profile trans" href="<?=Url::to(['profile/index']);?>"><i class="far fa-user-circle"></i><i class="fas fa-chevron-right"></i><span>BACK TO MY PROFILE</span></a></div>
        </div>
        <div class="sidebar-col">
          <?=\frontend\widgets\TopOperatorWidget::widget();?>
        </div>
      </div>
    </div>
  </section>
</main>

<?php
$script = <<< JS
// Update profile
var editProfileForm = new AjaxFormSubmit({element: '#edit-profile-form'});
editProfileForm.success = function (data, form) {
	toastr.success(data.message);
}
editProfileForm.error = function (errors) {	
	toastr.error(errors);
}

// Update Email
var editEmailForm = new AjaxFormSubmit({element: '#update-email-form'});
editEmailForm.success = function (data, form) {
	toastr.success(data.message);
	form.reset();
}
editEmailForm.error = function (errors) {	
	toastr.error(errors);
}

// Update Password
var editPasswordForm = new AjaxFormSubmit({element: '#update-password-form'});
editPasswordForm.success = function (data, form) {
	toastr.success(data.message);
	form.reset();
}
editPasswordForm.error = function (errors) {	
	toastr.error(errors);
}
JS;
$this->registerJs($script);
?>