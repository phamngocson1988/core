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
  <div class="col-md-8">
    <div class="portlet light bordered">
      <div class="portlet-title">
        <div class="caption">
          <span class="caption-subject bold font-green uppercase">{Yii::t('app', 'main_content')}</span>
        </div>
      </div>
      <div class="portlet-body">
        {$form->field($model, 'title')->textInput(['id' => title])}
        {$form->field($model, 'slug')->textInput(['readonly' => 'true', 'class' => 'slug form-control'])}
        {$form->field($model, 'content')->widget(TinyMce::className(), ['options' => ['rows' => 10]])}
        <h3 class="form-section">{Yii::t('app', 'meta')}</h3>
        {$form->field($model, 'meta_title')->textInput()}
        {$form->field($model, 'meta_keyword')->textInput()}
        {$form->field($model, 'meta_description')->textInput()}
      </div>
    </div>
    <div class="portlet light bordered">
      <div class="portlet-title">
        <div class="caption">
          <span class="caption-subject bold font-green uppercase">{Yii::t('app', 'packages')}</span>
        </div>
      </div>
      <div class="portlet-body">
        <div class="form-group">
          <div class="col-md-12">
            <div class="mt-repeater">
              <div data-repeater-list="packages">
                {foreach $packages as $package}
                <div data-repeater-item class="row">
                  {$form->field($package, 'title', [
                    'options' => ['class' => 'col-md-6']
                  ])->textInput()}
                  {$form->field($package, 'gems', [
                    'options' => ['class' => 'col-md-2']
                  ])->textInput()}
                  {$form->field($package, 'price', [
                    'options' => ['class' => 'col-md-2']
                  ])->textInput()}
                </div>
                {/foreach}
              </div>
              <hr>
              <a href="javascript:;" data-repeater-create class="btn btn-info mt-repeater-add">
              <i class="fa fa-plus"></i> Add Variation</a>
              <br>
              <br> 
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-md-4">
    <div class="portlet light bordered">
      <div class="portlet-title">
        <div class="caption">
          <span class="caption-subject bold font-green uppercase">User Profile</span>
        </div>
      </div>
      <div class="portlet-body">
        {$form->field($model, 'status', [
        ])->radioList(['Y' => 'Enable<span></span>', 'N' => 'Disable<span></span>'], [
          'class' => 'mt-radio-inline', 
          'encode' => false , 
          'itemOptions' => ['labelOptions' => ['class'=>'mt-radio']]
        ])}
        <hr>
        <div class="form-group">
          <label class="control-label">{Yii::t('app', 'image')}</label>
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
        <div class="margin-top-10">
          <button class="btn green">Save Changes </button>
          <button class="btn default">Cancel </button>
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