<?php 
use yii\helpers\Url;
?>
<div class="sec-heading-profile widget-box mb-4">
  <div class="heading-banner"><img class="object-fit" src="../img/profile/profile_bnr.jpg" alt="image"></div>
  <div class="heading-body">
    <div class="heading-avatar col-avatar">
      <div class="heading-image operator-avatar-background">
        <img class="object-fit operator-avatar" src="<?=$operator->getImageUrl('150x150');?>" alt="image">
        <a class="edit-camera fas fa-camera trans" href="javascript:;"></a>
        <input type="file" id="upload-user-avatar-element" name="upload-user-avatar-element" style="display: none" multiple accept="image/*"/>
      </div>
      <h1 class="heading-name"><?=$operator->name;?></h1>
    </div>
    <div class="heading-right">
      <ul class="profile-link profile-link-custom">
        <li class="favorites"><a class="trans" href="<?=Url::to(['manage-operator/index', 'operator_id' => $operator->id, 'slug' => $operator->slug]);?>"><i class="fas fa-home"></i><span>BACK TO PAGE</span></a></li>
        <?php if ($isAdmin) : ?>
        <li class="edit-profile"><a class="trans" href="<?=Url::to(['manage-operator/edit', 'operator_id' => $operator->id, 'slug' => $operator->slug]);?>"><i class="fas fa-cog"></i><span>EDIT MY PAGE</span></a></li>
        <?php endif;?>
      </ul>
    </div>
  </div>
</div>
<?php
$script = <<< JS
$('form').on('keyup keypress', function(e) {
  var keyCode = e.keyCode || e.which;
  if (keyCode === 13) { 
    e.preventDefault();
    return false;
  }
});

// upload image
var uploadImage = new AjaxUploadImage({
  trigger_element: '.edit-camera',
  file_element: '#upload-user-avatar-element', // seletor of the file element
  review_width: '180',
  review_height: '180',
  link: '###LINK###'
});
uploadImage.callback = function(data) { 
  console.log(data);
  var objs = Object.values(data);
  if (objs.length) {
    var avatarObj = objs[0];
    var id = avatarObj.id;
    var thumb = avatarObj.thumb;
    console.log(id);
    console.log(thumb);
    $('body').find('.operator-avatar').attr('src', thumb);
    $('body').find('.operator-avatar-background').attr('style', 'background-image: url("'+thumb+'")')
    // Update user avatar
    $.ajax({
      url: '###UPDATEAVATAR###',
      type: 'POST',
      dataType : 'json',
      data: {image_id: id},
      success: function (result, textStatus, jqXHR) {
        console.log(result);
      },
    });
  } else {
    toastr.error('No file');
  }
};
JS;
$uploadLink = Url::to(['image/ajax-upload']);
$updateAvatarLink = Url::to(['manage-operator/update-avatar', 'operator_id' => $operator->id]);
$script = str_replace('###LINK###', $uploadLink, $script);
$script = str_replace('###UPDATEAVATAR###', $updateAvatarLink, $script);
$this->registerJs($script);
?>