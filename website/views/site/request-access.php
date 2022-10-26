<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
?>
<div class="section-md">
    <div class="container container-wide" style="padding-bottom: 40px; padding-top: 40px">
        <div class="col-md-7 mx-auto">
            <div class="card card-summary">
                <h5 class="card-header text-uppercase" style="color: #ff6129">Request Admin to access page</h5>
                <div class="card-body">
                    <div class="text-center">
                        <?php if ($saveSuccess) :?>
                        <p>Your request is handling by admin</p>
                        <?php else : ?>
                        <?php $form = ActiveForm::begin(); ?>
                        <p>Input your name</p>
                        <?= $form->field($model, 'name')->textInput()->label(false) ?>
                        <?= Html::submitButton('Submit') ?>
                        <?php ActiveForm::end();?>
                        <?php endif;?>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>