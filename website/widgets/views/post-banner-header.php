<?php 
$setting = Yii::$app->settings;
$postBanner = $setting->get('ApplicationSettingForm', 'post_banner');
?>

<?php if ($postBanner) : ?>
<section class="py-3" style="background-color: #d8e3e8;">
  <div class="container">
    <div class="banner-subpayment">
      <img src="<?=$postBanner;?>"/>
    </div>
  </div>
</section>
<?php endif;?>