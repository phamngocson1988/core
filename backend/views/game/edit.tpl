{use class='yii\helpers\Html'}
{use class='yii\widgets\ActiveForm' type='block'}
{use class='common\widgets\TinyMce' type='block'}
{use class='unclead\multipleinput\MultipleInput'}
{use class='common\widgets\ImageInputWidget'}
{use class='common\widgets\RadioListInput'}
{$this->registerCssFile('@web/vendor/assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.css', ['depends' => [\backend\assets\AppAsset::className()]])}
{$this->registerCssFile('@web/vendor/assets/pages/css/profile.min.css', ['depends' => [\backend\assets\AppAsset::className()]])}
{$this->registerJsFile('@web/vendor/assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.js', ['depends' => [\yii\web\JqueryAsset::className()]])}
{$this->registerJsFile('@web/vendor/assets/global/plugins/jquery.sparkline.min.js', ['depends' => [\yii\web\JqueryAsset::className()]])}
{$this->registerJsFile('@web/vendor/assets/pages/scripts/profile.min.js', ['depends' => [\yii\web\JqueryAsset::className()]])}
{$this->registerJsFile('@web/js/jquery.number.min.js', ['depends' => [\yii\web\JqueryAsset::className()]])}
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
              </ul>
            </div>
            <div class="portlet-body">
              <div class="tab-content">
                <div class="tab-pane active" id="tab_1_1">
                  {$form->field($model, 'title')->textInput()}
                  {$form->field($model, 'excerpt')->textarea()}
                  {$form->field($model, 'content')->widget(TinyMce::className(), ['options' => ['rows' => 10]])}
                </div>
                <div class="tab-pane" id="tab_1_3">
                  <a data-toggle="modal" class="btn btn-link" id="add_packages" href="#new-product-modal">{Yii::t('app', 'add_package')}</a>
                  <table class="table table-striped table-bordered table-hover table-checkable">
                    <thead>
                      <tr>
                        <th style="width: 10%;"> {Yii::t('app', 'image')} </th>
                        <th style="width: 20%;"> {Yii::t('app', 'title')} </th>
                        <th style="width: 10%;"> {Yii::t('app', 'price')} </th>
                        <th style="width: 10%;"> {Yii::t('app', 'gems')} </th>
                        <th style="width: 10%;"> {Yii::t('app', 'status')} </th>
                        <th style="width: 10%;" class="dt-center"> {Yii::t('app', 'actions')} </th>
                      </tr>
                    </thead>
                    <tbody>
                        {if (!$model->getGame()->products) }
                        <tr><td colspan="6">{Yii::t('app', 'no_data_found')}</td></tr>
                        {/if}
                        {foreach $model->getGame()->products as $key => $product}
                        <tr>
                          <td style="vertical-align: middle;"><img src="{$product->getImageUrl('50x50')}" width="50px;" /></td>
                          <td style="vertical-align: middle;">{$product->title}</td>
                          <td style="vertical-align: middle;">{$product->price}</td>
                          <td style="vertical-align: middle;">{$product->gems}</td>
                          <td style="vertical-align: middle;">{$product->status}</td>
                          <td style="vertical-align: middle;">
                              <a href='{url route="product/edit" id=$product->id}' class="btn btn-xs grey-salsa tooltips" data-container="body" data-original-title="{Yii::t('app', 'edit')}"><i class="fa fa-pencil"></i></a>
                              <a href='{url route="product/delete" id=$product->id}' class="btn btn-xs grey-salsa delete-action tooltips" data-container="body" data-original-title="{Yii::t('app', 'delete')}"><i class="fa fa-trash-o"></i></a>
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
      <div class="modal-body" id="create_product">
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
            {$newform->field($newProductModel, 'gems')}
          </div>
        </div>
        <div class="row">
          <div class="col-md-6">
            {$newform->field($newProductModel, 'image_id', [
              'options' => ['tag' => false, 'class' => 'profile-userpic'],
              'template' => '{input}{hint}{error}'
            ])->widget(ImageInputWidget::className(), [
              'template' => '<div class="profile-userpic">{image}{input}</div><div class="profile-userbuttons list-separated profile-stat">{choose_button}{cancel_button}</div>',
              'imageOptions' => ['class' => 'img-responsive', 'size' => '300x300'],
              'chooseButtonOptions' => ['tag' => 'span', 'options' => ['class' => 'btn btn-circle green btn-sm']],
              'cancelButtonOptions' => ['tag' => 'button', 'options' => ['class' => 'btn btn-circle red btn-sm']]
            ])->label(false)}
          </div>
          <div class="col-md-6">
            {$newform->field($newProductModel, 'status', [
              'options' => ['class' => 'list-separated profile-stat']
            ])->widget(RadioListInput::className(), [
              'items' => $newProductModel->getStatusList(),
              'options' => ['class' => 'mt-radio-list']
            ])}
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
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">{Yii::t('app', 'edit_package')}</h4>
      </div>
      {ActiveForm assign='editform' options=['id' => 'edit-product-form'] action='/product/edit'}
      <div class="modal-body" id="create_product">
      {$editform->field($editProductModel, 'id', ['template' => '{input}', 'options' => ['tag' => null]])->hiddenInput()}
      {$editform->field($editProductModel, 'game_id', ['template' => '{input}', 'options' => ['tag' => null]])->hiddenInput()}
        <div class="row">
          <div class="col-md-12">
            {$editform->field($editProductModel, 'title')}
          </div>
        </div>
        <div class="row">
          <div class="col-md-6">
            {$editform->field($editProductModel, 'price')}
          </div>
          <div class="col-md-6">
            {$editform->field($editProductModel, 'gems')}
          </div>
        </div>
        <div class="row">
          <div class="col-md-6">
            {$editform->field($editProductModel, 'image_id', [
              'options' => ['tag' => false, 'class' => 'profile-userpic'],
              'template' => '{input}{hint}{error}'
            ])->widget(ImageInputWidget::className(), [
              'template' => '<div class="profile-userpic">{image}{input}</div><div class="profile-userbuttons list-separated profile-stat">{choose_button}{cancel_button}</div>',
              'imageOptions' => ['class' => 'img-responsive', 'size' => '300x300'],
              'chooseButtonOptions' => ['tag' => 'span', 'options' => ['class' => 'btn btn-circle green btn-sm']],
              'cancelButtonOptions' => ['tag' => 'button', 'options' => ['class' => 'btn btn-circle red btn-sm']]
            ])->label(false)}
          </div>
          <div class="col-md-6">
            {$editform->field($editProductModel, 'status', [
              'options' => ['class' => 'list-separated profile-stat']
            ])->widget(RadioListInput::className(), [
              'items' => $editProductModel->getStatusList(),
              'options' => ['class' => 'mt-radio-list']
            ])}
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
<!-- Modal -->

{registerJs}
{literal}
// number format
$('input.number').number(true, 0);
var newform = new AjaxFormSubmit({element: '#add-product-form'});
newform.success = function(data, form) {
  $(form)[0].reset();
}
newform.error = function(errors) {
  console.log(errors);
}
{/literal}
{/registerJs}