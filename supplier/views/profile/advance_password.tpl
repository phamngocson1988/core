{use class='yii\widgets\ActiveForm' type='block'}
<!-- BEGIN PAGE BAR -->
<div class="page-bar">
  <ul class="page-breadcrumb">
    <li>
      <a href="/">{Yii::t('app', 'home')}</a>
      <i class="fa fa-circle"></i>
    </li>
    <li>
      <span>{Yii::t('app', 'user')}</span>
    </li>
  </ul>
</div>
<!-- END PAGE BAR -->
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title"> {Yii::t('app', 'user_profile')}</h1>
<!-- END PAGE TITLE-->
<!-- END PAGE HEADER-->
<div class="row">
  <div class="col-md-12">
    <!-- BEGIN PROFILE SIDEBAR -->
    <div class="profile-sidebar">
      <!-- PORTLET MAIN -->
      <div class="portlet light profile-sidebar-portlet ">
        <!-- SIDEBAR USERPIC -->
        <div class="profile-userpic">
          <img global="avatar_{$user->id}" src="{$user->getAvatarUrl('100x100')}" class="img-responsive" alt=""> 
        </div>
        <div class="profile-userbuttons">
          <a class="btn btn-circle red btn-sm" action='change-avatar'>{Yii::t('app', 'change_avatar')}</a>
        </div>
        <!-- END SIDEBAR BUTTONS -->
        <!-- SIDEBAR MENU -->
        <div class="profile-usermenu">
        </div>
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
              <div class="caption caption-md">
                <i class="icon-globe theme-font hide"></i>
                <span class="caption-subject font-blue-madison bold uppercase">{Yii::t('app', 'profile_account')}</span>
              </div>
              <ul class="nav nav-tabs">
                <li>
                  <a href="{$links.profile}">{Yii::t('app', 'personal_info')}</a>
                </li>
                <li>
                  <a href="{$links.password}">{Yii::t('app', 'change_password')}</a>
                </li>
                <li class="active">
                  <a href="#tab_1_3">Password nâng cao</a>
                </li>
              </ul>
            </div>
            <div class="portlet-body">
              <div class="tab-content">
                <div class="tab-pane" id="tab_1_1"></div>
                <!-- PERSONAL INFO TAB -->
                <div class="tab-pane active" id="tab_1_3">
                  {ActiveForm assign='form'}
                    {$form->field($model, 'old_password')->passwordInput()}
                    {$form->field($model, 'new_password')->passwordInput()}
                    {$form->field($model, 're_password')->passwordInput()}
                    <div class="margiv-top-10">
                      <button type="submit" class="btn green"> {Yii::t('app', 'save_changes')} </button>
                      <a href="javascript:;" class="btn default"> {Yii::t('app', 'cancel')} </a>
                    </div>
                  {/ActiveForm}
                </div>
                <!-- END PERSONAL INFO TAB -->
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- END PROFILE CONTENT -->
  </div>
</div>
{registerJs}
{literal}
var manager = new ImageManager();
$("a[action='change-avatar']").selectImage(manager, {
  callback: function(img) {
    var thumb = img.src;
    var id = img.id;
    $.ajax({
      url: '{/literal}{$links.change_avatar}{literal}',
      type: 'POST',
      dataType : 'json',
      data: {image_id: id},
      success: function (result, textStatus, jqXHR) {
        if (result.status == false) {
          console.log(result.error);
          return false;
        } else {
          $('[global="avatar_{/literal}{$user->id}{literal}"]').attr('src', thumb);
        }
      },
    });
  }
});
{/literal}
{/registerJs}