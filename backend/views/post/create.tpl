{use class='yii\helpers\Html'}
{use class='yii\widgets\ActiveForm' type='block'}
<div class="page-content-wrapper">
  <!-- BEGIN CONTENT BODY -->
  <div class="page-content">
    <!-- BEGIN PAGE BAR -->
    <div class="page-bar">
      <ul class="page-breadcrumb">
        <li>
          <a href="/">{Yii::t('app', 'home')}</a>
          <i class="fa fa-circle"></i>
        </li>
        <li>
          <a href="{url route='post/index'}">{Yii::t('app', 'manage_posts')}</a>
          <i class="fa fa-circle"></i>
        </li>
        <li>
          <span>{Yii::t('app', 'create_post')}</span>
        </li>
      </ul>
    </div>
    <!-- END PAGE BAR -->
    <!-- BEGIN PAGE TITLE-->
    <h1 class="page-title">{Yii::t('app', 'create_post')}</h1>
    <!-- END PAGE TITLE-->
    <div class="row">
      <div class="col-md-12">
        {ActiveForm assign='form' options=['class' => 'form-horizontal form-row-seperated']}
          <div class="portlet">
            <div class="portlet-title">
              <div class="caption">{Yii::t('app', 'create_post')}</div>
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
                    <a href="#tab_general" data-toggle="tab"> {Yii::t('app', 'main_content')}</a>
                  </li>
                  <li>
                    <a href="#tab_category" data-toggle="tab"> {Yii::t('app', 'categories')} </a>
                  </li>
                  <li>
                    <a href="#tab_meta" data-toggle="tab"> {Yii::t('app', 'meta')} </a>
                  </li>
                </ul>
                <div class="tab-content">
                  <div class="tab-pane active" id="tab_general">
                    <div class="form-body">
                      {$form->field($model, 'title', [
                        'labelOptions' => ['class' => 'col-md-2 control-label'],
                        'inputOptions' => ['id' => 'title', 'class' => 'form-control'],
                        'template' => '{label}<div class="col-md-10">{input}{hint}{error}</div>'
                      ])->textInput()}
                      {$form->field($model, 'slug', [
                        'labelOptions' => ['class' => 'col-md-2 control-label'],
                        'inputOptions' => ['class' => 'slug form-control'],
                        'template' => '{label}<div class="col-md-10">{input}{hint}{error}</div>'
                      ])->textInput()}
                      {$form->field($model, 'excerpt', [
                        'labelOptions' => ['class' => 'col-md-2 control-label'],
                        'template' => '{label}<div class="col-md-10">{input}{hint}{error}</div>'
                      ])->textarea()}
                      {$form->field($model, 'content', [
                        'labelOptions' => ['class' => 'col-md-2 control-label'],
                        'inputOptions' => ['id' => 'content', 'class' => 'form-control'],
                        'template' => '{label}<div class="col-md-10">{input}{hint}{error}</div>'
                      ])->textarea()}
                      {$form->field($model, 'status', [
                        'labelOptions' => ['class' => 'col-md-2 control-label'],
                        'template' => '{label}<div class="col-md-10">{input}{hint}{error}</div>'
                      ])->radioList($model->getStatusList('%s<span></span>'), [
                        'class' => 'md-radio-list', 
                        'encode' => false , 
                        'itemOptions' => ['labelOptions' => ['class'=>'mt-radio', 'style' => 'display: block']]
                      ])->label('Status')}
                      <div class="form-group">
                        <label class="control-label col-md-2">{Yii::t('app', 'image')}</label>
                        <div class="col-md-10">
                            <div class="fileinput fileinput-new" data-provides="fileinput">
                                <div class="fileinput-preview thumbnail" data-trigger="fileinput" style="width: 150px; height: 150px;">
                                    <img src="" id="image" />
                                </div>
                                <div>
                                  <span class="help-block"> {Yii::t('app/error', 'image_size_at_least', ['size' => '940x630'])} </span>
                                  <span class="btn default btn-file">
                                    <span class="fileinput-new" id="upload-image"> {Yii::t('app', 'choose_image')} </span>
                                    {$form->field($model, 'image_id', [
                                      'inputOptions' => ['id' => 'image_id'], 
                                      'template' => '{input}', 
                                      'options' => ['tag' => null]
                                    ])->hiddenInput()->label(false)}
                                  </span>
                                  <a href="javascript:void(0)" onclick="removeMainImage()" class="btn red fileinput-exists" data-dismiss="fileinput"> {Yii::t('app', 'remove')} </a>
                                </div>
                            </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="tab-pane" id="tab_category">
                    <div class="form-body">
                      {$form->field($model, 'categories', [
                        'labelOptions' => ['class' => 'col-md-2 control-label'],
                        'template' => '{label}<div class="col-md-10">{input}{hint}{error}</div>'
                      ])->checkboxList($model->getCategories('%s<span></span>'), [
                        'class' => 'md-checkbox-list', 
                        'encode' => false , 
                        'itemOptions' => ['labelOptions' => ['class'=>'mt-checkbox', 'style' => 'display: block']]
                      ])->label('Categories')}
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
  </div>
  <!-- END CONTENT BODY -->
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
$('#title').slug();

//editor
editor = CKEDITOR.replace('content');
//editor.config.allowedContent = true;
editor.on('change', function() {editor.updateElement()});

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