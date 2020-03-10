{use class='yii\widgets\ActiveForm' type='block'}
<!-- BEGIN LOGIN FORM -->
{ActiveForm assign='form' options=['class' => 'login-form']}
  <h3 class="form-title font-green">{Yii::t('app', 'signin')}</h3>
  <div class="alert alert-danger display-hide">
    <button class="close" data-close="alert"></button>
    <span> {Yii::t('app', 'enter_username_and_password')} </span>
  </div>
  {$form->field($model, 'username', [
    'labelOptions' => ['class' => 'control-label visible-ie8 visible-ie9'],
    'inputOptions' => ['class' => 'form-control form-control-solid placeholder-no-fix', 'autocomplete' => 'off', 'autofocus' => true, 'placeholder' => Yii::t('app', 'username')]
  ])->textInput()}
  {$form->field($model, 'password', [
    'labelOptions' => ['class' => 'control-label visible-ie8 visible-ie9'],
    'inputOptions' => ['class' => 'form-control form-control-solid placeholder-no-fix', 'autocomplete' => 'off', 'placeholder' => Yii::t('app', 'password')]
  ])->passwordInput()}
  {$form->field($model, 'role', [
    'labelOptions' => ['class' => 'control-label visible-ie8 visible-ie9'],
    'inputOptions' => ['class' => 'form-control form-control-solid placeholder-no-fix', 'autocomplete' => 'off', 'placeholder' => 'Vai trò']
  ])->dropdownList($model->getRoles(), ['prompt' => 'Chọn vai trò đăng nhập'])}
  
  <div class="form-actions">
    <button type="submit" class="btn green uppercase">{Yii::t('app', 'login')}</button>
    {$form->field($model, 'rememberMe', [
      'options' => ['tag' => false]
    ])->checkbox([
      'label' => {Yii::t('app', 'remember')|cat:' <span></span>'},
      'labelOptions' => ['class' => 'rememberme check mt-checkbox mt-checkbox-outline']
    ])}
  </div>
{/ActiveForm}
<!-- END LOGIN FORM -->