{use class='yii\widgets\ActiveForm' type='block'}
<!-- BEGIN LOGIN FORM -->
{ActiveForm assign='form' options=['class' => 'login-form']}
  <h3 class="form-title font-green">Sign In</h3>
  <div class="alert alert-danger display-hide">
    <button class="close" data-close="alert"></button>
    <span> Enter any username and password. </span>
  </div>
  {$form->field($model, 'username', [
    'labelOptions' => ['class' => 'control-label visible-ie8 visible-ie9'],
    'inputOptions' => ['class' => 'form-control form-control-solid placeholder-no-fix', 'autocomplete' => 'off', 'autofocus' => true, 'placeholder' => 'Username']
  ])->textInput()}
  {$form->field($model, 'password', [
    'labelOptions' => ['class' => 'control-label visible-ie8 visible-ie9'],
    'inputOptions' => ['class' => 'form-control form-control-solid placeholder-no-fix', 'autocomplete' => 'off', 'placeholder' => 'Password']
  ])->passwordInput()}
  
  <div class="form-actions">
    <button type="submit" class="btn green uppercase">Login</button>
    {$form->field($model, 'rememberMe', [
      'options' => ['tag' => false]
    ])->checkbox([
      'label' => 'Remember<span></span>',
      'labelOptions' => ['class' => 'rememberme check mt-checkbox mt-checkbox-outline']
    ])}
  </div>
{/ActiveForm}
<!-- END LOGIN FORM -->