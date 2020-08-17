<div class="block-delineation js-delineation">
	<ul>
		<?php foreach ($models as $model) :?>
		<?php $imageUrl = $model->getImageUrl('1260x60');?>
		<li>
			<a href="<?=$model->link;?>" target="_blank">
				<img class="d-none d-md-block" src="<?=$imageUrl;?>" alt="<?=$model->title;?>">
				<img class="d-block d-md-none" src="<?=$imageUrl;?>" alt="<?=$model->title;?>">
			</a>
		</li>
		<?php endforeach;?>
	</ul>
</div>