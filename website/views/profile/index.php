<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;

$this->title = 'User Profile';
?>
<div class="container profile my-5">
  <div class="row">
    <div class="col-md-3">
      <div class="card card-info text-center">
        <img class="card-img-top" src="/images/icon/mask.svg" alt="Card image">
        <div class="card-body">
          <h4 class="card-title"><?=sprintf("%s %s", $model->firstname, $model->lastname);?></h4>
          <p class="card-text">@<?=$model->username;?></p>
          <?php if (!$model->phone) :?>
          <div class="text-red font-weight-bold mb-2"><img class="icon-btn" src="/images/icon/warning.svg"/>UNVERIFIED</div>
          <a href="#" class="btn btn-orange" data-toggle="modal" data-target="#verify">
            Click here to VERIFY 
          </a>
          <?php else :?>
          <div class="text-red font-weight-bold mb-2"><img class="icon-btn mr-1" src="/images/icon/verrified.svg"/>VERIFIED</div>
          <?php endif;?>
        </div>
      </div>
    </div>
    <div class="col-md-9">
      <?php $form = ActiveForm::begin(); ?>
      <p class="lead">Connect</p>
      <hr />
      <ul class="list-inline">
        <?php $authAuthChoice = \yii\authclient\widgets\AuthChoice::begin([
            'baseAuthUrl' => ['site/auth'],
            'popupMode' => false,
          ]); ?>
          <?php foreach ($authAuthChoice->getClients() as $client): ?>
            <li class="list-inline-item"><?= $authAuthChoice->clientLink($client) ?></li>
          <?php endforeach; ?>
          <?php \yii\authclient\widgets\AuthChoice::end(); ?>
      </ul>
      <div class="row mt-5">
        <div class="col-md-6">
          <p class="lead">Account</p>
          <hr />
          <?= $form->field($model, 'username')->textInput(['placeholder' => 'Username', 'readonly' => true])->label(false);?>
          <?= $form->field($model, 'email')->textInput(['placeholder' => 'Email', 'readonly' => true])->label(false);?>
          <?= $form->field($model, 'phone')->textInput(['placeholder' => 'Phone number', 'class' => 'form-control phoneinp', 'readonly' => true, 'disabled' => true])->label(false);?>
          <div class="form-group">
            <div class="input-group mb-3">
              <input type="text" class="form-control inp-changepw" disabled placeholder="*********" aria-label="Example text with button addon" aria-describedby="">
              <div class="input-group-prepend">
                <button class="btn btn-green" type="button" id="btn-changepw" data-toggle="modal" data-target="#changepw">Change password</button>
              </div>
            </div>
            
          </div>
        </div>
        <div class="col-md-6">
          <p class="lead">Personal</p>
          <hr />
          <?= $form->field($model, 'firstname')->textInput(['placeholder' => 'First name'])->label(false);?>
          <?= $form->field($model, 'lastname')->textInput(['placeholder' => 'Last name'])->label(false);?>
          <?= $form->field($model, 'birthday')->textInput(['placeholder' => 'Date of Birth', 'type' => 'date'])->label(false);?>
        </div>
      </div>
      <div class="row mt-5">
        <div class="col-md-12">
          <p class="lead">Social</p>
          <hr />
        </div>
        <div class="col-md-6">
          <?= $form->field($model, 'social_facebook', [
            'options' => ['class' => 'input-group mb-3'],
            'template' => '<div class="input-group-prepend"><span class="input-group-text"><img class="icon-sm" src="/images/icon/facebook-icon.svg"></span></div>{input}'
          ])->textInput(['placeholder' => 'Link URL'])->label(false);?>
        </div>
        <div class="col-md-6">
          <?= $form->field($model, 'social_telegram', [
            'options' => ['class' => 'input-group mb-3'],
            'template' => '<div class="input-group-prepend"><span class="input-group-text"><img class="icon-sm" src="/images/icon/telegram-icon.svg"></span></div>{input}'
          ])->textInput(['placeholder' => 'Link URL'])->label(false);?>
        </div>
        <div class="col-md-6">
          <?= $form->field($model, 'social_twitter', [
            'options' => ['class' => 'input-group mb-3'],
            'template' => '<div class="input-group-prepend"><span class="input-group-text"><img class="icon-sm" src="/images/icon/twitter-icon.svg"></span></div>{input}'
          ])->textInput(['placeholder' => 'Link URL'])->label(false);?>
        </div>
        <div class="col-md-6">
          <?= $form->field($model, 'social_wechat', [
            'options' => ['class' => 'input-group mb-3'],
            'template' => '<div class="input-group-prepend"><span class="input-group-text"><img class="icon-sm" src="/images/icon/wechat-icon.svg"></span></div>{input}'
          ])->textInput(['placeholder' => 'Phone Number'])->label(false);?>
        </div>
        <div class="col-md-6">
          <?= $form->field($model, 'social_whatsapp', [
            'options' => ['class' => 'input-group mb-3'],
            'template' => '<div class="input-group-prepend"><span class="input-group-text"><img class="icon-sm" src="/images/icon/whatsapp-icon.svg"></span></div>{input}'
          ])->textInput(['placeholder' => 'Phone Number'])->label(false);?>
        </div>
        <div class="col-md-6">
          <?= $form->field($model, 'social_other', [
            'options' => ['class' => 'input-group mb-3'],
            'template' => '<div class="input-group-prepend"><span class="input-group-text">Other</span></div>{input}'
          ])->textInput()->label(false);?>
        </div>
      </div>
      <div class="text-right mt-5">
        <button type="submit" class="btn btn-red">Save changes</button>
      </div>
      <?php ActiveForm::end();?>
    </div>
  </div>
</div>

<?=\website\widgets\VerifyAccountFormWidget::widget();?>
<?=\website\widgets\ChangePasswordFormWidget::widget();?>
