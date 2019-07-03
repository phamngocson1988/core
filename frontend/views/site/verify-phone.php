<?php 
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
print_r(Yii::$app->getSession()->getFlash('error'));
print_r(Yii::$app->getSession()->getFlash('success'));

?>
<?php $form = ActiveForm::begin(['id' => 'form-signup', 'class' => 'rd-mailform form-fix']); ?>
<?= $form->field($model, 'digit_1')->textInput() ?>
<?= $form->field($model, 'digit_2')->textInput() ?>
<?= $form->field($model, 'digit_3')->textInput() ?>
<?= $form->field($model, 'digit_4')->textInput() ?>
<div class="form-button">
<?= Html::submitButton('Signup', ['class' => 'button button-block button-secondary button-nina', 'name' => 'Signup']) ?>
</div>
<?php ActiveForm::end(); ?>