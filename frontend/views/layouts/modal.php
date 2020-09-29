<?php
use frontend\widgets\LoginFormWidget;
use frontend\widgets\SignupFormWidget;
use frontend\widgets\ResetPasswordFormWidget;
use yii\helpers\Url;
?>

<?php if (Yii::$app->user->isGuest) : ?>
<?=LoginFormWidget::widget(['loginUrl' => Url::to(['site/login'])]);?>
<?=ResetPasswordFormWidget::widget(['requestUrl' => Url::to(['site/request-password-reset'])]);?>
<?=SignupFormWidget::widget([
	'signupUrl' => Url::to(['site/signup']),
	'profileUrl' => Url::to(['profile/complete']),
]);?>
<?php
$script = <<< JS
var hash = window.location.hash.substr(1).trim();
console.log(hash);
if (hash == 'modalLogin') {
  $('#modalLogin').modal();
}
JS;
$this->registerJs($script);
?>

<?php endif;?>