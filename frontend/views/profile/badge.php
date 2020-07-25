<?php foreach ($models as $badge) :?>
<?php $badgeType = $badge->badge;?>
<?php echo $this->render("@frontend/views/profile/badge/$badgeType.php", ['badge' => $badge]);?>
<?php endforeach;?>