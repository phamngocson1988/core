{use class='yii\helpers\Html'}
{use class='yii\widgets\ActiveForm' type='block'}
<!-- BEGIN PAGE BAR -->
<div class="page-bar">
  <ul class="page-breadcrumb">
    <li>
      <a href="/">{Yii::t('app', 'home')}</a>
      <i class="fa fa-circle"></i>
    </li>
    <li>
      <a href="{url route='template/index'}">Quản lý mẫu nội dung</a>
      <i class="fa fa-circle"></i>
    </li>
    <li>
      <span>Tạo mẫu nội dung</span>
    </li>
  </ul>
</div>
<!-- END PAGE BAR -->
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title">Tạo mẫu nội dung</h1>
<!-- END PAGE TITLE-->
<div class="row">
  <div class="col-md-12">
    {ActiveForm assign='form' options=['class' => 'form-horizontal form-row-seperated'] id='signup-form'}
      <div class="portlet">
        <div class="portlet-title">
          <div class="caption">Tạo mẫu nội dung</div>
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
                  {$form->field($model, 'user_id', ['template' => '{input}'])->hiddenInput(['value' => $app->user->id])}
                  {$form->field($model, 'title', [
                    'labelOptions' => ['class' => 'col-md-2 control-label'],
                    'template' => '{label}<div class="col-md-6">{input}{hint}{error}</div>'
                  ])->textInput()}
                  {$form->field($model, 'content', [
                    'labelOptions' => ['class' => 'col-md-2 control-label'],
                    'template' => '{label}<div class="col-md-6">{input}{hint}{error}</div>',
                    'hintOptions' => ['class' => 'alert alert-block alert-info fade in', 'tag' => 'div']
                  ])->textArea()->hint('Những biến được hỗ trợ: <strong>%%%NAME%%%</strong> (tên người nhận), <strong>%%%PHONE%%%</strong> (số điện thoại người nhận)')}
                  {$form->field($model, 'status', [
                    'labelOptions' => ['class' => 'col-md-2 control-label'],
                    'template' => '{label}<div class="col-md-6">{input}{hint}{error}</div>'
                  ])->dropDownList(['1' => 'Kích hoạt', '0' => 'Ngưng kích hoạt'])}
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      {/ActiveForm}
  </div>
</div>
