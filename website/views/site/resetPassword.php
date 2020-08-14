<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;

$this->title = 'Change password';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="container profile my-5">
  <div class="row">
    <div class="col-md-12">
      <?php $form = ActiveForm::begin(['options' => ['autocomplete' => 'off']]); ?>
      <div class="row mt-5">
        <div class="col-md-6">
          <p class="lead">Type new password.</p>
          <hr />
          <?= $form->field($model, 'password', [
            'template' => '<div class="input-group mb-3">{input}<div class="input-group-prepend">
                <button class="btn btn-green" type="submit">Change password</button>
              </div>',
          ])->passwordInput([
            'autofocus' => true,
            'class' => 'form-control inp-changepw'
          ])->label(false) ?>
          <!-- <div class="form-group">
            <div class="input-group mb-3">
              <input type="text" class="form-control inp-changepw" disabled placeholder="*********" aria-label="Example text with button addon" aria-describedby="">
              <div class="input-group-prepend">
                <button class="btn btn-green" type="button" id="btn-changepw" data-toggle="modal" data-target="#changepw">Change password</button>
              </div>
            </div>
          </div> -->
        </div>
      </div>
      <?php ActiveForm::end();?>
    </div>
  </div>
</div>