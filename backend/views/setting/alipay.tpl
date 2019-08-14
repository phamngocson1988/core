{use class='yii\widgets\ActiveForm' type='block'}
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
      <span>Alipay</span>
    </li>
  </ul>
</div>
<!-- END PAGE BAR -->
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title">Thiết lập cho Alipay</h1>
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
{*<div class="panel panel-default">
  <div class="panel-heading">Tài khoản Alipay Offline</div>
  <div class="panel-body">
    <div class="row">
        {$form->field($model, 'partner', [
        'labelOptions' => ['class' => 'col-md-2 control-label'],
        'template' => '{label}<div class="col-md-5">{input}{hint}{error}</div>'
        ])->textInput()}
        {$form->field($model, 'seller_email', [
        'labelOptions' => ['class' => 'col-md-2 control-label'],
        'template' => '{label}<div class="col-md-5">{input}{hint}{error}</div>'
        ])->textInput()}
        {$form->field($model, 'key', [
        'labelOptions' => ['class' => 'col-md-2 control-label'],
        'template' => '{label}<div class="col-md-5">{input}{hint}{error}</div>'
        ])->textInput()}
    </div>
  </div>
</div>*}
<div class="panel panel-default">
  <div class="panel-heading">Tài khoản ngân hàng 1</div>
  <div class="panel-body">
    <div class="row">
        {$form->field($model, 'bank_name', [
        'labelOptions' => ['class' => 'col-md-2 control-label'],
        'template' => '{label}<div class="col-md-5">{input}{hint}{error}</div>'
        ])->textInput()}
        {$form->field($model, 'account_number', [
        'labelOptions' => ['class' => 'col-md-2 control-label'],
        'template' => '{label}<div class="col-md-5">{input}{hint}{error}</div>'
        ])->textInput()}
        {$form->field($model, 'account_holder', [
        'labelOptions' => ['class' => 'col-md-2 control-label'],
        'template' => '{label}<div class="col-md-5">{input}{hint}{error}</div>'
        ])->textInput()}
    </div>
  </div>
</div>
{/ActiveForm}