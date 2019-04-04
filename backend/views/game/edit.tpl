{use class='yii\helpers\Html'}
{use class='yii\widgets\ActiveForm' type='block'}
{use class='common\widgets\TinyMce' type='block'}
{use class='yii\widgets\Pjax' type='block'}
{use class='unclead\multipleinput\MultipleInput'}
{use class='common\widgets\ImageInputWidget'}
{use class='common\widgets\RadioListInput'}
{use class='common\widgets\MultipleImageInputWidget'}

{$this->registerCssFile('@web/vendor/assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.css', ['depends' => [\backend\assets\AppAsset::className()]])}
{$this->registerCssFile('@web/vendor/assets/pages/css/profile.min.css', ['depends' => [\backend\assets\AppAsset::className()]])}
{$this->registerJsFile('@web/vendor/assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.js', ['depends' => [\yii\web\JqueryAsset::className()]])}
{$this->registerJsFile('@web/vendor/assets/global/plugins/jquery.sparkline.min.js', ['depends' => [\yii\web\JqueryAsset::className()]])}
{$this->registerJsFile('@web/vendor/assets/pages/scripts/profile.min.js', ['depends' => [\yii\web\JqueryAsset::className()]])}
{$this->registerJsFile('@web/js/jquery.number.min.js', ['depends' => [\yii\web\JqueryAsset::className()]])}

{registerCss}
{literal}
.product-filter.active {
  color: #32c5d2;
}
{/literal}
{/registerCss}

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
<h1 class="page-title"> {Yii::t('app', 'create_game')} </h1>
<!-- END PAGE TITLE-->
{ActiveForm assign='form' options=['class' => 'form-horizontal form-row-seperated form']}
{$form->field($model, 'id', ['template' => '{input}'])->hiddenInput()}
<div class="row">
  <div class="col-md-12">
    <!-- BEGIN PROFILE SIDEBAR -->
    <div class="profile-sidebar">
      <!-- PORTLET MAIN -->
      <div class="portlet light">
        <!-- SIDEBAR USERPIC -->
        {$form->field($model, 'image_id', [
          'options' => ['tag' => false, 'class' => 'profile-userpic'],
          'template' => '{input}{hint}{error}'
        ])->widget(ImageInputWidget::className(), [
          'template' => '<div class="profile-userpic">{image}{input}</div><div class="profile-userbuttons list-separated profile-stat">{choose_button}{cancel_button}</div>',
          'imageOptions' => ['class' => 'img-responsive', 'size' => '300x300'],
          'chooseButtonOptions' => ['tag' => 'span', 'options' => ['class' => 'btn btn-circle green btn-sm']],
          'cancelButtonOptions' => ['tag' => 'button', 'options' => ['class' => 'btn btn-circle red btn-sm']]
        ])->label(false)}

        {$form->field($model, 'status', [
          'options' => ['class' => 'list-separated profile-stat']
        ])->widget(RadioListInput::className(), [
          'items' => $model->getStatusList(),
          'options' => ['class' => 'mt-radio-list']
        ])}

        {Html::submitButton(Yii::t('app', 'save'), ['class' => 'btn green'])}
        {Html::resetButton(Yii::t('app', 'cancel'), ['class' => 'btn default'])}
        <!-- END MENU -->
      </div>
      <!-- END PORTLET MAIN -->
    </div>
    <!-- END BEGIN PROFILE SIDEBAR -->
    <!-- BEGIN PROFILE CONTENT -->
    <div class="profile-content">
      <div class="row">
        <div class="col-md-12">
          <div class="portlet light ">
            <div class="portlet-title tabbable-line">
              <ul class="nav nav-tabs">
                <li class="active">
                  <a href="#tab_1_1" data-toggle="tab">{Yii::t('app', 'main_content')}</a>
                </li>
                <li>
                  <a href="#tab_1_3" data-toggle="tab">{Yii::t('app', 'packages')}</a>
                </li>
                <li>
                  <a href="#images" data-toggle="tab">Hình ảnh</a>
                </li>
              </ul>
            </div>
            <div class="portlet-body">
              <div class="tab-content">
                <div class="tab-pane active" id="tab_1_1">
                  {$form->field($model, 'title')->textInput()}
                  {$form->field($model, 'excerpt')->textarea()}
                  {$form->field($model, 'unit_name')->textInput()}
                  {$form->field($model, 'content')->widget(TinyMce::className(), ['options' => ['rows' => 10]])}
                </div>
                <div class="tab-pane" id="tab_1_3">
                  <div style="margin-bottom: 10px;">
                    <a class="btn green" data-toggle="modal" id="add_packages" href="#new-product-modal">{Yii::t('app', 'add_new')}</a>
                    <a class="btn btn-link product-filter active" id="refresh_package_list" href="{url route='product/index' game_id=$model->id}">{Yii::t('app', 'all')}</a>
                    <a class="btn btn-link product-filter" id="refresh_package_list" href="{url route='product/index' game_id=$model->id status=Y}">{Yii::t('app', 'visible')}</a>
                    <a class="btn btn-link product-filter" id="refresh_package_list" href="{url route='product/index' game_id=$model->id status=N}">{Yii::t('app', 'disable')}</a>
                    <a class="btn btn-link product-filter" id="refresh_package_list" href="{url route='product/index' game_id=$model->id status=D}">{Yii::t('app', 'delete')}</a>

                  </div>


                  {Pjax enablePushState=false enableReplaceState=false linkSelector='.product-filter'}
                  
                  {/Pjax}
                </div>
                <div class="tab-pane" id="images">
                  {$form->field($model, 'gallery', [
                    'template' => '{input}{hint}{error}'
                  ])->widget(MultipleImageInputWidget::className(), [
                  ])->label(false)}
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- END PROFILE CONTENT -->
  </div>
