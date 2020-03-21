<?php
use yii\helpers\Url;
?>
<style type="text/css">
.image{
    position:relative;
    overflow:hidden;
    padding-bottom:100%;
}
.image img{
    position:absolute;
}
.btn-delete {
   position: absolute;
   cursor: pointer;
   right: 2px;
   top: 2px;
}
</style>
<?php foreach ($images as $image) : ?>
<div class="col-sm-6 image-item"> 
    <div class="image">
        <a href="<?=$image->getUrl();?>" class="fancybox" data-fancybox="gallery_before"><img src="<?=$image->getUrl();?>" class="img img-responsive full-width" /></a>
        <a href="<?=Url::to(['order/remove-evidence-image', 'id' => $image->id])?>" class="delete-image"><img class="btn-delete" src="/images/delete.png"/></a>
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