<?php if ($errors) : ?>
	<?php foreach ($errors as $error) {
		echo $error;
	}?>
<?php else : ?>
	Success
<?php endif;?>