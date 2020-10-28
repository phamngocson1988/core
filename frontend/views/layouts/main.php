<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use frontend\assets\AppAsset;
use frontend\components\toastr\NotificationFlash;
use webzop\notifications\widgets\Notifications;
AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<!--[if IE 9 ]> <html lang="vi" class="ie9 loading-site no-js"> <![endif]-->
<!--[if IE 8 ]> <html lang="vi" class="ie8 loading-site no-js"> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!-->
<html lang="<?=Yii::$app->language ?>">
<!--<![endif]-->
<head>
  <meta charset="<?= Yii::$app->charset ?>">
  <meta name="HandheldFriendly" content="True">
  <meta name="MobileOptimized" content="320">
  <meta name="format-detection" content="telephone=no">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <!--[if IE]><meta http-equiv="cleartype" content="on"><![endif]-->
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no, user-scalable=0" id="viewport">
  <meta name="description" content="">
  <meta name="keywords" content="">
  <?= Html::csrfMetaTags() ?>
  <title><?= Html::encode($this->title) ?></title>
  <link rel="shortcut icon" href="/favicon.ico" />
  <?= NotificationFlash::widget() ?>
  <?php $this->head() ?>
</head>
<body>
	<?php $this->beginBody();?>
  <div class="wrapper">
    <header class="header">
      <div class="container d-flex justify-content-between">
        <div class="header-left d-flex align-items-center">
          <h1 class="header-logo"><a class="trans" href="/"><img src="/img/common/logo.png" alt="image"></a></h1>
          <nav class="header-nav" id="js-nav-bar">
            <div class="header-nav-inner">
              <form class="header-search" action="./">
                <input class="form-control" type="text" placeholder="<?=Yii::t('app', 'Search');?>">
                <button class="fa fa-search" type="submit"></button>
              </form>
              <?php $main_menu_active = isset($this->params['main_menu_active']) ? $this->params['main_menu_active'] : '';?>
              <?php
              $items = [];
              $categories = \frontend\models\Category::find()->all();
              if (Yii::$app->user->isGuest) {
                $items[] = ['label' => Yii::t('app', 'Login'), 'url' => '#modalLogin', 'visible' => Yii::$app->user->isGuest, 'template' => '<a href="{url}" data-toggle="modal">{label}</a>', 'options' => ['class' => 'header-login']];
              } else {
                $items[] = ['label' => strtoupper(Yii::$app->user->identity->getName()), 'url' => ['profile/index'], 'options' => ['class' => 'header-login']];
              }
              $items[] = ['label' => Yii::t('app', 'Operators'), 'url' => ['operator/index'], 'active' => $main_menu_active == 'operator.index'];
              $items[] = ['label' => Yii::t('app', 'Bonuses'), 'url' => ['bonus/index'], 'active' => $main_menu_active == 'bonus.index'];
              $items[] = ['label' => Yii::t('app', 'Complaints'), 'url' => ['complain/index'], 'active' => $main_menu_active == 'complain.index'];
              $items[] = [
                'label' => Yii::t('app', 'News'), 
                'url' => ['news/index'], 
                'active' => $main_menu_active == 'news.index', 
                'options' => ['class' => 'has-sub'],
                'template' => '<a href="{url}" class="item-nav no-link">{label}</a><div class="item-nav js-btn-dropdown">{label}</div>', 
                'items' => array_map(function($category) {
                  return ['label' => $category->title, 'url' => Url::to(['news/category', 'id' => $category->id, 'slug' => $category->slug]), 'template' => '<a href="{url}" class="trans">{label}</a>'];
                }, $categories)
              ];
              $items[] = ['label' =>  Yii::t('app', 'Forum'), 'url' => ['forum/index'], 'active' => $main_menu_active == 'forum.index'];
              ?>
              <?=yii\widgets\Menu::widget([
                'options' => ['class' => 'header-nav-list d-flex'],
                'itemOptions' => ['class' => ''],
                'linkTemplate' => '<a href="{url}" class="item-nav">{label}</a>',
                'submenuTemplate' => '<ul class="js-dropdown">{items}</ul>',
                'items' => $items
                ]);
              ?>
            </div>
          </nav>
        </div>
        <div class="header-right d-flex align-items-center">
          
          <?php if (Yii::$app->user->isGuest) : ?>
          <div class="header-login"><a href="#modalLogin" data-toggle="modal"><?=Yii::t('app', 'Login');?></a></div>
          <?php else : ?>
          <?php $user = Yii::$app->user->getIdentity();?>
          <div class="header-icon header-email"><a class="trans" href="<?=Url::to(['mail/index']);?>"><i class="fas fa-envelope"></i></a></div>
          <?=\frontend\components\notifications\Notifications::widget();?>
          <?=\frontend\widgets\ProfileMenuWidget::widget();?>
          <?php endif;?>
          <div class="header-language">
            <div class="button-language js-button-language"><span><?php
            $currentLanguageData = ArrayHelper::getValue(Yii::$app->params['languages'], Yii::$app->language);
            echo $currentLanguageData ? $currentLanguageData['short'] : 'EN';
            ?></span></div>
            <div class="dropdown-language js-dropdown-language">
              <ul class="list-language">
                <?php foreach (Yii::$app->params['languages'] as $language => $languageData) : ?>
                <li><a href="<?=Url::to(['site/language', 'language' => $language]);?>"><span class="flag"><img src="/img/common/<?=$language;?>.png" alt="image"></span><span class="text"><?=$languageData['title'];?></span></a></li>
                <?php endforeach;?>
              </ul>
            </div>
          </div>
        </div>
        <div class="btn-menu" id="btn-menu"><span></span><span></span><span></span></div>
      </div>
    </header>
    <div class="overlay-menu" id="overlay-menu"></div>
      <?=$content;?>
    <footer class="footer">
      <div class="container d-flex flex-wrap">
        <div class="footer-col col-md-2 col-6">
          <?=yii\widgets\Menu::widget([
            'options' => ['class' => 'footer-menu'],
            'items' => [
              ['label' => Yii::t('app', 'Operators'), 'url' => ['operator/index'], 'active' => $main_menu_active == 'operator.index'],
              ['label' => Yii::t('app', 'Bonuses'), 'url' => ['bonus/index'], 'active' => $main_menu_active == 'bonus.index'],
              ['label' => Yii::t('app', 'Complaints'), 'url' => ['complain/index'], 'active' => $main_menu_active == 'complain.index'],
              ['label' => Yii::t('app', 'News'), 'url' => ['news/index'], 'active' => $main_menu_active == 'news.index'],
              ['label' => Yii::t('app', 'Forum'), 'url' => ['forum/index'], 'active' => $main_menu_active == 'forum.index'],
            ]
          ]);?>
        </div>
        <div class="footer-col col-md-2 col-6">
          <?=yii\widgets\Menu::widget([
            'options' => ['class' => 'footer-menu'],
            'items' => [
              ['label' => Yii::t('app', 'About Us'), 'url' => ['site/about']],
              ['label' => Yii::t('app', 'Advertise'), 'url' => ['site/advertise']],
              ['label' => Yii::t('app', 'Corporate'), 'url' => ['site/corporate']],
              ['label' => Yii::t('app', 'Contact Us'), 'url' => ['site/contact']],
            ]
          ]);?>
        </div>
        <div class="footer-col col-12 col-md-7">
          <p class="footer-title"><?=Yii::t('app', 'Sign up for lastest promotion offers from our partners');?></p>
          <form class="footer-form d-flex" action="./">
            <input class="form-control form-control-sm" type="text" placeholder="Email address">
            <button class="btn btn-warning" type="submit"><?=Yii::t('app', 'Sign up');?></button>
          </form>
          <div class="footer-text col-12 col-md-10"><?=Yii::t('app', 'By subscribing you are certifying that you have reviewed and accepted our updated Privacy and Cookie Policy');?></div>
          <ul class="footer-sns d-flex align-items-center">
            <li><a class="fab fa-facebook trans" href="#"></a></li>
            <li><a class="fab fa-youtube trans" href="#"></a></li>
            <li><a class="fab fa-twitter trans" href="#"></a></li>
          </ul>
        </div>
      </div>
    </footer>
  </div>
  <?php require_once(Yii::$app->basePath . '/views/layouts/modal.php');?>
  <?php $this->endBody();?>
</body>
<?php
$script = <<< JS
$(".list-language > li").on('click', function() {
  var link = $(this).find('a').attr('href');
  console.log(link);
  $.ajax({
    url: link,
    type: "GET",
    success: function(result){
      console.log(result);
      location.reload();
    },
  });
  return false;
});
JS;
$this->registerJs($script);
?>
</html>
<?php $this->endPage();?>