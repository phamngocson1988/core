<div class="bottom-header">
    <div class="main-slider">
        <div class="main-slider-bxslider">
            <div style="background: url(/images/header-bg.jpg) no-repeat center 0; background-size: auto 100%;">
                <div class="container">
                    <div class="row">
                        <div class="col col-sm-12">
                            <div class="slider-text-box">
                                <p>Lorem ipsum dolor sit amet, consectetur elit, sed do eiusmod</p>
                                <p>Food supplement with Serenoa Repens that contributes to supporting
                                    prostate
                                    and
                                    urinary-tract function.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div style="background: url(/images/header-bg.jpg) no-repeat center 0; background-size: auto 100%;">
                <div class="container">
                    <div class="row">
                        <div class="col col-sm-12">
                            <div class="slider-text-box">
                                <p>Lorem ipsum dolor sit amet, consectetur elit, sed do eiusmod</p>
                                <p>Food supplement with Serenoa Repens that contributes to supporting
                                    prostate
                                    and
                                    urinary-tract function.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div style="background: url(/images/header-bg.jpg) no-repeat center 0; background-size: auto 100%;">
                <div class="container">
                    <div class="row">
                        <div class="col col-sm-12">
                            <div class="slider-text-box">
                                <p>Lorem ipsum dolor sit amet, consectetur elit, sed do eiusmod</p>
                                <p>Food supplement with Serenoa Repens that contributes to supporting
                                    prostate
                                    and
                                    urinary-tract function.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div style="background: url(/images/header-bg.jpg) no-repeat center 0; background-size: auto 100%;">
                <div class="container">
                    <div class="row">
                        <div class="col col-sm-12">
                            <div class="slider-text-box">
                                <p>Lorem ipsum dolor sit amet, consectetur elit, sed do eiusmod</p>
                                <p>Food supplement with Serenoa Repens that contributes to supporting
                                    prostate
                                    and
                                    urinary-tract function.</p>
                            </div>
                        </div>
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