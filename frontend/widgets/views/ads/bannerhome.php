<div class="js-delineation keyvisual-slider">
  <ul>
	<?php foreach ($models as $model) : ?>
    <li><a class="item-block large" href="<?=$model->link;?>"><img class="object-fit" src="<?=$model->getImageUrl('600x400');?>" alt="image"></a></li>
	<?php endforeach;?>
  </ul>
</div>