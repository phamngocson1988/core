<div class="block-delineation js-delineation">
	<ul>
		<?php foreach ($models as $model) : ?>
		<li><a href="<?=$model->link;?>" target="_blank"><img src="<?=$model->getImageUrl('220x700');?>" alt="image"></a></li>
		<?php endforeach;?>
	</ul>
</div>