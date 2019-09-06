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
      <span>Paypal</span>
    </li>
  </ul>
</div>
<!-- END PAGE BAR -->
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title">Thiết lập cho Paypal</h1>
<!-- END PAGE TITLE-->
<div class="row">
  <div class="col-md-12">
    {ActiveForm assign='form' options=['class' => 'form-horizontal form-row-seperated']}
    <div class="portlet">
      <div class="portlet-title">
        <div class="caption">Thiết lập cho Paypal</div>
        <div class="actions btn-set">
          <button type="reset" class="btn default">
          <i class="fa fa-angle-left"></i> {Yii::t('app', 'reset')}
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
          <div class="tab-content repeater">
            <div class="tab-pane active" id="tab_general">
              <div class="form-body">
                {$form->field($model, 'mode', [
                  'labelOptions' => ['class' => 'col-md-2 control-label'],
                  'template' => '{label}<div class="col-md-6">{input}{hint}{error}</div>'
                ])->dropDownList(['sandbox' => 'Sử dụng tài khoản thử nghiệm', 'live' => 'Sử dụng tài khoản chính'])->label('Chế độ sử dụng')}
                {$form->field($model, 'client_id', [
                  'labelOptions' => ['class' => 'col-md-2 control-label'],
                  'template' => '{label}<div class="col-md-6">{input}{hint}{error}</div>'
                ])->textInput()}
                {$form->field($model, 'client_secret', [
                  'labelOptions' => ['class' => 'col-md-2 control-label'],
                  'template' => '{label}<div class="col-md-6">{input}{hint}{error}</div>'
                ])->textInput()->label('Secret (Mã bí mật)')}
                {$form->field($model, 'sandbox_client_id', [
                  'labelOptions' => ['class' => 'col-md-2 control-label'],
                  'template' => '{label}<div class="col-md-6">{input}{hint}{error}</div>'
                ])->textInput()->label('Client Id thử nghiệm')}
                {$form->field($model, 'sandbox_client_secret', [
                  'labelOptions' => ['class' => 'col-md-2 control-label'],
                  'template' => '{label}<div class="col-md-6">{input}{hint}{error}</div>'
                ])->textInput()->label('Secret (Mã bí mật) thử nghiệm')}
                {$form->field($model, 'fee', [
                  'labelOptions' => ['class' => 'col-md-2 control-label'],
                  'template' => '{label}<div class="col-md-6">{input}{hint}{error}</div>'
                ])->textInput()->label('Phần trăm phí thanh toán')}
                {$form->field($model, 'status', [
                  'labelOptions' => ['class' => 'col-md-2 control-label'],
                  'template' => '{label}<div class="col-md-6">{input}{hint}{error}</div>'
                ])->dropDownList(['0' => 'Chưa kích hoạt', '1' => 'Đã kích hoạt'])->label('Trạng thái kích hoạt')}
              </div>   
            </div>
          </div>
        </div>
      </div>
    </div>
    {/ActiveForm}
  </div>
</div>
