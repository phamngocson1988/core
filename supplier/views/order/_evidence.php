<?php
use yii\helpers\Url;
?>
<?php foreach ($images as $index => $image) : ?>
<div class="col-sm-6 mt-element-overlay image-item" style="margin-bottom: 10px">
    <div class="mt-overlay-1">
        <img src="<?=$image->getImageUrl('200x200');?>" class="img img-responsive full-width">
        <div class="mt-overlay">
        <ul class="mt-info">
            <li>
            <a class="btn default btn-outline delete-image" href="<?=Url::to(['order/remove-evidence-image', 'id' => $image->id])?>"><i class="icon-close"></i></a>
            </li>
            <li>
            <a class="btn default btn-outline fancybox" data-fancybox="gallery_before" href="<?=$image->getUrl();?>" target="_blank"><i class="icon-link"></i></a>
            </li>
        </ul>
        </div>
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