<?php
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this \yii\web\View view component instance */
/* @var $message \yii\mail\MessageInterface the message being composed */
/* @var $content string main view render result */
?>
<?php $this->beginPage() ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=<?= Yii::$app->charset ?>" />
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
    <?php $this->beginBody() ?>
    <div style="background-color: #F2F2F2; font-family: Arial, Helvetica, sans-serif; font-size: 12px; color: #444; line-height: 18px; font-weight: normal; padding: 20px auto;">
		<div style="width: 600px; margin: 0 auto; background-color: white">
			<div style="padding:10px 20px;">
    			<?= $content ?>
    		</div>
	    </div>
	</div>
    <?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
