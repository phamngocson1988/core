{use class='yii\helpers\Html'}
{use class='yii\widgets\ActiveForm' type='block'}
<!-- BEGIN PAGE BAR -->
<div class="page-bar">
  <ul class="page-breadcrumb">
    <li>
      <a href="/">{Yii::t('module.shop', 'home')}</a>
      <i class="fa fa-circle"></i>
    </li>
    <li>
      <a href="{url route='product/index'}">{Yii::t('module.shop', 'manage_product_categories')}</a>
      <i class="fa fa-circle"></i>
    </li>
    <li>
      <span>{Yii::t('module.shop', 'create_product_category')}</span>
    </li>
  </ul>
</div>
<!-- END PAGE BAR -->
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title">{Yii::t('module.shop', 'create_product_category')}</h1>
<!-- END PAGE TITLE-->
<div class="row">
  <div class="col-md-12">
    {ActiveForm assign='form' options=['class' => 'form-horizontal form-row-seperated']}
      {$form->field($model, 'id')->hiddenInput()->label(false)}
      <div class="portlet">
        <div class="portlet-title">
          <div class="actions btn-set">
            <a href="{$back}" class="btn default">
            <i class="fa fa-angle-left"></i> {Yii::t('module.shop', 'back')}</a>
            <button type="submit" class="btn btn-success">
            <i class="fa fa-check"></i> {Yii::t('module.shop', 'save')}
            </button>
          </div>
        </div>
        <div class="portlet-body">
          <div class="tabbable-bordered">
            <ul class="nav nav-tabs">
              <li class="active">
                <a href="#tab_general" data-toggle="tab"> {Yii::t('module.shop', 'main_content')}</a>
              </li>
              <li>
                <a href="#tab_meta" data-toggle="tab"> {Yii::t('module.shop', 'meta')} </a>
              </li>
            </ul>
            <div class="tab-content">
              <div class="tab-pane active" id="tab_general">
                <div class="form-body">
                  {$form->field($model, 'name', [
                    'labelOptions' => ['class' => 'col-md-2 control-label'],
                    'inputOptions' => ['id' => 'name', 'class' => 'form-control'],
                    'template' => '{label}<div class="col-md-10">{input}{hint}{error}</div>'
                  ])->textInput()}
                  {$form->field($model, 'slug', [
                    'labelOptions' => ['class' => 'col-md-2 control-label'],
                    'inputOptions' => ['class' => 'slug form-control'],
                    'template' => '{label}<div class="col-md-10">{input}{hint}{error}</div>'
                  ])->textInput()}
                  {$form->field($model, 'parent_id', [
                    'labelOptions' => ['class' => 'col-md-2 control-label'],
                    'template' => '{label}<div class="col-md-10">{input}{hint}{error}</div>'
                  ])->dropDownList($model->getAvailableParent(), ['prompt' => 'Choose parent'])}
                  {$form->field($model, 'visible', [
                    'labelOptions' => ['class' => 'col-md-2 control-label'],
                    'template' => '{label}<div class="col-md-10">{input}{hint}{error}</div>'
                  ])->radioList(['Y' => 'Enable<span></span>', 'N' => 'Disable<span></span>'], [
                    'class' => 'mt-radio-inline', 
                    'encode' => false , 
                    'itemOptions' => ['labelOptions' => ['class'=>'mt-radio']]
                  ])}

                  <div class="form-group">
                    <label class="control-label col-md-2">{Yii::t('module.shop', 'image')}</label>
                    <div class="col-md-10">
                        <div class="fileinput fileinput-new" data-provides="fileinput">
                            <div class="fileinput-preview thumbnail" data-trigger="fileinput" style="width: 150px; height: 150px;">
                                <img src="{$model->getImageUrl('150x150')}" id="image" />
                            </div>
                            <div>
                              <span class="help-block"> {Yii::t('module.shop', 'image_size_at_least', ['size' => '940x630'])} </span>
                              <span class="btn default btn-file">
                                <span class="fileinput-new" id="upload-image"> {Yii::t('module.shop', 'choose_image')} </span>
                                {$form->field($model, 'image_id', [
                                  'inputOptions' => ['id' => 'image_id'], 
                                  'template' => '{input}', 
                                  'options' => ['tag' => null]
                                ])->hiddenInput()->label(false)}
                              </span>
                              <a href="javascript:void(0)" onclick="removeMainImage()" class="btn red fileinput-exists" data-dismiss="fileinput"> {Yii::t('module.shop', 'remove')} </a>
                            </div>
                        </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="tab-pane" id="tab_meta">
                <div class="form-body">
                  {$form->field($model, 'meta_title', [
                    'labelOptions' => ['class' => 'col-md-2 control-label'],
                    'template' => '{label}<div class="col-md-10">{input}{hint}{error}</div>'
                  ])->textInput()}
                  {$form->field($model, 'meta_keyword', [
                    'labelOptions' => ['class' => 'col-md-2 control-label'],
                    'template' => '{label}<div class="col-md-10">{input}{hint}{error}</div>'
                  ])->textInput()}
                  {$form->field($model, 'meta_description', [
                    'labelOptions' => ['class' => 'col-md-2 control-label'],
                    'template' => '{label}<div class="col-md-10">{input}{hint}{error}</div>'
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
<script type="text/javascript">
  // Remove main image
function removeMainImage() {
  $("#image").attr('src', '');
  $("#image_id").val('');
}
</script>

{registerJs}
{literal}
// slug
$('#name').slug();

// image
var manager = new ImageManager();
$("#upload-image").selectImage(manager, {
  callback: function(img) {
    var thumb = img.src;
    var id = img.id;
    $("#image").attr('src', thumb).removeClass('hide');
    $("#image_id").val(id);
  }
});
{/literal}
{/registerJs}