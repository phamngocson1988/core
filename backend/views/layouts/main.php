<?php 
use yii\helpers\Html;
use yii\helpers\Url;
use backend\assets\AppAsset;
use common\widgets\Alert;
use \lavrentiev\widgets\toastr\NotificationFlash;
AppAsset::register($this);
$this->beginPage();
?>
<!DOCTYPE html>
<html lang="<?=Yii::$app->language;?>">
<head>
  <meta charset="<?= Yii::$app->charset;?>">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta content="width=device-width, initial-scale=1" name="viewport" />
  <?=Html::csrfMetaTags();?>
  <title><?=Html::encode($this->title);?></title>
  <link rel="shortcut icon" href="/images/favicon.ico" />
  <?php $this->head();?>
</head>

<?php if (!isset($this->params['body_class'])) {
    $bodyClass = 'page-header-fixed page-sidebar-closed-hide-logo page-content-white';
} else {
    $bodyClass = $this->params['body_class'];
}
?>
<body class="<?=$bodyClass;?>">
  <?php $this->beginBody();?>
  <div class="page-wrapper">
    <?php include __DIR__ . '/parts/header.php';?>
    <div class="clearfix"></div>
    <div class="page-container">
        <?php include __DIR__ . '/parts/sidebar.php';?>
      <div class="page-content-wrapper">
        <!-- BEGIN CONTENT BODY -->
        <div class="page-content">
        <?php Alert::widget();?>
        <?=$content;?>
        </div>
        <!-- END CONTENT BODY -->
      </div>
    </div>
    <?php include __DIR__ . '/parts/footer.php';?>
  </div>
  <?php $this->endBody();?>
</body>
</html>
<?php $this->endPage();?>
