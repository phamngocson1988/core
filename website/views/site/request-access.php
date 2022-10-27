<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
?>
<?php if ($saveSuccess) : ?>
    <h1>ACCESS RESTRICTION!</h1>
<div>
    <p>Your request is sent to our team. Thank you!</p>
    <p>&mdash; The [Admin] Team</p>
</div>
<?php else : ?>
<h1>ACCESS RESTRICTION!</h1>
<div>
    <p>Sorry for the inconvenience. We&rsquo;re restric request from your area. Please <span style="color: #721b1b;font-weight:bold">type your name</span> and send a request to Admin team. We will process to grant permission to your account for this website.</p>
    <p>&mdash; The [Admin] Team</p>
</div>
<?php $form = ActiveForm::begin(); ?>
<?= $form->field($model, 'name')->textInput()->label(false) ?>
<?= Html::submitButton('Submit') ?>
<?php ActiveForm::end();?>
<?php endif;?>

