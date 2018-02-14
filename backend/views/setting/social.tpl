{use class='yii\widgets\ActiveForm' type='block'}
<div class="page-content-wrapper">
  <!-- BEGIN CONTENT BODY -->
  <div class="page-content">
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
          <span>{Yii::t('app', 'social_networks')}</span>
        </li>
      </ul>
    </div>
    <!-- END PAGE BAR -->
    <!-- BEGIN PAGE TITLE-->
    <h1 class="page-title">{Yii::t('app', 'social_networks')}</h1>
    <!-- END PAGE TITLE-->
    <div class="row">
      <div class="col-md-12">
        {ActiveForm assign='form' options=['class' => 'form-horizontal form-row-seperated']}
        <div class="portlet">
          <div class="portlet-title">
            <div class="caption">{Yii::t('app', 'social_networks')}</div>
            <div class="actions btn-set">
              <button type="reset" class="btn default">
              <i class="fa fa-angle-left"></i> {Yii::t('app', 'reset')}</a>
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
                    {$form->field($model, 'facebook', [
                      'labelOptions' => ['class' => 'col-md-2 control-label'],
                      'template' => '{label}<div class="col-md-6">{input}{hint}{error}</div>'
                    ])->textInput()}
                    {$form->field($model, 'twitter', [
                      'labelOptions' => ['class' => 'col-md-2 control-label'],
                      'template' => '{label}<div class="col-md-6">{input}{hint}{error}</div>'
                    ])->textInput()}
                    {$form->field($model, 'gplus', [
                      'labelOptions' => ['class' => 'col-md-2 control-label'],
                      'template' => '{label}<div class="col-md-6">{input}{hint}{error}</div>'
                    ])->textInput()}
                    {$form->field($model, 'rss', [
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