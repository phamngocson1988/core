<div class="modal-slider" data-order="<?=$order->id;?>">
	<?php foreach ($files as $file) : ?>
	<img src="<?=$file->getUrl();?>">
	<?php endforeach;?>
</div>