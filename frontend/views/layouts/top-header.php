<?php
use yii\bootstrap\ActiveForm;
use frontend\forms\LoginForm;
use yii\captcha\Captcha;
use yii\helpers\Url;
$setting = Yii::$app->settings;
?>
<div class="container top-header-container">
    <div class="row">
        <div class="col col-sm-12">
            <div class="top-header display-ib">
                <div class="top-header-inner display-ib">
                    <div class="mobile-nav" id="mobile-nav-wrapper">
                        <a href="javascript:;" class="mobile-nav-ico"><i class="fas fa-bars"></i></a>
                        <div class="mobile-nav-block">
                            <nav>
                                <ul>
                                    <li><a href="/">Home</a></li>
                                    <li><a href="<?=Url::to(['topup/index']);?>">Top up</a></li>
                                    <li><a href="<?=Url::to(['game/index']);?>">Shop</a></li>
                                    <li><a href="<?=Url::to(['promotion/index']);?>">Promotion</a></li>
                                    <li><a href="<?=Url::to(['refer/index']);?>">Refer Friend</a></li>
                                    <li><a href="<?=Url::to(['affiliate/index']);?>">Afiliate</a></li>
                                    <li><a href="<?=Url::to(['site/question']);?>">Q&A</a></li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                    <div class="logo fl-left">
                        <a href="#">
                            <img src="<?=$setting->get('ApplicationSettingForm', 'logo');?>" alt="">
                        </a>
                    </div>
                    <div class="right-box fl-right" id="login-box-wrapper">
                        <a href="javascript:;" class="ico-user-login"><i class="fas fa-user"></i></a>
                        <?php if (!Yii::$app->user->isGuest) :?>
                        <div class="login-box">
                            <span class="fl-left">Chào bạn</span>
                        </div>
                        <?php else :?>
                        <div class="login-box">
                            <?php $form = ActiveForm::begin(['action' => ['site/login']]); ?>
                            <span class="fl-left">Join Now</span>
                            <div class="login-form fl-left">
                                <div class="login-control fl-left">
                                    <?php $top_login_form = new LoginForm();?>
                                    <?= $form->field($top_login_form, 'username', [
                                        'template' => '{input}', 
                                        'options' => ['tag' => false],
                                        'inputOptions' => ['class' => '']
                                    ])->textInput() ?>
                                    <?= $form->field($top_login_form, 'password', [
                                        'template' => '{input}', 
                                        'options' => ['tag' => false],
                                        'inputOptions' => ['class' => '']
                                    ])->passwordInput() ?>
                                </div>
                                <div class="login-captcha fl-left">
                                    <div class="captcha-example">
                                        <?= $form->field($top_login_form, 'captcha', [
                                            'options' => ['tag' => false],
                                            'inputOptions' => ['class' => '', 'placeholder' => 'Input captcha'],
                                            'template' => '{input}'
                                        ])->widget(Captcha::className(), [
                                            'template' => '{input}{image}',
                                            'imageOptions' => ['class' => 'captcha-image']
                                        ])->label('Captcha') ?>
                                    </div>
                                </div>
                                <div class="login-submit fl-left">
                                    <input type="submit" value="Login">
                                    <div class="forgot-password"><a href="#">Forgot login detail?</a></div>
                                </div>
                            </div>
                            <?php ActiveForm::end(); ?>
                        </div>
                        <?php endif;?>
                        <div class="main-nav">
                            <nav>
                                <ul>
                                    <li><a href="/">Home</a></li>
                                    <li><a href="<?=Url::to(['topup/index']);?>">Top up</a></li>
                                    <li><a href="<?=Url::to(['game/index']);?>">Shop</a></li>
                                    <li><a href="<?=Url::to(['promotion/index']);?>">Promotion</a></li>
                                    <li><a href="<?=Url::to(['refer/index']);?>">Refer Friend</a></li>
                                    <li><a href="<?=Url::to(['affiliate/index']);?>">Afiliate</a></li>
                                    <li><a href="<?=Url::to(['site/question']);?>">Q&A</a></li>
                                </ul>
                            </nav>
                            <div class="search-box">
                                <form action="#">
                                    <div class="search-control">
                                        <input type="text" placeholder="Search">
                                        <input type="submit" value="">
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="header-time-box display-ib">
                    <span class="fl-left"><?=date('d/m/Y | H:i');?>(GTM+7)</span>
                    <span class="fl-right"><?=$setting->get('ApplicationSettingForm', 'top_notice');?></span>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
$script = <<< JS
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