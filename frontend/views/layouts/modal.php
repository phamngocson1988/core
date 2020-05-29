<?php
use frontend\widgets\LoginFormWidget;
use frontend\widgets\SignupFormWidget;
use yii\helpers\Url;
?>

<?php if (Yii::$app->user->isGuest) : ?>
<?=LoginFormWidget::widget(['loginUrl' => Url::to(['site/login'])]);?>
<?=SignupFormWidget::widget();?>
<?php
$script = <<< JS
// var hash = window.location.hash.substr(1).trim();
// console.log(hash);
// if (hash == 'modalSecure') {
//   $('#modalSecure').modal();
// }
JS;
$this->registerJs($script);
?>

<?php endif;?>