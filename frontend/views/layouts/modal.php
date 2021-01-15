<?php
use frontend\widgets\LoginFormWidget;
use frontend\widgets\SignupFormWidget;
use frontend\widgets\ProblemFormWidget;
use frontend\widgets\SecureFormWidget;
use yii\helpers\Url;
?>

<?php if (Yii::$app->user->isGuest) : ?>
<?=LoginFormWidget::widget();?>
<?=SignupFormWidget::widget();?>
<?=ProblemFormWidget::widget([
	'emailUrl' => Url::to(['site/request-password-reset']),
	'phoneUrl' => Url::to(['site/request-email-reset']),
]);?>

<?php else : ?>
<?=SecureFormWidget::widget(['url' => Url::to(['user/update-secure-profile'])]);?>
<?php
$script = <<< JS
var hash = window.location.hash.substr(1).trim();
console.log(hash);
if (hash == 'modalSecure') {
  $('#modalSecure').modal();
}
JS;
$this->registerJs($script);
?>

<?php endif;?>