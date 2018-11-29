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
<h1 class="page-title">{Yii::t('app', 'application_settings')}</h1>
<!-- END PAGE TITLE-->
<div class="row">
  <div class="col-md-12">
    {ActiveForm assign='form' options=['class' => 'form-horizontal form-row-seperated']}
    <div class="portlet">
      <div class="portlet-title">
        <div class="caption">{Yii::t('app', 'application_settings')}</div>
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
              <a href="#tab_general" data-toggle="tab"> {Yii::t('app', 'main_gallery')} </a>
            </li>
          </ul>
          <div class="tab-content">
            <div class="tab-pane active" id="tab_general">
              {$form->field($model, 'main_gallery', [
                'template' => '{input}{hint}{error}'
              ])->widget(MultipleImageInputWidget::className(), [
              ])->label(false)}
            </div>
          </div>
        </div>
      </div>
    </div>
    {/ActiveForm}
  </div>
</div>
