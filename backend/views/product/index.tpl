{use class='yii\widgets\ActiveForm' type='block'}
{use class='yii\widgets\Pjax' type='block'}
{$this->registerJsFile('@web/vendor/assets/global/plugins/jquery-repeater/jquery.repeater.js', ['depends' => [\backend\assets\AppAsset::className()]])}
{$this->registerJsFile('@web/vendor/assets/pages/scripts/form-repeater.min.js', ['depends' => [\backend\assets\AppAsset::className()]])}
<!-- BEGIN PAGE BAR -->
<div class="page-bar">
  <ul class="page-breadcrumb">
    <li>
      <a href="/">{Yii::t('app', 'home')}</a>
      <i class="fa fa-circle"></i>
    </li>
    <li>
      <span>{Yii::t('app', 'manage_posts')}</span>
    </li>
  </ul>
</div>
<!-- END PAGE BAR -->
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title">{Yii::t('app', 'manage_posts')}</h1>
<!-- END PAGE TITLE-->
<div class="row">
  <div class="col-md-12">
    <!-- BEGIN EXAMPLE TABLE PORTLET-->
    {foreach $editProductForms as $editProductForm}
    {ActiveForm assign='form' action=['product/edit', 'id' => $editProductForm->id] options=['class' => 'form-horizontal form-row-seperated']}
      {$form->field($editProductForm, 'id', ['template' => '{input}', 'options' => ['tag' => null]])->hiddenInput(['value' => $editProductForm->id])}
      {$form->field($editProductForm, 'game_id', ['template' => '{input}', 'options' => ['tag' => null]])->hiddenInput(['value' => $editProductForm->game_id])}
    <div class="portlet box blue-hoki edit-product-form">
      <div class="portlet-title">
        <div class="caption">
          <i class="fa fa-gift"></i>{$editProductForm->title}
        </div>
        <div class="tools">
          <a href="" class="collapse" data-original-title="" title=""> </a>
          <a href="{url route='product/delete' id=$editProductForm->id}" class="remove" data-original-title="" title=""> </a>
        </div>
      </div>
      <div class="portlet-body form">
        <div class="form-body">
          <div class="row">
            <div class="col-md-3">
              <div class="form-group">
                <div class="fileinput fileinput-new" data-provides="fileinput">
                  <div class="fileinput-preview thumbnail" data-trigger="fileinput" style="width: 150px; height: 150px;">
                    <img src="{$editProductForm->getImageUrl('150x150')}" width="150" height="150" />
                  </div>
                  <div>
                    <span class="help-block"> {Yii::t('app', 'image_size_at_least', ['size' => '940x630'])} </span>
                    <span class="btn default btn-file">
                      <span class="fileinput-new product-image"> {Yii::t('app', 'choose_image')} </span>
                      {$form->field($editProductForm, 'image_id', [
                        'template' => '{input}', 
                        'options' => ['tag' => null],
                        'inputOptions' => ['class' => 'product-image_id']
                      ])->hiddenInput()->label(false)}
                    </span>
                    <a href="javascript:void(0)" class="btn red fileinput-exists remove-image" data-dismiss="fileinput"> {Yii::t('app', 'remove')} </a>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-9">
              <div class="row">
              {$form->field($editProductForm, 'title', [
                'options' => ['class' => 'form-group col-md-6'],
                'labelOptions' => ['class' => 'col-md-4 control-label'],
                'template' => '{label}<div class="col-md-8">{input}{hint}{error}</div>',
                'inputOptions' => ['class' => 'form-control product-title']
              ])->textInput()}
              {$form->field($editProductForm, 'status', [
                'options' => ['class' => 'form-group col-md-6'],
                'labelOptions' => ['class' => 'col-md-4 control-label'],
                'template' => '{label}<div class="col-md-8">{input}{hint}{error}</div>',
                'inputOptions' => ['class' => 'form-control product-status']
              ])->dropDownList($editProductForm->getStatusList())}
              </div>
              <div class="row">
              {$form->field($editProductForm, 'price', [
                'options' => ['class' => 'form-group col-md-6'],
                'labelOptions' => ['class' => 'col-md-4 control-label'],
                'template' => '{label}<div class="col-md-8">{input}{hint}{error}</div>',                                
                'inputOptions' => ['class' => 'form-control product-price']
              ])->textInput()}
              {$form->field($editProductForm, 'gems', [
                'options' => ['class' => 'form-group col-md-6'],
                'labelOptions' => ['class' => 'col-md-4 control-label'],
                'template' => '{label}<div class="col-md-8">{input}{hint}{error}</div>',
                'inputOptions' => ['class' => 'form-control product-gems']
              ])->textInput()}
              </div>
            </div>
          </div>
        </div>
        <div class="form-actions">
          <div class="row">
            <div class="col-md-offset-3 col-md-9">
              <button type="submit" class="btn green">{Yii::t('app', 'submit')}</button>
              <button type="button" class="btn default">{Yii::t('app', 'cancel')}</button>
            </div>
          </div>
        </div>
      </div>
    </div>
    {/ActiveForm}
    {/foreach}
    <!-- END EXAMPLE TABLE PORTLET-->
  </div>
</div>


{registerJs}
{literal}

// image
var manager = new ImageManager();
$(".product-image").selectImage(manager, {
  callback: function(img, e) {
    var thumb = img.src;
    var id = img.id;
    var image = e.closest('.form-group').find('img');
    var input = e.closest('.form-group').find('[type=hidden]');
    image.attr('src', thumb).removeClass('hide');
    input.val(id);    
  }
});
$(".remove-image").on('click', function(e){
  e.preventDefault();
  $(this).closest('form').find('img').attr('src', '');
  $(this).closest('form').find('input[type="hidden"]').val('');
})

// Edit package
var editForm = AjaxFormSubmit({element:'form'});
editForm.validate = function(form) {
  if (form.find('.has-error').length) {
    return false;
  }
  return true;
}
editForm.error = function (errors) {
  $.blockUI({ message: "Errors occurred." }); 
}
editForm.success = function (data) {
  $(this.options.element).find(".caption").html('<i class="fa fa-gift"></i>' + data.model.title);
}
editForm.beforeSend = function (element) {
  App.blockUI({target: element, animate: true});
}
editForm.complete = function(element) {
  App.unblockUI(element);
}

{/literal}
{/registerJs}
