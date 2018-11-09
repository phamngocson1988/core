{use class='yii\helpers\Html'}
{use class='yii\widgets\ActiveForm' type='block'}
{use class='common\widgets\TinyMce' type='block'}
{$this->registerJsFile('vendor/assets/global/plugins/jquery-repeater/jquery.repeater.js', ['depends' => '\backend\assets\AppAsset'])}
{$this->registerJsFile('vendor/assets/pages/scripts/form-repeater.min.js', ['depends' => '\backend\assets\AppAsset'])}
<!-- BEGIN PAGE BAR -->
<div class="page-bar">
  <ul class="page-breadcrumb">
    <li>
      <a href="/">{Yii::t('app', 'home')}</a>
      <i class="fa fa-circle"></i>
    </li>
    <li>
      <a href="{url route='game/index'}">{Yii::t('app', 'manage_games')}</a>
      <i class="fa fa-circle"></i>
    </li>
    <li>
      <span>{Yii::t('app', 'create_game')}</span>
    </li>
  </ul>
</div>
<!-- END PAGE BAR -->
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title">{Yii::t('app', 'create_game')}</h1>
<!-- END PAGE TITLE-->
<div class="row">
  {ActiveForm assign='form' options=['class' => 'form-horizontal form-row-seperated form']}
  <div class="col-md-12">
      <div class="portlet">
        <div class="portlet-title">
          <div class="actions btn-set">
            <a href="{$back}" class="btn default">
            <i class="fa fa-angle-left"></i> {Yii::t('app', 'back')}</a>
            <button type="submit" class="btn btn-success">
            <i class="fa fa-check"></i> {Yii::t('app', 'save')}
            </button>
          </div>
        </div>

        <!-- <div class="portlet-body">
          <div class="tabbable-bordered">
            <ul class="nav nav-tabs">
              <li class="active">
                <a href="#tab_general" data-toggle="tab"> {Yii::t('app', 'main_content')}</a>
              </li>
              <li>
                <a href="#tab_gallery" data-toggle="tab"> {Yii::t('app', 'gallery')} </a>
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
                  ])->widget(TinyMce::className(), [
                    'options' => ['rows' => 10]
                  ])}
                  <div class="form-group">
                    <label class="control-label col-md-2">{Yii::t('app', 'image')}</label>
                    <div class="col-md-10">
                        <div class="fileinput fileinput-new" data-provides="fileinput">
                            <div class="fileinput-preview thumbnail" data-trigger="fileinput" style="width: 150px; height: 150px;">
                                <img src="" id="image" />
                            </div>
                            <div>
                              <span class="help-block"> {Yii::t('app', 'image_size_at_least', ['size' => '940x630'])} </span>
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
              <div class="tab-pane" id="tab_gallery">
                <div id="tab_images_uploader_container" class="text-align-reverse margin-bottom-10">
                    <a class="btn btn-success" id="upload-gallery"><i class="fa fa-plus"></i> {Yii::t('app', 'choose_image')} </a>
                </div>
                <table class="table table-bordered table-hover">
                  <thead>
                    <tr role="row" class="heading">
                      <th width="20%"> {Yii::t('app', 'image')} </th>
                      <th width="40%"> {Yii::t('app', 'no')}</th>
                      <th width="40%"> {Yii::t('app', 'action')}</th>
                    </tr>
                  </thead>
                  <tbody>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div> -->
      </div>
      
  </div>
  <div class="col-md-8">
    <div class="portlet light bordered">
      <div class="portlet-title">
        <div class="caption">
          <i class="icon-speech font-green"></i>
          <span class="caption-subject bold font-green uppercase">User Profile</span>
        </div>
        <div class="actions">
          <a href="javascript:;" class="btn btn-circle btn-outline green">
          <i class="fa fa-pencil"></i> Edit </a>
          <a href="javascript:;" class="btn btn-circle blue-steel btn-outline">
          <i class="fa fa-plus"></i> Add </a>
          <a class="btn btn-circle btn-icon-only btn-default fullscreen" href="javascript:;"> </a>
        </div>
      </div>
      <div class="portlet-body">
        <div class="form-group">
          <label class="control-label">Name</label>
          <input type="text" placeholder="John Smith" class="form-control" /> 
        </div>
        <div class="form-group">
          <label class="control-label">E-mail</label>
          <input type="email" placeholder="john.smith@gmail.com" class="form-control" /> 
        </div>
        <h3 class="form-section">Basic validation States</h3>
        <div class="form-group mt-repeater">
          <div data-repeater-list="group-b">
            <div class="mt-repeater-item">
              <label class="control-label">Mobile Number</label>
              <input type="text" placeholder="+1 646 580 DEMO (6284)" class="form-control" /> 
            </div>
            <div data-repeater-item class="mt-repeater-item mt-overflow">
              <label class="control-label">Additional Contact Number</label>
              <div class="mt-repeater-cell">
                <input type="text" placeholder="+1 646 580 DEMO (6284)" class="form-control mt-repeater-input-inline" />
                <a href="javascript:;" data-repeater-delete class="btn btn-danger mt-repeater-delete mt-repeater-del-right mt-repeater-btn-inline">
                <i class="fa fa-close"></i>
                </a>
              </div>
            </div>
          </div>
          <a href="javascript:;" data-repeater-create class="btn btn-success mt-repeater-add">
          <i class="fa fa-plus"></i> Add new contact number</a>
        </div>
        <div class="form-group">
          <label class="control-label">Occupation</label>
          <input type="text" placeholder="Web Developer" class="form-control" /> 
        </div>
        <div class="form-group">
          <label class="control-label">About</label>
          <textarea class="form-control" rows="3" placeholder="We are KeenThemes!!!"></textarea>
        </div>
        <div class="form-group">
          <label class="control-label">Website Url</label>
          <input type="text" placeholder="http://www.mywebsite.com" class="form-control" /> 
        </div>
        <div class="margin-top-10">
          <a href="javascript:;" class="btn green">Save Changes </a>
          <a href="javascript:;" class="btn default">Cancel </a>
        </div>
      </div>
    </div>
  </div>
  <div class="col-md-4">
    <div class="portlet light bordered">
      <div class="portlet-title">
        <div class="caption">
          <i class="icon-speech font-green"></i>
          <span class="caption-subject bold font-green uppercase">User Profile</span>
        </div>
        <div class="actions">
          <a href="javascript:;" class="btn btn-circle btn-outline green">
          <i class="fa fa-pencil"></i> Edit </a>
          <a href="javascript:;" class="btn btn-circle blue-steel btn-outline">
          <i class="fa fa-plus"></i> Add </a>
          <a class="btn btn-circle btn-icon-only btn-default fullscreen" href="javascript:;"> </a>
        </div>
      </div>
      <div class="portlet-body">
        <div class="form-group">
          <label class="control-label">Name</label>
          <input type="text" placeholder="John Smith" class="form-control" /> 
        </div>
        <div class="form-group">
          <label class="control-label">E-mail</label>
          <input type="email" placeholder="john.smith@gmail.com" class="form-control" /> 
        </div>
        <div class="form-group mt-repeater">
          <div data-repeater-list="group-b">
            <div class="mt-repeater-item">
              <label class="control-label">Mobile Number</label>
              <input type="text" placeholder="+1 646 580 DEMO (6284)" class="form-control" /> 
            </div>
            <div data-repeater-item class="mt-repeater-item mt-overflow">
              <label class="control-label">Additional Contact Number</label>
              <div class="mt-repeater-cell">
                <input type="text" placeholder="+1 646 580 DEMO (6284)" class="form-control mt-repeater-input-inline" />
                <a href="javascript:;" data-repeater-delete class="btn btn-danger mt-repeater-delete mt-repeater-del-right mt-repeater-btn-inline">
                <i class="fa fa-close"></i>
                </a>
              </div>
            </div>
          </div>
          <a href="javascript:;" data-repeater-create class="btn btn-success mt-repeater-add">
          <i class="fa fa-plus"></i> Add new contact number</a>
        </div>
        <div class="form-group">
          <label class="control-label">Occupation</label>
          <input type="text" placeholder="Web Developer" class="form-control" /> 
        </div>
        <div class="form-group">
          <label class="control-label">About</label>
          <textarea class="form-control" rows="3" placeholder="We are KeenThemes!!!"></textarea>
        </div>
        <div class="form-group">
          <label class="control-label">Website Url</label>
          <input type="text" placeholder="http://www.mywebsite.com" class="form-control" /> 
        </div>
        <div class="margin-top-10">
          <a href="javascript:;" class="btn green">Save Changes </a>
          <a href="javascript:;" class="btn default">Cancel </a>
        </div>
      </div>
    </div>
  </div>
  {/ActiveForm}
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

