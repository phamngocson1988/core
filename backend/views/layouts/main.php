<?php
use yii\helpers\Html;
use yii\helpers\Url;
use backend\assets\AppAsset;
AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<!--[if IE 9 ]> <html lang="vi" class="ie9 loading-site no-js"> <![endif]-->
<!--[if IE 8 ]> <html lang="vi" class="ie8 loading-site no-js"> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!-->
<html lang="en" class="loading-site no-js" lang="<?=Yii::$app->language ?>">
<!--<![endif]-->
<head>
  <meta charset="<?= Yii::$app->charset ?>">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta content="width=device-width, initial-scale=1" name="viewport" />
  <?= Html::csrfMetaTags() ?>
  <title><?= Html::encode($this->title) ?></title>
  <link rel="shortcut icon" href="/images/favicon.ico" />
  <?php $this->head() ?>
</head>
<?php 
if (!isset($this->params['body_class'])) {
	$bodyClass = 'page-header-fixed page-sidebar-closed-hide-logo page-content-white';
} else {
	$bodyClass = $this->params['body_class'];
}
?>
<body class="<?=$bodyClass;?>">
	<?php $this->beginBody();?>
	<div class="page-wrapper">
    <?php require_once(Yii::$app->basePath . '/views/layouts/parts/header.php');?>
    <div class="clearfix"></div>
    <div class="page-container">
      <?php require_once(Yii::$app->basePath . '/views/layouts/parts/sidebar.php');?>
      <div class="page-content-wrapper">
        <!-- BEGIN CONTENT BODY -->
        <div class="page-content">
      	  <?php require_once(Yii::$app->basePath . '/views/layouts/parts/alert.php');?>
          <?=$content;?>
        </div>
        <!-- END CONTENT BODY -->
      </div>
    </div>
    <?php require_once(Yii::$app->basePath . '/views/layouts/parts/footer.php');?>
  </div>
    <?php require_once(Yii::$app->basePath . '/views/layouts/parts/quick-nav.php');?>
	<?php $this->endBody();?>
</body>
</html>
<?php $this->endPage();?>
