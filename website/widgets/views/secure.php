<?php
use yii\bootstrap\ActiveForm;
?>
<!-- Modal Secure -->
<div id="modalSecure" class="modal fade">
  <div class="modal-dialog modal-login ">
    <div class="modal-content">
      <div class="modal-header">
        <div class="avatar">
          <img src="/images/avatar.png" alt="Avatar">
        </div>
        <h3 class="modal-title text-uppercase">Secure your account</h3>
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
      </div>
      <div class="modal-body">
        <p class="text-center">In order to indentify it’s you, please enter your real name.
          Once verified. it cannot be changed
        </p>
        <?php $form = ActiveForm::begin(['action' => $url, 'id' => $id]); ?>
          <?= $form->field($model, 'firstname')->textInput(['autofocus' => true, 'placeholder' => 'Your first name', 'required' => 'required'])->label(false) ?>
          <?= $form->field($model, 'lastname')->textInput(['placeholder' => 'Your last name', 'required' => 'required'])->label(false) ?>
          <!-- <p>To protect the security of your account, please add your mobile number and request for the security
            token. We will send you a text message with 6-digit security token that you’ll need to enter below
          </p> 
          <?=$form->field($model, 'phone', [
            'template' => '{input}', 
            'options' => ['class' => 'input-group mb-3'],
            'inputOptions' => ['required' => 'required', 'class' => 'form-control phoneinp', 'id' => $phoneId]
          ])->textInput()->label(false);?>
          <div class="input-group mb-3">
            <div class="input-group-prepend mr-2">
              <button class="btn btn-request-token border-radius-3 text-left" type="button" id="<?=$buttonId;?>">Security token
              <br />
              <small>Request Token</small></button>
            </div>
            <?=$form->field($model, 'code', [
              'template' => '{input}', 
              'options' => ['tag' => false],
              'inputOptions' => ['class' => 'form-control border-radius-3']
            ])->textInput()->label(false);?>
          </div>
          -->
          <p style="color: #f7931f;">Your favourite games</p>
          <?= $form->field($model, 'favourite', ['template' => '{input}', 'options' => ['tag' => false]])->dropdownList($model->fetchGame(), ['multiple' => true, 'id' => 'multiple'])->label(false) ?>
          <p class="mt-3" style="color: #009345;">Contact apps</p>
          <ul class="list-inline contact-apps">
            <li class="list-inline-item"><a href="javascript:;" data-platform="facebook"><img class="icon-md" src="/images/icon/facebook-icon.svg" /></a>
            </li>
            <li class="list-inline-item"><a href="javascript:;" data-platform="telegram"><img class="icon-md" src="/images/icon/telegram-icon.svg" /></a>
            </li>
            <li class="list-inline-item"><a href="javascript:;" data-platform="twitter"><img class="icon-md" src="/images/icon/twitter-icon.svg" /></a>
            </li>
            <li class="list-inline-item"><a href="javascript:;" data-platform="wechat"><img class="icon-md" src="/images/icon/wechat-icon.svg" /></a>
            </li>
            <li class="list-inline-item"><a href="javascript:;" data-platform="whatsapp"><img class="icon-md" src="/images/icon/whatsapp-icon.svg" /></a>
            </li>
          </ul>
          <?=$form->field($model, 'social_facebook', [
            'template' => '{input}', 
            'options' => ['tag' => false],
            'inputOptions' => ['class' => 'form-control platform d-none inp-url inputDisabled', 'placeholder' => 'Enter url profile', 'id' => 'facebook']
          ])->textInput()->label(false);?>
          <?=$form->field($model, 'social_wechat', [
            'template' => '{input}', 
            'options' => ['tag' => false],
            'inputOptions' => ['class' => 'form-control platform d-none inp-url inputDisabled', 'placeholder' => 'Enter url profile', 'id' => 'wechat']
          ])->textInput()->label(false);?>
          <?=$form->field($model, 'social_twitter', [
            'template' => '{input}', 
            'options' => ['tag' => false],
            'inputOptions' => ['class' => 'form-control platform d-none inp-url inputDisabled', 'placeholder' => 'Enter url profile', 'id' => 'twitter']
          ])->textInput()->label(false);?>
          <?=$form->field($model, 'social_telegram', [
            'template' => '{input}', 
            'options' => ['tag' => false],
            'inputOptions' => ['class' => 'form-control platform d-none inp-url inputDisabled', 'placeholder' => 'Enter url profile', 'id' => 'telegram']
          ])->textInput()->label(false);?>
          <?=$form->field($model, 'social_whatsapp', [
            'template' => '{input}', 
            'options' => ['tag' => false],
            'inputOptions' => ['class' => 'form-control platform d-none inp-url inputDisabled', 'placeholder' => 'Enter url profile', 'id' => 'whatsapp']
          ])->textInput()->label(false);?>
          <!-- <input type="text" class="form-control inp-url inputDisabled" disabled name="tel" placeholder="Enter url profile"> -->
          <div class="form-group mt-5 d-flex">
            <div class="flex-fill w-100 mr-2">
              <button type="button" class="btn-block btn btn-skip login-btn text-uppercase"
                data-dismiss="modal">skip</button>
            </div>
            <div class="flex-fill w-100">
              <button type="submit" class="btn-block btn btn-save login-btn text-uppercase">save</button>
            </div>
          </div>
        <?php ActiveForm::end(); ?>
      </div>
    </div>
  </div>
</div>