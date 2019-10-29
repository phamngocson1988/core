{use class='yii\helpers\Html'}
{use class='yii\widgets\ActiveForm' type='block'}
{use class='common\widgets\TinyMce' type='block'}
{use class='yii\widgets\Pjax' type='block'}
{use class='unclead\multipleinput\MultipleInput'}
{use class='common\widgets\ImageInputWidget'}
{use class='common\widgets\RadioListInput'}
{use class='common\widgets\CheckboxInput'}
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

        {$form->field($model, 'pin', [
          'options' => ['class' => 'list-separated profile-stat']
        ])->widget(CheckboxInput::className())->label(false)}

        {$form->field($model, 'soldout', [
          'options' => ['class' => 'list-separated profile-stat']
        ])->widget(CheckboxInput::className())->label(false)}

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
                  <a href="#main" data-toggle="tab">{Yii::t('app', 'main_content')}</a>
                </li>
                <li>
                  <a href="#tab_1_2" data-toggle="tab">Meta data</a>
                </li>
              </ul>
            </div>
            <div class="portlet-body">
              <div class="tab-content">
                <div class="tab-pane active" id="main">
                  {$form->field($model, 'title')->textInput()}
                  {$form->field($model, 'original_price')->textInput()}
                  {$form->field($model, 'pack')->textInput()}
                  {$form->field($model, 'unit_name')->textInput()}
                  {$form->field($model, 'content')->widget(TinyMce::className(), ['options' => ['rows' => 30]])}
                </div>
                <div class="tab-pane" id="tab_1_2">
                  {$form->field($model, 'meta_title')->textInput()}
                  {$form->field($model, 'meta_keyword')->textInput()}
                  {$form->field($model, 'meta_description')->textInput()}
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- END PROFILE CONTENT -->
    </div>
  </div>
</div>
{/ActiveForm}

{registerJs}
{literal}
var game_id = '{/literal}{$model->id}{literal}';
var newform = new AjaxFormSubmit({element: '#add-product-form'});
newform.success = function(data, form) {
  $(form)[0].reset();
  $('#new-product-modal').modal('hide');
  console.log(data);
  loadProducts(game_id);
}
newform.error = function(errors) {
  console.log(errors);
}

$('html').on('click', '.actions>a.delete-action', function(e) {
  e.preventDefault();
  e.stopImmediatePropagation();
  if (!window.confirm('Bạn có muốn xóa gói giá game này không?')) {
    return;
  }
  var element = this;
  $.ajax({
      url: $(this).attr('href'),
      type: 'GET',
      dataType : 'json',
      success: function (result, textStatus, jqXHR) {
        if (result.status == false) {
          alert('Có lỗi xảy ra');
          return false;
        } else {
          loadProducts(game_id);
        }
      },
  });
  return false;
});

function loadProducts(id) {
  $( "#products" ).load( '/game/products?id=' + id );
}
{/literal}
{/registerJs}