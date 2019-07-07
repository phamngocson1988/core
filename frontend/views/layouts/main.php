<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use frontend\assets\AppAsset;
use common\widgets\Alert;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<!--[if IE 9 ]> <html lang="vi" class="ie9 loading-site no-js"> <![endif]-->
<!--[if IE 8 ]> <html lang="vi" class="ie8 loading-site no-js"> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!-->
<html class="loading-site no-js" lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <meta name='robots' content='noindex,follow' />
    <link rel="icon" href="/images/favicon.ico" type="image/x-icon">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>
<div class="wrapper">
  <section class="header">
    <div class="container">
      <div class="row">
        <div class="col col-sm-12">
          <?php require_once(Yii::$app->basePath . '/views/layouts/top-header.php');?>
          <?php require_once(Yii::$app->basePath . '/views/layouts/bottom-header.php');?>
        </div>
      </div>
    </div>
  </section>
  <?= $content ?>
  <?php require_once(Yii::$app->basePath . '/views/layouts/highlight.php');?>
  <?php require_once(Yii::$app->basePath . '/views/layouts/footer.php');?>
</div>
<?php $this->endBody() ?>
</body>
<?php require_once(Yii::$app->basePath . '/views/layouts/livechat.php');?>
</html>
<?php $this->endPage() ?>
