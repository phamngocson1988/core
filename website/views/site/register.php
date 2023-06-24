<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
?>
<div class="section-md">
    <div class="container container-wide" style="padding-bottom: 40px; padding-top: 40px">
        <div class="col-md-5 mx-auto">
            <div class="card card-summary">
                <h5 class="card-header text-uppercase" style="color: #ff6129">Register</h5>
                <div class="card-body">
                    <div class="text-center">
                        <div id="bap-form" class="sign-up">
                            <div class="container text-center">
                            <h3 class="heading">Sign up</h3>
                            <p class="text-center">with your social network</p>
                            <ul class="list-inline text-center">
                            <?php $authAuthChoice = \yii\authclient\widgets\AuthChoice::begin([
                                'baseAuthUrl' => ['site/auth'],
                                'popupMode' => false,
                            ]); ?>
                            <?php foreach ($authAuthChoice->getClients() as $key => $client): ?>
                                <?php if ($key == 'google') continue;?>
                                <li class="list-inline-item <?=$key;?>"><?= $authAuthChoice->clientLink($client) ?></li>
                            <?php endforeach; ?>
                            <?php \yii\authclient\widgets\AuthChoice::end(); ?>
                            </ul>
                            <div class="text-horizontal"><span>or</span></div>
                            
                            <?php $form = ActiveForm::begin(['action' => Url::to(['site/signup']), 'id' => 'signup-form']);?>
                            <?= $form->field($model, 'email')->textInput(['autofocus' => true, 'placeholder' => 'Email', 'required' => 'required', 'id' => 'signup-form-email'])->label(false) ?>
                            <?= $form->field($model, 'password')->passwordInput(['placeholder' => 'Password', 'required' => 'required', 'id' => 'signup-form-password'])->label(false) ?>
                            <?= $form->field($model, 'phone', ['inputOptions' => ['placeholder' => 'Phone', 'id' => 'signup-form-phone']])->widget(\website\widgets\PhoneInputWidget::class, ['country_code_id' => 'signup-form-country_code'])->label(false);?>
                                <div class="text-center mt-5">
                                <button type="submit" class="btn btn-lg">Register</button>
                                </div>
                            <?php ActiveForm::end(); ?>
                            </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
$profileUrl = Url::to(['profile/index', '#' => 'modalSecure']);
$script = <<< JS

$('html').on('submit', 'form#signup-form', function() {
    var form = $(this);
    $.ajax({
        url: '/signup.html',
        type: 'post',
        dataType : 'json',
        data: form.serialize(),
        success: function (result, textStatus, jqXHR) {
            if (result.status == false) {
                toastr.error(result.errors);
                return false;
            } else {
                window.location.href = '$profileUrl';
            }
        },
    });
    return false;
});
JS;
$this->registerJs($script);
?>