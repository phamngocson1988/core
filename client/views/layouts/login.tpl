{use class='yii\helpers\Html'}
{use class='yii\helpers\Url'}
{use class='client\assets\LoginAsset'}
{LoginAsset::register($this)|void}
{$this->beginPage()}
<!DOCTYPE html>
<html lang="{$app->language}">
<head>
  <meta charset="{$app->charset}">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta content="width=device-width, initial-scale=1" name="viewport" />
  {Html::csrfMetaTags()}
  <title>{Html::encode($this->title)}</title>
  <link rel="shortcut icon" href="/images/favicon.ico" />
  {$this->head()}
</head>

<body class=" login">
	{$this->beginBody()}
  <!-- BEGIN LOGO -->
  <div class="logo">
    <a href="index.html">
    <img src="../vendor/assets/pages/img/logo-big.png" alt="" /> </a>
  </div>
  <!-- END LOGO -->
  <!-- BEGIN LOGIN -->
  <div class="content">
    {$content}
  </div>
  <div class="copyright"> {Yii::t('app', 'copyright')} </div>
	{$this->endBody()}
</body>
</html>
{$this->endPage()}
