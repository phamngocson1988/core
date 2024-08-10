<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
$referrer = Yii::$app->request->referrer;
?>
<div class="section-md">
    <div class="container container-wide" style="padding-bottom: 40px; padding-top: 40px">
        <div class="col-md-5 mx-auto">
            <div class="card card-summary">
                <h5 class="card-header text-uppercase" style="color: #ff6129">Login</h5>
                <div class="card-body">
                    <div class="text-center">
                        <div id="bap-form" class="sign-up">
                            <div class="container text-center">
                            <h3 class="heading">Login</h3>
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
                            
                                <?php $form = ActiveForm::begin(['action' => Url::to(['site/signin']), 'id' => 'signin-form']);?>
                                <?= $form->field($model, 'username')->textInput(['autofocus' => true, 'id' => 'username', 'scenario' => $loginScenario, 'placeholder' => 'Username', 'required' => 'required'])->label(false) ?>
                                <?= $form->field($model, 'password')->passwordInput(['placeholder' => 'Password', 'id' => 'password', 'scenario' => $loginScenario, 'required' => 'required'])->label(false) ?>
                                <?= $form->field($model, 'securityCode', ['options' => ['style' => 'display:none', 'scenario' => $verifyScenario], 'hintOptions' => ['class' => 'hint-block']])
                                ->textInput(['placeholder' => 'Verification Code', 'id' => 'securityCode'])
                                ->hint('Verification code is sent to your email')
                                ->label(false) ?>
                                
                                <div class="d-flex bd-highlight" style="justify-content: space-between">
                                    <?=$form->field($model, 'rememberMe', [
                                            'options' => ['class' => 'form-check flex-fill', 'id' => 'rememberMe', 'scenario' => $loginScenario],
                                    'labelOptions' => ['class' => 'form-check-label'],
                                    'template' => '{input}{label}'
                                            ])->checkbox(['class' => 'form-check-input', 'style' => "margin-top:6px"], false);?>
                                    <a href="javascript:;" id="back-login-form" scenario="<?=$verifyScenario;?>" style="cursor: pointer; display: none">Back to login</a>
                                    <a href="#modalProblem" data-toggle="modal" style="cursor: pointer;" data-dismiss="modal">Problem to login?</a>
                                </div>
                                
                                <input type="hidden" id="scenario" name="scenario" value="<?=$scenario;?>" />
                                <div class="form-group mt-3">
                                    <button type="submit" id="submit" class="btn btn-primary btn-lg btn-block login-btn text-uppercase" style="background:#fe0000;">Login</button>
                                </div>
                                    <?php ActiveForm::end(); ?>
                                </div>
                                <div class="text-center">
                                <p>Not a member yet?<a href="<?=Url::to(['site/signup']);?>"  style="cursor: pointer;"> Sign up</a></p>
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
$redirectUrl = Yii::$app->user->returnUrl;

$script = <<< JS
$('html').on('submit', 'form#signin-form', function() {
    showLoader();
    var form = $(this);
    $.ajax({
        url: '/signin.html',
        type: 'post',
        dataType : 'json',
        data: form.serialize(),
        success: function (result, textStatus, jqXHR) {
            console.log(result);
            if (result.status == false) {
                toastr.error(result.errors);
                return false;
            } else {
                if (result.user_id) {
                    location.href = '$redirectUrl';
                }
                let scenario = $('#scenario').val();
                if (scenario === '$loginScenario') {
                    $('#scenario').val('$verifyScenario');
                    $("[scenario='$loginScenario']").attr('style', 'display:none');
                    $("[scenario='$verifyScenario']").attr('style', 'display:block');
                    $('#submit').text('Verify');
                } else {
                    location.href = '$redirectUrl';
                }
            }
        },
        complete: hideLoader
    });
    return false;
});
JS;
$this->registerJs($script);
?>