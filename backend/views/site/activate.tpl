{use class='yii\widgets\ActiveForm' type='block'}
<!-- BEGIN LOGIN FORM -->
{ActiveForm assign='form' options=['class' => 'activate-form']}
  <h3 class="form-title font-green">{Yii::t('app', 'activate_user')}</h3>
  {$form->field($model, 'password', [
    'labelOptions' => ['class' => 'control-label visible-ie8 visible-ie9'],
    'inputOptions' => ['class' => 'form-control form-control-solid placeholder-no-fix', 'autocomplete' => 'off', 'placeholder' => Yii::t('app', 'password')]
  ])->passwordInput()}
  
  <div class="form-actions">
    <button type="submit" class="btn green uppercase">{Yii::t('app', 'activate')}</button>
    <a class="green uppercase" href="{url route='site/login'}">{Yii::t('app', 'go_login')}</a>
  </div>
{/ActiveForm}
<!-- END LOGIN FORM -->