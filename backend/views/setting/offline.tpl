{use class='yii\widgets\ActiveForm' type='block'}
{use class='common\widgets\MultipleImageInputWidget'}
<div class="page-bar">
  <ul class="page-breadcrumb">
    <li>
      <a href="/">{Yii::t('app', 'home')}</a>
      <i class="fa fa-circle"></i>
    </li>
    <li>
      <a href="javascript:;">{Yii::t('app', 'settings')}</a>
      <i class="fa fa-circle"></i>
    </li>
    <li>
      <span>{Yii::t('app', 'application')}</span>
    </li>
  </ul>
</div>
<!-- END PAGE BAR -->
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title">Tài khoản ngân hàng</h1>
<!-- END PAGE TITLE-->
{ActiveForm assign='form' options=['class' => 'form-horizontal form-row-seperated']}
<div class="row">
  <div class="col-md-12">
    <div class="portlet">
      <div class="portlet-title">
        <div class="caption">Tài khoản ngân hàng</div>
        <div class="actions btn-set">
          <a href="{url route='setting/list-offline'}" class="btn default"><i class="fa fa-angle-left"></i> Danh sách giao dịch chuyển khoản</a>
          <button type="submit" class="btn btn-success">
          <i class="fa fa-check"></i> {Yii::t('app', 'save')}
          </button>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="panel panel-default">
  <div class="panel-heading">Tài khoản ngân hàng 1</div>
  <div class="panel-body">
    <div class="row">
        {$form->field($model, 'bank_name1', [
        'labelOptions' => ['class' => 'col-md-2 control-label'],
        'template' => '{label}<div class="col-md-5">{input}{hint}{error}</div>'
        ])->textInput()}
        {$form->field($model, 'account_number1', [
        'labelOptions' => ['class' => 'col-md-2 control-label'],
        'template' => '{label}<div class="col-md-5">{input}{hint}{error}</div>'
        ])->textInput()}
        {$form->field($model, 'account_holder1', [
        'labelOptions' => ['class' => 'col-md-2 control-label'],
        'template' => '{label}<div class="col-md-5">{input}{hint}{error}</div>'
        ])->textInput()}
    </div>
  </div>
</div>
<div class="panel panel-default">
  <div class="panel-heading">Tài khoản ngân hàng 2</div>
  <div class="panel-body">
    <div class="row">
        {$form->field($model, 'bank_name2', [
        'labelOptions' => ['class' => 'col-md-2 control-label'],
        'template' => '{label}<div class="col-md-5">{input}{hint}{error}</div>'
        ])->textInput()}
        {$form->field($model, 'account_number2', [
        'labelOptions' => ['class' => 'col-md-2 control-label'],
        'template' => '{label}<div class="col-md-5">{input}{hint}{error}</div>'
        ])->textInput()}
        {$form->field($model, 'account_holder2', [
        'labelOptions' => ['class' => 'col-md-2 control-label'],
        'template' => '{label}<div class="col-md-5">{input}{hint}{error}</div>'
        ])->textInput()}
    </div>
  </div>
</div>
<div class="panel panel-default">
  <div class="panel-heading">Tài khoản ngân hàng 3</div>
  <div class="panel-body">
    <div class="row">
        {$form->field($model, 'bank_name3', [
        'labelOptions' => ['class' => 'col-md-2 control-label'],
        'template' => '{label}<div class="col-md-5">{input}{hint}{error}</div>'
        ])->textInput()}
        {$form->field($model, 'account_number3', [
        'labelOptions' => ['class' => 'col-md-2 control-label'],
        'template' => '{label}<div class="col-md-5">{input}{hint}{error}</div>'
        ])->textInput()}
        {$form->field($model, 'account_holder3', [
        'labelOptions' => ['class' => 'col-md-2 control-label'],
        'template' => '{label}<div class="col-md-5">{input}{hint}{error}</div>'
        ])->textInput()}
    </div>
  </div>
</div>
<div class="panel panel-default">
  <div class="panel-heading">Tài khoản ngân hàng 4</div>
  <div class="panel-body">
    <div class="row">
        {$form->field($model, 'bank_name4', [
        'labelOptions' => ['class' => 'col-md-2 control-label'],
        'template' => '{label}<div class="col-md-5">{input}{hint}{error}</div>'
        ])->textInput()}
        {$form->field($model, 'account_number4', [
        'labelOptions' => ['class' => 'col-md-2 control-label'],
        'template' => '{label}<div class="col-md-5">{input}{hint}{error}</div>'
        ])->textInput()}
        {$form->field($model, 'account_holder4', [
        'labelOptions' => ['class' => 'col-md-2 control-label'],
        'template' => '{label}<div class="col-md-5">{input}{hint}{error}</div>'
        ])->textInput()}
    </div>
  </div>
</div>
{/ActiveForm}