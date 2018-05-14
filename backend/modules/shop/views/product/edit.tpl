{use class='yii\helpers\Html'}
{use class='yii\widgets\ActiveForm' type='block'}
{use class='common\widgets\TinyMce' type='block'}
<!-- BEGIN PAGE BAR -->
<div class="page-bar">
  <ul class="page-breadcrumb">
    <li>
      <a href="/">{Yii::t('module.shop', 'home')}</a>
      <i class="fa fa-circle"></i>
    </li>
    <li>
      <a href="{url route='product/index'}">{Yii::t('module.shop', 'manage_products')}</a>
      <i class="fa fa-circle"></i>
    </li>
    <li>
      <span>{Yii::t('module.shop', 'edit_product')}</span>
    </li>
  </ul>
</div>
<!-- END PAGE BAR -->
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title">{Yii::t('module.shop', 'edit_product')}</h1>
<!-- END PAGE TITLE-->
<div class="row">
  <div class="col-md-12">
    {ActiveForm assign='form' options=['class' => 'form-horizontal form-row-seperated']}
      {$form->field($model, 'id', [
        'template' => '{input}'
      ])->hiddenInput()}
      <div class="portlet">
        <div class="portlet-title">
          <div class="caption">{Yii::t('module.shop', 'edit_product')}</div>
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
                <a href="#tab_price" data-toggle="tab"> {Yii::t('module.shop', 'prices')} </a>
              </li>
              <li>
                <a href="#tab_category" data-toggle="tab"> {Yii::t('module.shop', 'product_categories')} </a>
              </li>
              <li>
                <a href="#tab_meta" data-toggle="tab"> {Yii::t('module.shop', 'meta')} </a>
              </li>
              <li>
                <a href="#tab_gallery" data-toggle="tab"> Gallery </a>
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
                    <label class="control-label col-md-2">{Yii::t('module.shop', 'image')}</label>
                    <div class="col-md-10">
                        <div class="fileinput fileinput-new" data-provides="fileinput">
                            <div class="fileinput-preview thumbnail" data-trigger="fileinput" style="width: 150px; height: 150px;">
                                <img src="{$model->getImageUrl('150x150')}" id="image" />
                            </div>
                            <div>
                              <span class="help-block"> {Yii::t('module.shop', 'image_size_at_least', ['size' => '940x630'])} </span>
                              <span class="btn default btn-file">
                                <span class="fileinput-new" id="upload-image"> Choose image </span>
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
              <div class="tab-pane" id="tab_price">
                <div class="form-body">
                  {$form->field($model, 'price', [
                    'labelOptions' => ['class' => 'col-md-2 control-label'],
                    'inputOptions' => ['class' => 'form-control number'],
                    'template' => '{label}<div class="col-md-6">{input}{hint}{error}</div>'
                  ])->textInput()}
                  {$form->field($model, 'sale_price', [
                    'labelOptions' => ['class' => 'col-md-2 control-label'],
                    'inputOptions' => ['class' => 'form-control number'],
                    'template' => '{label}<div class="col-md-6">{input}{hint}{error}</div>'
                  ])->textInput()}
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
              <div class="tab-pane" id="tab_gallery">
                <div id="tab_images_uploader_container" class="text-align-reverse margin-bottom-10">
                    <a class="btn btn-success" id="upload-gallery"><i class="fa fa-plus"></i> Select Files </a>
                </div>
                <table class="table table-bordered table-hover">
                  <thead>
                    <tr role="row" class="heading">
                      <th width="20%"> {Yii::t('module.shop', 'image')}</th>
                      <th width="40%"> Order</th>
                      <th width="40%"> Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    {foreach $model->getGalleryImages() as $imageObject}
                    <tr>
                      <td>
                        <a href="{$imageObject->getUrl()}" class="fancybox-button" data-rel="fancybox-button">
                        <img class="img-responsive" src="{$imageObject->getUrl('150x150')}" alt=""> </a>
                        <input type="hidden" name="EditProductForm[gallery][]" value="{$imageObject->id}">
                      </td>
                      <td>
                        <a href="javascript:;" class="btn btn-icon-only green go-up"><i class="fa fa-arrow-up"></i></a>
                        <a href="javascript:;" class="btn btn-icon-only red go-down"><i class="fa fa-arrow-down"></i></a>
                      </td>
                      <td>
                        <a href="javascript:;" class="btn btn-default btn-sm remove">
                        <i class="fa fa-times"></i> {Yii::t('module.shop', 'remove')} </a>
                      </td>
                    </tr>
                    {/foreach}
                  </tbody>
                </table>
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
      _html += '<input type="hidden" name="EditProductForm[gallery][]" value="' + img.id + '">';
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