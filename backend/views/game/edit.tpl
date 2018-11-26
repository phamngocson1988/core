{use class='yii\helpers\Html'}
{use class='yii\widgets\ActiveForm' type='block'}
{use class='common\widgets\TinyMce' type='block'}
{use class='unclead\multipleinput\MultipleInput'}
{use class='common\widgets\ImageInputWidget'}
{use class='common\widgets\MultipleImageInputWidget'}
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
      <span>{Yii::t('app', 'edit_game')}</span>
    </li>
  </ul>
</div>
<!-- END PAGE BAR -->
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title"> {Yii::t('app', 'edit_game')} </h1>
<!-- END PAGE TITLE-->
{ActiveForm assign='form' options=['class' => 'form-horizontal form-row-seperated form']}
{$form->field($model, 'id')->hiddenInput()->label(false)}
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
          'imageOptions' => ['class' => 'img-responsive'],
          'imageSrc' => $model->getImageUrl('150x150'),
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
                  <a href="#tab_1_2" data-toggle="tab">{Yii::t('app', 'meta')}</a>
                </li>
                <li>
                  <a href="#tab_1_3" data-toggle="tab">{Yii::t('app', 'products')}</a>
                </li>
                <li>
                  <a href="#tab_1_4" data-toggle="tab">{Yii::t('app', 'products')}</a>
                </li>
              </ul>
            </div>
            <div class="portlet-body">
              <div class="tab-content">
                <div class="tab-pane active" id="tab_1_1">
                  {$form->field($model, 'title')->textInput()}
                  {$form->field($model, 'content')->widget(TinyMce::className(), ['options' => ['rows' => 10]])}
                </div>
                <div class="tab-pane" id="tab_1_2">
                  {$form->field($model, 'meta_title')->textInput()}
                  {$form->field($model, 'meta_keyword')->textInput()}
                  {$form->field($model, 'meta_description')->textarea(['rows' => '5'])}
                </div>
                <div class="tab-pane" id="tab_1_3">
                  {$form->field($model, 'products')->widget(MultipleInput::className(), [
                    'columns' => [
                        [
                            'name'  => 'id',
                            'title' => 'ID',
                            'type' => 'hiddenInput'
                        ],
                        [
                            'name'  => 'game_id',
                            'title' => 'Game id',
                            'type' => 'hiddenInput'
                        ],
                        [
                            'name'  => 'title',
                            'title' => 'Title',
                            'enableError' => true,
                            'headerOptions' => [
                              'style' => 'width: 50%'
                            ]
                        ],
                        [
                            'name'  => 'price',
                            'title' => 'Price',
                            'enableError' => true,
                            'options' => [
                              'type' => 'number',
                              'class' => 'number'
                            ]
                        ],
                        [
                            'name'  => 'gems',
                            'title' => 'Gems',
                            'enableError' => true,
                            'options' => [
                              'type' => 'number',
                              'class' => 'number'
                            ]
                        ]
                    ]
                  ])->label(false)}
                </div>
                <div class="tab-pane" id="tab_1_4">
                  <!-- <div class="form-group">
                    <div class="row">
                      <div class="col-md-2">
                        <div class="thumbnail">
                          <img src="http://image.chuchu.com/4/150x150/hacker_hacker_human_-512.png" alt="Lights" style="width:100%">
                          <i class="glyphicon glyphicon-remove" style="position: absolute; top:0;right:15px"></i>
                        </div>
                      </div>
                      <div class="col-md-2">
                        <div class="thumbnail">
                          <img src="http://image.chuchu.com/4/150x150/hacker_hacker_human_-512.png" alt="Lights" style="width:100%">
                          <i class="glyphicon glyphicon-remove" style="position: absolute; top:0;right:15px"></i>
                        </div>
                      </div>
                      <div class="col-md-2">
                        <div class="thumbnail">
                          <img src="http://image.chuchu.com/4/150x150/hacker_hacker_human_-512.png" alt="Lights" style="width:100%">
                          <i class="glyphicon glyphicon-remove" style="position: absolute; top:0;right:15px"></i>
                        </div>
                      </div>
                      <div class="col-md-2">
                        <div class="thumbnail">
                          <img src="http://image.chuchu.com/4/150x150/hacker_hacker_human_-512.png" alt="Lights" style="width:100%">
                          <i class="glyphicon glyphicon-remove" style="position: absolute; top:0;right:15px"></i>
                        </div>
                      </div>
                      <div class="col-md-2">
                        <div class="thumbnail">
                          <img src="http://image.chuchu.com/4/150x150/hacker_hacker_human_-512.png" alt="Lights" style="width:100%">
                          <i class="glyphicon glyphicon-remove" style="position: absolute; top:0;right:15px"></i>
                        </div>
                      </div>
                    </div>
                  </div> -->
                  {$form->field($model, 'gallery', [
                    'options' => ['tag' => false, 'class' => 'profile-userpic'],
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

{registerJs}
{literal}
// number format
$('input.number').number(true, 0);
{/literal}
{/registerJs}