<div class="js-top-keyvisual keyvisual-slider">
  <ul>
	<?php foreach ($models as $model) : ?>
    <li><a class="item-block large" href="<?=$model->link;?>"><img class="object-fit" src="<?=$model->getImageUrl('600x400');?>" alt="<?=$model->title;?>"></a></li>
	<?php endforeach;?>
  </ul>
</div>