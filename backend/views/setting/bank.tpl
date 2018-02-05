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
          <span>Bank Accounts</span>
        </li>
      </ul>
    </div>
    <!-- END PAGE BAR -->
    <!-- BEGIN PAGE TITLE-->
    <h1 class="page-title">Bank Accounts</h1>
    <!-- END PAGE TITLE-->
    <div class="row">
      <div class="col-md-12">
        {ActiveForm assign='form' options=['class' => 'form-horizontal form-row-seperated']}
        <div class="portlet">
          <div class="portlet-title">
            <div class="caption">Bank Accounts</div>
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
              <div class="tab-content repeater">
                <div class="tab-pane active" id="tab_general">
                  <div class="form-body">
                    {$form->field($model, 'bankName1', [
                      'labelOptions' => ['class' => 'col-md-2 control-label'],
                      'template' => '{label}<div class="col-md-6">{input}{hint}{error}</div>'
                    ])->textInput()}
                    {$form->field($model, 'bankHolder1', [
                      'labelOptions' => ['class' => 'col-md-2 control-label'],
                      'template' => '{label}<div class="col-md-6">{input}{hint}{error}</div>'
                    ])->textInput()}
                    {$form->field($model, 'bankNumber1', [
                      'labelOptions' => ['class' => 'col-md-2 control-label'],
                      'template' => '{label}<div class="col-md-6">{input}{hint}{error}</div>'
                    ])->textInput()}
                    {$form->field($model, 'bankBranch1', [
                      'labelOptions' => ['class' => 'col-md-2 control-label'],
                      'template' => '{label}<div class="col-md-6">{input}{hint}{error}</div>'
                    ])->textInput()}
                  </div>   
                  <hr>
                  <div class="form-body">
                    {$form->field($model, 'bankName2', [
                      'labelOptions' => ['class' => 'col-md-2 control-label'],
                      'template' => '{label}<div class="col-md-6">{input}{hint}{error}</div>'
                    ])->textInput()}
                    {$form->field($model, 'bankHolder2', [
                      'labelOptions' => ['class' => 'col-md-2 control-label'],
                      'template' => '{label}<div class="col-md-6">{input}{hint}{error}</div>'
                    ])->textInput()}
                    {$form->field($model, 'bankNumber2', [
                      'labelOptions' => ['class' => 'col-md-2 control-label'],
                      'template' => '{label}<div class="col-md-6">{input}{hint}{error}</div>'
                    ])->textInput()}
                    {$form->field($model, 'bankBranch2', [
                      'labelOptions' => ['class' => 'col-md-2 control-label'],
                      'template' => '{label}<div class="col-md-6">{input}{hint}{error}</div>'
                    ])->textInput()}
                  </div>   
                  <hr>
                  <div class="form-body">
                    {$form->field($model, 'bankName3', [
                      'labelOptions' => ['class' => 'col-md-2 control-label'],
                      'template' => '{label}<div class="col-md-6">{input}{hint}{error}</div>'
                    ])->textInput()}
                    {$form->field($model, 'bankHolder3', [
                      'labelOptions' => ['class' => 'col-md-2 control-label'],
                      'template' => '{label}<div class="col-md-6">{input}{hint}{error}</div>'
                    ])->textInput()}
                    {$form->field($model, 'bankNumber3', [
                      'labelOptions' => ['class' => 'col-md-2 control-label'],
                      'template' => '{label}<div class="col-md-6">{input}{hint}{error}</div>'
                    ])->textInput()}
                    {$form->field($model, 'bankBranch3', [
                      'labelOptions' => ['class' => 'col-md-2 control-label'],
                      'template' => '{label}<div class="col-md-6">{input}{hint}{error}</div>'
                    ])->textInput()}
                  </div> 
                  <hr>
                  <div class="form-body">
                    {$form->field($model, 'bankName4', [
                      'labelOptions' => ['class' => 'col-md-2 control-label'],
                      'template' => '{label}<div class="col-md-6">{input}{hint}{error}</div>'
                    ])->textInput()}
                    {$form->field($model, 'bankHolder4', [
                      'labelOptions' => ['class' => 'col-md-2 control-label'],
                      'template' => '{label}<div class="col-md-6">{input}{hint}{error}</div>'
                    ])->textInput()}
                    {$form->field($model, 'bankNumber4', [
                      'labelOptions' => ['class' => 'col-md-2 control-label'],
                      'template' => '{label}<div class="col-md-6">{input}{hint}{error}</div>'
                    ])->textInput()}
                    {$form->field($model, 'bankBranch4', [
                      'labelOptions' => ['class' => 'col-md-2 control-label'],
                      'template' => '{label}<div class="col-md-6">{input}{hint}{error}</div>'
                    ])->textInput()}
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