// number format
$('input.number').number(true, 0);

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

// gallery
$("#upload-gallery").selectImage(manager, {
  type: 'multiple',
  callback: function(imgs) {
    $.each( imgs, function( key, img ) {
      var _html = "";
      _html += '<tr>';
      _html += '<td>';
      _html += '<a href="javascript:;" class="fancybox-button" data-rel="fancybox-button">';
      _html += '<img class="img-responsive" src="' + img.src + '" alt=""> </a>';
      _html += '<input type="hidden" name="CreateGameForm[gallery][]" value="' + img.id + '">';
      _html += '</td>';
      _html += '<td>';
      _html += '<a href="javascript:;" class="btn btn-icon-only green go-up"><i class="fa fa-arrow-up"></i></a>';
      _html += '<a href="javascript:;" class="btn btn-icon-only red go-down"><i class="fa fa-arrow-down"></i></a>';
      _html += '</td>';
      _html += '<td>';
      _html += '<a href="javascript:;" class="btn btn-default btn-sm remove"><i class="fa fa-times"></i> Remove </a>';                        
      _html += '</td>';
      _html += '</tr>';
      $('#tab_gallery').find('tbody').append(_html);
    });
  }
});
$("#tab_gallery").on('click', '.remove', function() {
  $(this).closest('tr').fadeOut(300, function(){ $(this).remove();});
})
{/literal}
{/registerJs}