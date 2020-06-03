{use class='yii\helpers\Html'}
{use class='yii\widgets\ActiveForm' type='block'}
{use class='common\widgets\TinyMce' type='block'}
{use class='unclead\multipleinput\MultipleInput'}
{use class='backend\models\Game'}
{use class='common\widgets\ImageInputWidget'}
{use class='common\widgets\RadioListInput'}
{use class='common\widgets\CheckboxInput'}
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
          'template' => '<div class="">{image}{input}</div><div class="profile-userbuttons list-separated profile-stat">{choose_button}{cancel_button}</div>',
          'imageOptions' => ['class' => 'img-responsive'],
          'chooseButtonOptions' => ['tag' => 'span', 'options' => ['class' => 'btn btn-circle green btn-sm']],
          'cancelButtonOptions' => ['tag' => 'button', 'options' => ['class' => 'btn btn-circle red btn-sm']]
        ])->label(false)}

        {$form->field($model, 'status', [
          'options' => ['class' => 'list-separated profile-stat']
        ])->widget(RadioListInput::className(), [
          'items' => [Game::STATUS_INVISIBLE => 'Invisible', Game::STATUS_VISIBLE => 'Visible'],
          'options' => ['class' => 'mt-radio-list']
        ])}

        {$form->field($model, 'pin', [
          'options' => ['class' => 'list-separated profile-stat']
        ])->widget(CheckboxInput::className())->label(false)}

        {$form->field($model, 'soldout', [
          'options' => ['class' => 'list-separated profile-stat']
        ])->widget(CheckboxInput::className())->label(false)}

        {if $app->user->can('orderteam')}
        {$form->field($model, 'average_speed', [
          'options' => ['class' => 'list-separated profile-stat']
        ])->textInput()}

        {$form->field($model, 'number_supplier', [
          'options' => ['class' => 'list-separated profile-stat']
        ])->textInput()}

        {$form->field($model, 'remark', [
          'options' => ['class' => 'list-separated profile-stat'],
          'inputOptions' => ['style' => 'resize: vertical', 'class' => 'form-control']
        ])->textArea()}
        {/if}
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
                  <a href="#tab_1_2" data-toggle="tab">Meta data</a>
                </li>
                <li>
                  <a href="#tab_1_3" data-toggle="tab">Danh mục game</a>
                </li>
                <li>
                  <a href="#tab_1_4" data-toggle="tab">Đặc tính</a>
                </li>
              </ul>
            </div>
            <div class="portlet-body">
              <div class="tab-content">
                <div class="tab-pane active" id="tab_1_1">
                  {$form->field($model, 'title')->textInput()}
                  {$form->field($model, 'original_price')->textInput()}
                  {$form->field($model, 'pack')->textInput()}
                  {$form->field($model, 'unit_name')->textInput()}
                  {$form->field($model, 'content')->widget(TinyMce::className(), ['options' => ['rows' => 30]])}
                  {$form->field($model, 'google_ads')->widget(TinyMce::className(), ['options' => ['rows' => 30]])}
                </div>
                <div class="tab-pane" id="tab_1_2">
                  {$form->field($model, 'meta_title')->textInput()}
                  {$form->field($model, 'meta_keyword')->textInput()}
                  {$form->field($model, 'meta_description')->textInput()}
                </div>
                <div class="tab-pane" id="tab_1_3">
                  {$form->field($model, 'categories', [
                    'labelOptions' => ['class' => 'col-md-2 control-label'],
                    'template' => '{label}<div class="col-md-10">{input}{hint}{error}</div>'
                  ])->checkboxList($model->getCategories('%s<span></span>'), [
                    'class' => 'md-checkbox-list', 
                    'encode' => false , 
                    'itemOptions' => ['labelOptions' => ['class'=>'mt-checkbox', 'style' => 'display: block']]
                  ])->label('Categories')}
                </div>
                <div class="tab-pane" id="tab_1_4">
                  {$form->field($model, 'hot_deal', [
                    'labelOptions' => ['class' => 'col-md-2 control-label'],
                    'template' => '{label}<div class="col-md-10">{input}{hint}{error}</div>'
                  ])->widget(CheckboxInput::className())->label('')}

                  {$form->field($model, 'new_trending', [
                    'labelOptions' => ['class' => 'col-md-2 control-label'],
                    'template' => '{label}<div class="col-md-10">{input}{hint}{error}</div>'
                  ])->widget(CheckboxInput::className())->label('')}

                  {$form->field($model, 'top_grossing', [
                    'labelOptions' => ['class' => 'col-md-2 control-label'],
                    'template' => '{label}<div class="col-md-10">{input}{hint}{error}</div>'
                  ])->widget(CheckboxInput::className())->label('')}

                  {$form->field($model, 'back_to_stock', [
                    'labelOptions' => ['class' => 'col-md-2 control-label'],
                    'template' => '{label}<div class="col-md-10">{input}{hint}{error}</div>'
                  ])->widget(CheckboxInput::className())->label('')}
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

{registerJs}
{literal}
// number format
$('input.number').number(true, 0);
{/literal}
{/registerJs}