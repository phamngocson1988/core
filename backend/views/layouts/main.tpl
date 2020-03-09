{use class='yii\helpers\Html'}
{use class='yii\helpers\Url'}
{use class='backend\assets\AppAsset'}
{use class='backend\widgets\Alert'}
{AppAsset::register($this)|void}
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

{if !isset($this->params['body_class'])}
{$bodyClass = 'page-header-fixed page-sidebar-closed-hide-logo page-content-white'}
{else}
{$bodyClass = $this->params['body_class']}
{/if}
<body class="{$bodyClass}">
	{$this->beginBody()}
  <div class="page-wrapper">
    {include file='./parts/header.tpl'}
    <div class="clearfix"></div>
    <div class="page-container">
      {include file='./parts/sidebar.tpl'}
      <div class="page-content-wrapper">
        <!-- BEGIN CONTENT BODY -->
        <div class="page-content">
        {*include file='./parts/alert.tpl'*}
        {Alert::widget()}
        {$content}
        </div>
        <!-- END CONTENT BODY -->
      </div>
    </div>
    {include file='./parts/footer.tpl'}
  </div>
  {include file='./parts/quick-nav.tpl'}
	{$this->endBody()}
</body>
</html>
{$this->endPage()}
