<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;

$this->title = 'User Profile';
$user = Yii::$app->user->getIdentity();
?>
<div class="container profile my-5">
  <div class="row">
    <div class="col-md-3">
      <div class="card card-info text-center">
        <img class="card-img-top" src="/images/icon/mask.svg" alt="Card image">
        <div class="card-body">
          <h4 class="card-title"><?=sprintf("%s", $model->name);?></h4>
          <p class="card-text">@<?=$model->username;?></p>
          <?php if (!$model->is_verify_email) :?>
          <div class="text-red font-weight-bold mb-2"><img class="icon-btn" src="/images/icon/warning.svg"/>UNVERIFIED</div>
          <a href="#" class="btn btn-orange" data-toggle="modal" data-target="#verify-email">
            Click here to VERIFY EMAIL
          </a>
          <?php else :?>
          <div class="text-red font-weight-bold mb-2"><img class="icon-btn mr-1" src="/images/icon/verrified.svg"/>VERIFIED</div>
          <?php endif;?>
        </div>
      </div>
      <?php if ($user->isReseller()) : ?>
        <?php
        $reseller = $user->reseller;
        if ($reseller && $reseller->code) :
        $resellerCodeLink = sprintf("https://subpayment.online/%s.html", $reseller->code);
        ?>
      <div class="card card-info text-center" style="margin-top: 20px">
      <?php
      $qc = new \common\components\qr\GoogleQR();
      $qc->URL($resellerCodeLink);
      $data = $qc->QRCODE(400);
      ?>
        <img src='data:image/png;base64, <?=$data;?>'/>
        <a class="btn btn-orange" download="qrcode.png" href="data:image/png;base64,<?=$data;?>">Download QR code <br/>Sub-Payment link</a>
      </div>
        <?php endif;?>
      <?php endif;?>
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
          <?php foreach ($authAuthChoice->getClients() as $key => $client): ?>
            <?php if ($key == 'google') continue;?>
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
          <?= $form->field($model, 'phone')->widget(\website\widgets\PhoneInputWidget::class)->label(false);?>
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
          <?= $form->field($model, 'name')->textInput(['placeholder' => 'Name'])->label(false);?>
          <?= $form->field($model, 'birthday')->textInput(['placeholder' => 'Date of Birth', 'type' => 'date', 'disabled' => true])->label(false);?>
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
<div class="container profile my-5">  
  <div class="row">
    <div class="table-wrapper table-responsive">
      <table class="table table-hover">
        <thead>
          <tr>
            <th>Tên</th>
            <th>Ngày tạo</th>
            <th>Đơn hàng đầu tiên</th>
          </tr>
        </thead>
        <tbody>
          <?php if (!count($referrers)) :?>
          <tr><td class="text-center" colspan="3">No data found</td></tr>
          <?php endif;?>
          <?php foreach ($referrers as $referrer) :?>
          <tr>
            <td><?=$referrer->getName();?></td>
            <td><?=date('d/m/Y H:i', strtotime($referrer->created_at));?></td>
            <td><?=date('d/m/Y H:i', strtotime($referrer->first_order_at));?></td>
          </tr>
          <?php endforeach;?>
        </tbody>
      </table>
    </div>
  </div>
</div>
<?php // website\widgets\VerifyAccountFormWidget::widget();?>
<?=\website\widgets\VerifyEmailFormWidget::widget();?>
<?=\website\widgets\ChangePasswordFormWidget::widget();?>