</div>
{/ActiveForm}
<!-- Modal -->
<div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" id="new-product-modal">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">{Yii::t('app', 'create_package')}</h4>
      </div>
      {ActiveForm assign='newform' options=['id' => 'add-product-form'] action='/product/create'}
      <div class="modal-body">
      {$newform->field($newProductModel, 'game_id', ['template' => '{input}', 'options' => ['tag' => null]])->hiddenInput()}
        <div class="row">
          <div class="col-md-12">
            {$newform->field($newProductModel, 'title')}
          </div>
        </div>
        <div class="row">
          <div class="col-md-6">
            {$newform->field($newProductModel, 'price')}
          </div>
          <div class="col-md-6">
            {$newform->field($newProductModel, 'unit')}
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">{Yii::t('app', 'cancel')}</button>
        <button type="submit" class="btn btn-primary">{Yii::t('app', 'submit')}</button>
      </div>
      {/ActiveForm}
    </div>
  </div>
</div>
<div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" id="edit-product-modal">
</div>
<!-- Modal -->

{registerJs}
{literal}
$('.product-filter.active').click();

var newform = new AjaxFormSubmit({element: '#add-product-form'});
newform.success = function(data, form) {
  $(form)[0].reset();
  $('#new-product-modal').modal('hide');
  $('.product-filter.active').click();
}
newform.error = function(errors) {
  console.log(errors);
}

// Edit product form
$('body').on('click', '.edit-product', function(e) {
  e.preventDefault();
  $('#edit-product-modal').load( $(this).attr('href') );
  $('#edit-product-modal').modal('show');
});

$('body').on('submit', '.edit-product-form', function(e) {
    e.preventDefault();
    e.stopImmediatePropagation();
    var form = $(this);
    $.ajax({
        url: form.attr('action'),
        type: form.attr('method'),
        dataType : 'json',
        data: form.serialize(),
        success: function (result, textStatus, jqXHR) {
          console.log(result);
          if (result.status == true) {
            $('#edit-product-modal').modal('hide');
            $('.product-filter.active').click();
          }
        },
    });
    return false;
});

$('.product-filter').on('click', function(e){
  $('.product-filter').removeClass('active');
  $(this).addClass('active');
})
{/literal}
{/registerJs}