{use class='yii\helpers\Html'}
{use class='yii\widgets\ActiveForm' type='block'}
{use class='dosamigos\datepicker\DatePicker'}
<!-- BEGIN PAGE BAR -->
<div class="page-bar">
  <ul class="page-breadcrumb">
    <li>
      <a href="/">{Yii::t('app', 'home')}</a>
      <i class="fa fa-circle"></i>
    </li>
    <li>
      <a href="{url route='customer/index'}">{Yii::t('app', 'manage_customers')}</a>
      <i class="fa fa-circle"></i>
    </li>
    <li>
      <span>{Yii::t('app', 'manage_customer')}</span>
    </li>
  </ul>
</div>
<!-- END PAGE BAR -->
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title">{Yii::t('app', 'manage_customer')}</h1>
<!-- END PAGE TITLE-->
<div class="row">
  <div class="col-md-12">
    {ActiveForm assign='form' options=['class' => 'form-horizontal form-row-seperated'] id='edit-customer'}
      {$form->field($model, 'id', [
        'template' => '{input}'
      ])->hiddenInput()}
      <div class="portlet">
        <div class="portlet-title">
          <div class="caption">{Yii::t('app', 'manage_customer')}</div>
          <div class="actions btn-set">
            <a href="{$back}" class="btn default">
            <i class="fa fa-angle-left"></i> {Yii::t('app', 'back')}</a>
            <button type="submit" class="btn btn-success">
            <i class="fa fa-check"></i> {Yii::t('app', 'save')}
            </button>
          </div>
        </div>
        <div class="portlet-body">
          <div class="tabbable-bordered">
            <ul class="nav nav-tabs">
              <li class="active">
                <a href="#tab_general" data-toggle="tab"> {Yii::t('app', 'main_content')} </a>
              </li>
            </ul>
            <div class="tab-content">
              <div class="tab-pane active" id="tab_general">
                <div class="form-body">
                  {$form->field($model, 'name', [
                    'labelOptions' => ['class' => 'col-md-2 control-label'],
                    'template' => '{label}<div class="col-md-6">{input}{hint}{error}</div>'
                  ])->textInput()}
                  {$form->field($model, 'username', [
                    'labelOptions' => ['class' => 'col-md-2 control-label'],
                    'template' => '{label}<div class="col-md-6">{input}{hint}{error}</div>'
                  ])->textInput(['disabled' => true])}
                  {$form->field($model, 'email', [
                    'labelOptions' => ['class' => 'col-md-2 control-label'],
                    'template' => '{label}<div class="col-md-6">{input}{hint}{error}</div>'
                  ])->textInput(['disabled' => true])}
                  {$form->field($model, 'phone', [
                    'labelOptions' => ['class' => 'col-md-2 control-label'],
                    'template' => '{label}<div class="col-md-6">{input}{hint}{error}</div>'
                  ])->textInput()}
                  {$form->field($model, 'address', [
                    'labelOptions' => ['class' => 'col-md-2 control-label'],
                    'template' => '{label}<div class="col-md-6">{input}{hint}{error}</div>'
                  ])->textInput()}
                  {$form->field($model, 'birthday', [
                    'labelOptions' => ['class' => 'col-md-2 control-label'],
                    'template' => '{label}<div class="col-md-2">{input}{hint}{error}</div>'
                  ])->widget(DatePicker::className(), [
                    'inline' => false, 
                    'template' => '<div class="input-group date" data-provide="datepicker">{input}<div class="input-group-addon"><span class="glyphicon glyphicon-th"></span></div></div>',
                    'clientOptions' => [
                        'autoclose' => true,
                        'format' => 'yyyy-mm-dd'
                    ]
                  ])}
                  {$form->field($model, 'social_line', [
                    'labelOptions' => ['class' => 'col-md-2 control-label'],
                    'template' => '{label}<div class="col-md-6">{input}{hint}{error}</div>'
                  ])->textInput()}
                  {$form->field($model, 'social_zalo', [
                    'labelOptions' => ['class' => 'col-md-2 control-label'],
                    'template' => '{label}<div class="col-md-6">{input}{hint}{error}</div>'
                  ])->textInput()}
                  {$form->field($model, 'social_facebook', [
                    'labelOptions' => ['class' => 'col-md-2 control-label'],
                    'template' => '{label}<div class="col-md-6">{input}{hint}{error}</div>'
                  ])->textInput()}
                  {$form->field($model, 'status', [
                    'labelOptions' => ['class' => 'col-md-2 control-label'],
                    'template' => '{label}<div class="col-md-6">{input}{hint}{error}</div>'
                  ])->dropDownList($model->getUserStatus(), ['prompt' => Yii::t('app', 'choose')])}


                  <div class="form-group">
                    <label class="col-md-2 control-label" for="generate-password-checkbox">{Yii::t('app', 'generate_password')}</label>
                    <div class="col-md-6">
                      {dosamigos\switchinput\SwitchBox::widget([
                        'name' => 'send_mail',
                        'checked' => false,
                        'clientOptions' => [
                          'size' => 'medium',
                          'onColor' => 'success',
                          'offColor' => 'danger',
                          'onText' => "{Yii::t('app', 'send_mail')}",
                          'offText' => "{Yii::t('app', 'not_send_mail')}"
                        ]
                      ])} <a href="{url route='customer/generate-password' id=$model->id}" class="btn btn-warning generate-password"><i class="fa fa-key"></i> {Yii::t('app', 'generate_password')}</a>
                    </div> 
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      {/ActiveForm}
  </div>
</div>

{registerJs}
{literal}
var _csrf = "{/literal}{$app->request->getCsrfToken()}{literal}";
$("a.generate-password").ajax_action({
  data: {_csrf: _csrf},
  method: 'POST',
  collectData: function() {
    return {send_mail: $('input[name=send_mail]:checked').val()};
  },
  confirm: true,
  confirm_text: '{/literal}{Yii::t('app', 'confirm_generate_customer_password')}{literal}',
  callback: function(eletement, data) {
    console.log(data);
    swal('Generate password successfully', "New password: " + data.password, "success");
  },
  error: function(element, errors) {
    swal('Generate password failure', errors[0], "error");
  }
});
{/literal}
{/registerJs}
