<?php
use yii\helpers\Url;
?>
<style type="text/css">
.image-item {
  position: relative;
}
.overlay {
  position: absolute;
  top: 0;
  right: 16px;
  opacity: 0;
  transition: .3s ease;
  background-color: white;
}
.image-item:hover .overlay {
  cursor: pointer;
  opacity: 1;
}

</style>
<?php foreach ($images as $index => $image) : ?>
<div class="col-sm-6 image-item" style="margin-bottom: 10px">
    <a class="fancybox" data-fancybox="gallery_before" href="<?=$image->getUrl();?>" target="_blank"><img src="<?=$image->getImageUrl('200x200');?>" class="img img-responsive full-width"></a>
    <div class="overlay">
        <img src="/images/trash.png" class="delete-image" href="<?=Url::to(['order/remove-evidence-image', 'id' => $image->id])?>">
    </div>
</div>
<?php endforeach;?>
<!-- remove image order -->
<?php
$removeImageJs = <<< JS
$('body').on('click', '.delete-image', function(e){
    e.preventDefault();
    e.stopImmediatePropagation();
    if (!window.confirm('Bạn có muốn xóa file hình này?')) {
        return;
    }
    var element = this;
    $.ajax({
        url: $(this).attr('href'),
        type: 'GET',
        dataType : 'json',
        success: function (result, textStatus, jqXHR) {
            console.log('result', result);
            if (result.status == false) {
                toastr.error(result.errors);
            } else {
                $(element).parents('.image-item').remove();
                toastr.success("Xóa hình ảnh thành công");
            }
        },
    });
});
JS;
$this->registerJs($removeImageJs)
?>
<!-- End remove image order -->