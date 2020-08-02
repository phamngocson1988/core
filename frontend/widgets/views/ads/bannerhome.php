<div class="block-delineation js-delineation">
	<ul>
		<?php foreach ($models as $model) : ?>
		<li>
			<a href="<?=$model->link;?>" target="_blank">
				<img class="d-none d-md-block" src="<?=$model->getImageUrl('1260x100');?>" alt="image">
				<img class="d-block d-md-none" src="<?=$model->getImageUrl('1260x100');?>" alt="image">
			</a>
		</li>
		<?php endforeach;?>
	</ul>
</div>