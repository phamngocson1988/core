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
    ],
    [
        'title' => $setting->get('GallerySettingForm', 'title5'),
        'content' => $setting->get('GallerySettingForm', 'content5'),
        'link' => $setting->get('GallerySettingForm', 'link5'),
    ],
    [
        'title' => $setting->get('GallerySettingForm', 'title6'),
        'content' => $setting->get('GallerySettingForm', 'content6'),
        'link' => $setting->get('GallerySettingForm', 'link6'),
    ],
    [
        'title' => $setting->get('GallerySettingForm', 'title7'),
        'content' => $setting->get('GallerySettingForm', 'content7'),
        'link' => $setting->get('GallerySettingForm', 'link7'),
    ],
    [
        'title' => $setting->get('GallerySettingForm', 'title8'),
        'content' => $setting->get('GallerySettingForm', 'content8'),
        'link' => $setting->get('GallerySettingForm', 'link8'),
    ]
];
$gallery = array_filter($gallery, function($data) {
    return $data['link'];
});
?>
<div class="bottom-header">
  <div class="main-slider">
    <div class="main-slider-blurred">
      <?php foreach ($gallery as $data) : ?>
      <div>
        <img src="<?=$data['link'];?>" alt="">
      </div>
      <?php endforeach;?>
    </div>
    <div class="container">
      <div class="row">
        <div class="col col-sm-12">
          <div class="main-slider-bxslider">
            <?php foreach ($gallery as $data) : ?>
            <div>
              <div class="slider-image-box">
                <img src="<?=$data['link'];?>" alt="">
                <div class="slider-text-box">
                  <p><?=Html::encode($data['title']);?></p>
                  <p><?=Html::encode($data['content']);?></p>
                </div>
              </div>
            </div>
            <?php endforeach;?>
          </div>
        </div>
      </div>
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
var _headerHeight = $('section.header .top-header').height();
$('.main-slider .slider-image-box').css('margin-top', _headerHeight + 'px');
$('.main-slider .main-slider-blurred').css('width', 100*$('.main-slider .main-slider-blurred > div').length+'%');
$('.main-slider .main-slider-blurred > div').css('width', $('.main-slider .main-slider-blurred').width()/$('.main-slider .main-slider-blurred > div').length+'px');
$('.main-slider-bxslider').bxSlider({
    pagerSelector: $('.mainSlider-custom-Pager'),
    nextSelector: $('.main-slider-next-control'),
    prevSelector: $('.main-slider-prev-control'),
    speed: 1000,
    onSlideBefore: function(slideElement, oldIndex, newIndex) {
        setTimeout(function(){
            $('.main-slider .main-slider-blurred').css('left', '-'+(100*newIndex)+'%');
        }, 500);
    },
});
JS;
$this->registerJs($script);
?>