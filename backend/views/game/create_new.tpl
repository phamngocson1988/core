{use class='yii\helpers\Html'}
{use class='yii\widgets\ActiveForm' type='block'}
{use class='common\widgets\TinyMce' type='block'}
{use class='unclead\multipleinput\MultipleInput'}
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
      <a href="index.html">Home</a>
      <i class="fa fa-circle"></i>
    </li>
    <li>
      <span>User</span>
    </li>
  </ul>
  <div class="page-toolbar">
    <div class="btn-group pull-right">
      <button type="button" class="btn green btn-sm btn-outline dropdown-toggle" data-toggle="dropdown"> Actions
      <i class="fa fa-angle-down"></i>
      </button>
    </div>
  </div>
</div>
<!-- END PAGE BAR -->
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title"> New User Profile | Account
  <small>user account page</small>
</h1>
<!-- END PAGE TITLE-->
{ActiveForm assign='form' options=['class' => 'form-horizontal form-row-seperated form']}
<div class="row">
  <div class="col-md-12">
    <!-- BEGIN PROFILE SIDEBAR -->
    <div class="profile-sidebar">
      <!-- PORTLET MAIN -->
      <div class="portlet light profile-sidebar-portlet ">
        <!-- SIDEBAR USERPIC -->
        {$form->field($model, 'image_id', [
          'options' => ['tag' => false, 'class' => 'profile-userpic'],
          'template' => '{input}{hint}{error}'
        ])->widget(common\widgets\ImageInputWidget::className(), [
          'template' => '<div class="profile-userpic">{image}{input}</div><div class="profile-userbuttons">{choose_button}{cancel_button}</div>',
          'imageOptions' => ['class' => 'img-responsive'],
          'chooseButtonOptions' => ['tag' => 'span', 'options' => ['class' => 'btn btn-circle green btn-sm']],
          'cancelButtonOptions' => ['tag' => 'button', 'options' => ['class' => 'btn btn-circle red btn-sm']]
        ])->label(false)}

        <!-- END SIDEBAR BUTTONS -->
        <!-- SIDEBAR MENU -->
        <div class="profile-usermenu">
          <ul class="nav">
            <li>
              <a href="page_user_profile_1.html">
              <i class="icon-home"></i> Overview </a>
            </li>
            <li class="active">
              <a href="page_user_profile_1_account.html">
              <i class="icon-settings"></i> Account Settings </a>
            </li>
            <li>
              <a href="page_user_profile_1_help.html">
              <i class="icon-info"></i> Help </a>
            </li>
          </ul>
        </div>
        <!-- END MENU -->
      </div>
      <!-- END PORTLET MAIN -->
      <!-- PORTLET MAIN -->
      <div class="portlet light ">
        <!-- STAT -->
        <div class="row list-separated profile-stat">
          <div class="col-md-4 col-sm-4 col-xs-6">
            <div class="uppercase profile-stat-title"> 37 </div>
            <div class="uppercase profile-stat-text"> Projects </div>
          </div>
          <div class="col-md-4 col-sm-4 col-xs-6">
            <div class="uppercase profile-stat-title"> 51 </div>
            <div class="uppercase profile-stat-text"> Tasks </div>
          </div>
          <div class="col-md-4 col-sm-4 col-xs-6">
            <div class="uppercase profile-stat-title"> 61 </div>
            <div class="uppercase profile-stat-text"> Uploads </div>
          </div>
        </div>
        <!-- END STAT -->
        <div>
          <h4 class="profile-desc-title">About Marcus Doe</h4>
          <span class="profile-desc-text"> Lorem ipsum dolor sit amet diam nonummy nibh dolore. </span>
          <div class="margin-top-20 profile-desc-link">
            <i class="fa fa-globe"></i>
            <a href="http://www.keenthemes.com">www.keenthemes.com</a>
          </div>
          <div class="margin-top-20 profile-desc-link">
            <i class="fa fa-twitter"></i>
            <a href="http://www.twitter.com/keenthemes/">@keenthemes</a>
          </div>
          <div class="margin-top-20 profile-desc-link">
            <i class="fa fa-facebook"></i>
            <a href="http://www.facebook.com/keenthemes/">keenthemes</a>
          </div>
        </div>
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
              <div class="caption caption-md">
                <i class="icon-globe theme-font hide"></i>
                <span class="caption-subject font-blue-madison bold uppercase">Profile Account</span>
              </div>
              <ul class="nav nav-tabs">
                <li class="active">
                  <a href="#tab_1_1" data-toggle="tab">{Yii::t('app', 'general')}</a>
                </li>
                <li>
                  <a href="#tab_1_2" data-toggle="tab">{Yii::t('app', 'meta')}</a>
                </li>
                <li>
                  <a href="#tab_1_3" data-toggle="tab">{Yii::t('app', 'products')}</a>
                </li>
              </ul>
            </div>
            <div class="portlet-body">
              <div class="tab-content">
                <div class="tab-pane active" id="tab_1_1">
                  {$form->field($model, 'title')->textInput()}
                  {$form->field($model, 'content')->widget(TinyMce::className(), ['options' => ['rows' => 10]])}
                  <div class="margiv-top-10">
                    <input type="submit" class="btn green" value="Submit" />
                    <a href="javascript:;" class="btn default"> Cancel </a>
                  </div>
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
                            'enableError' => true
                        ],
                        [
                            'name'  => 'gems',
                            'title' => 'Gems',
                            'enableError' => true
                        ]
                    ]
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