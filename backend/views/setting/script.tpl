{use class='yii\widgets\ActiveForm' type='block'}
<div class="page-content-wrapper">
  <!-- BEGIN CONTENT BODY -->
  <div class="page-content">
    <div class="page-bar">
      <ul class="page-breadcrumb">
        <li>
          <a href="/">Home</a>
          <i class="fa fa-circle"></i>
        </li>
        <li>
          <a href="javascript:;">Settings</a>
          <i class="fa fa-circle"></i>
        </li>
        <li>
          <span>Header Scripts</span>
        </li>
      </ul>
    </div>
    <!-- END PAGE BAR -->
    <!-- BEGIN PAGE TITLE-->
    <h1 class="page-title">Header Scripts</h1>
    <!-- END PAGE TITLE-->
    <div class="row">
      <div class="col-md-12">
        {ActiveForm assign='form' options=['class' => 'form-horizontal form-row-seperated']}
        <div class="portlet">
          <div class="portlet-title">
            <div class="caption">Header Scripts</div>
            <div class="actions btn-set">
              <button type="reset" class="btn default">
              <i class="fa fa-angle-left"></i> Reset</a>
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
                    {$form->field($model, 'code', [
                      'labelOptions' => ['class' => 'col-md-2 control-label'],
                      'inputOptions' => ['rows' => 10, 'style' => 'width:100%'],
                      'template' => '{label}<div class="col-md-10">{input}{hint}{error}</div>'
                    ])->textarea()}
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
</div>