<?php
use yii\helpers\Html;
use yii\helpers\Url;
use common\widgets\Alert;
use backend\assets\LoginAsset;

LoginAsset::register($this);
$this->beginPage();
?>
<!DOCTYPE html>
<html lang="<?=Yii::$app->language;?>">
<head>
  <meta charset="<?=Yii::$app->charset;?>">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta content="width=device-width, initial-scale=1" name="viewport" />
  <?=Html::csrfMetaTags();?>
  <title><?=Html::encode($this->title);?></title>
  <link rel="shortcut icon" href="/images/favicon.ico" />
  <?php $this->head();?>
</head>

<body class=" login">
  <?php $this->beginBody();?>
  <!-- BEGIN LOGO -->
  <div class="logo">
    <a href="index.html">
    <img src="../vendor/assets/pages/img/logo-big.png" alt="" /> </a>
  </div>
  <!-- END LOGO -->
  <!-- BEGIN LOGIN -->
  <div class="content">
    <?php Alert::widget();?>
    <?=$content;?>
  </div>
  <div class="copyright"> <?=Yii::t('app', 'copyright');?> </div>
	<?php $this->endBody();?>
</body>
</html>
<?php $this->endPage();?>
