<?php
use yii\helpers\Html;
$setting = Yii::$app->settings;
$gallery = [
    [
        'title' => $setting->get('GallerySettingForm', 'title1'),
        'content' => $setting->get('GallerySettingForm', 'content1'),
        'link' => $setting->get('GallerySettingForm', 'link1'),
    ],
    [
        'title' => $setting->get('GallerySettingForm', 'title2'),
        'content' => $setting->get('GallerySettingForm', 'content2'),
        'link' => $setting->get('GallerySettingForm', 'link2'),
    ],
    [
        'title' => $setting->get('GallerySettingForm', 'title3'),
        'content' => $setting->get('GallerySettingForm', 'content3'),
        'link' => $setting->get('GallerySettingForm', 'link3'),
    ],
    [
        'title' => $setting->get('GallerySettingForm', 'title4'),
        'content' => $setting->get('GallerySettingForm', 'content4'),
        'link' => $setting->get('GallerySettingForm', 'link4'),
    ]
];
$gallery = array_filter($gallery, function($data) {
    return $data['link'];
});
?>
<div class="bottom-header">
    <div class="main-slider">
        
        <div class="main-slider-bxslider">
            <?php foreach ($gallery as $data) : ?>
            <div style="background: url(<?=Html::encode($data['link']);?>) no-repeat center 0; background-size: auto 100%;">
                <div class="container">
                    <div class="row">
                        <div class="col col-sm-12">
                            <div class="slider-text-box">
                                <p><?=$data['title'];?></p>
                                <p><?=$data['content'];?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach;?>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-sm-12">
                    <span class="main-slider-prev-control"></span>
                    <div class="mainSlider-custom-Pager">
                    </div>
                    <span class="main-slider-next-control"></span>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
$script = <<< JS
$('.main-slider-bxslider').bxSlider({
    pagerSelector: $('.mainSlider-custom-Pager'),
    nextSelector: $('.main-slider-next-control'),
    prevSelector: $('.main-slider-prev-control')
});

$('.top-header .right-box a.ico-user-login').click(function(){
    $(this).parent().toggleClass('active');
    $(this).toggleClass('active');
});

$('.mobile-nav a.mobile-nav-ico').click(function(){
    $(this).parent().toggleClass('active');
    $(this).toggleClass('active');
});

$('body').on('click', function(e) {
    if($(e.target).closest('#mobile-nav-wrapper').length == 0) {
        $('#mobile-nav-wrapper, .mobile-nav a.mobile-nav-ico').removeClass('active');
    }

    if($(e.target).closest('#login-box-wrapper').length == 0) {
        $('#login-box-wrapper, #login-box-wrapper a.ico-user-login').removeClass('active');
    }
});
JS;
$this->registerJs($script);
?>