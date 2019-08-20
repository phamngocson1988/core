<?php
use yii\helpers\Url;
?>
<?php foreach ($images as $image) : ?>
<div class="col-md-2 col-sm-3 mt-element-overlay image-item">
    <div class="mt-overlay-1">
        <img src="<?=$image->getUrl();?>" height="200" width="200">
        <div class="mt-overlay">
        <ul class="mt-info">
            <?php if ($can_edit) : ?>
            <li>
            <a class="btn default btn-outline delete" href="<?=Url::to(['order/remove-evidence-image', 'id' => $image->id])?>"><i class="icon-close"></i></a>
            </li>
            <?php endif;?>
            <li>
            <a class="btn default btn-outline" href="<?=$image->getUrl();?>" target="_blank"><i class="icon-link"></i></a>
            </li>
        </ul>
        </div>
    </div>
</div>
<?php endforeach;?>