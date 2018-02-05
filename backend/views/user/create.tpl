{use class='yii\helpers\Html'}
{use class='yii\widgets\ActiveForm' type='block'}
<div class="page-content-wrapper">
  <!-- BEGIN CONTENT BODY -->
  <div class="page-content">
    <!-- BEGIN PAGE BAR -->
    <div class="page-bar">
      <ul class="page-breadcrumb">
        <li>
          <a href="/">Home</a>
          <i class="fa fa-circle"></i>
        </li>
        <li>
          <a href="{url route='user/index'}">Manage Users</a>
          <i class="fa fa-circle"></i>
        </li>
        <li>
          <span>Create User</span>
        </li>
      </ul>
    </div>
    <!-- END PAGE BAR -->
    <!-- BEGIN PAGE TITLE-->
    <h1 class="page-title">Create User</h1>
    <!-- END PAGE TITLE-->
    <div class="row">
      <div class="col-md-12">
        {ActiveForm assign='form' options=['class' => 'form-horizontal form-row-seperated'] id='signup-form'}
          <div class="portlet">
            <div class="portlet-title">
              <div class="caption">Create User</div>
              <div class="actions btn-set">
                <a href="{$back}" class="btn default">
                <i class="fa fa-angle-left"></i> Back</a>
                <button type="submit" class="btn btn-success">
                <i class="fa fa-check"></i> Save
                </button>
              </div>
            </div>
            <div class="portlet-body">
              <div class="tabbable-bordered">
                <ul class="nav nav-tabs">
                  <li class="active">
                    <a href="#tab_general" data-toggle="tab"> Main content </a>
                  </li>
                </ul>
                <div class="tab-content">
                  <div class="tab-pane active" id="tab_general">
                    <div class="form-body">
                      {$form->field($model, 'username', [
                        'labelOptions' => ['class' => 'col-md-2 control-label'],
                        'template' => '{label}<div class="col-md-6">{input}{hint}{error}</div>'
                      ])->textInput()}
                      {$form->field($model, 'email', [
                        'labelOptions' => ['class' => 'col-md-2 control-label'],
                        'template' => '{label}<div class="col-md-6">{input}{hint}{error}</div>'
                      ])->textInput()}
                      {$form->field($model, 'password', [
                        'labelOptions' => ['class' => 'col-md-2 control-label'],
                        'template' => '{label}<div class="col-md-6">{input}{hint}{error}</div>'
                      ])->passwordInput()}
                      {$form->field($model, 'role', [
                        'labelOptions' => ['class' => 'col-md-2 control-label'],
                        'template' => '{label}<div class="col-md-6">{input}{hint}{error}</div>'
                      ])->dropDownList($model->getRoles(), ['prompt' => 'Choose'])}
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          {/ActiveForm}
      </div>
    </div>
  </div>
  <!-- END CONTENT BODY -->
</div>
