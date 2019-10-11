<?php
$setting = Yii::$app->settings;
?>
<section class="footer">
  <div class="bot-footer">
    <div class="container">
      <div class="small-container">
        <div class="footer-logo">
          <a href="/">
          <img src="<?=$setting->get('ApplicationSettingForm', 'logo', '/images/logo.png');?>" alt="">
          </a>
          <div class="footer-security">
            <div>
              <img src="/images/ico-security-1.jpg" alt="">
            </div>
            <div>
              <img src="/images/ico-security-2.jpg" alt="">
            </div>
            <div>
              <img src="/images/ico-security-3.jpg" alt="">
            </div>
            <div>
              <img src="/images/ico-security-4.jpg" alt="">
            </div>
          </div>
        </div>
        <div class="footer-about">
          <h4>about us</h4>
          <p>King Gems is an organization,we provide worldwide top up services for mobile games. Our
            service is delivered with 03 core values:
          </p>
          <ul>
            <li>Fast Top-up</li>
            <li>Cost Savings</li>
            <li>Multiple Choices</li>
          </ul>
          <p>Come to us to enjoy your games with acceptable cost and best quality service!</p>
          <p>We understand and believe that customer’s trust is the greatest asset of King Gems.
            Therefore, we are constantly developing to improve service quality, in order to meet the
            diverse needs of all multinational customers.
          </p>
        </div>
        <div class="footer-payment">
          <div class="t-img-wrap-logo-payment">
            <a href="https://kinggems.us/faq/detail/27-how-to-pay-via-visa-master-card.html"><img src="/images/visa-foot.png" alt=""></a>
          </div>
          <div class="t-img-wrap-logo-payment">
            <a href="https://kinggems.us/faq/detail/27-how-to-pay-via-visa-master-card.html"><img src="/images/master.png" alt=""></a>
          </div>
          <div class="t-img-wrap-logo-payment">
            <a href="https://kinggems.us/faq/detail/31-how-to-pay-via-skrill.html"><img src="/images/skrill.png" alt=""></a>
          </div>
          <div class="t-img-wrap-logo-payment">
            <a href="javascript:void(0);"><img src="/images/payoneer.png" alt=""></a>
          </div>
          <div class="t-img-wrap-logo-payment">
            <a href="https://kinggems.us/faq/detail/28-how-to-pay-via-paypal.html"><img src="/images/paypal.png" alt=""></a>
          </div>
          <div class="t-img-wrap-logo-payment">
            <a href="https://kinggems.us/faq/detail/30-how-to-pay-via-alipay.html"><img src="/images/alipay.png" alt=""></a>
          </div>
          <div class="t-img-wrap-logo-payment">
            <a href="https://kinggems.us/faq/detail/29-how-to-pay-via-wechat.html"><img src="/images/we.png" alt=""></a>
          </div>
        </div>
        <div class="footer-socials">
          <h4>Find Us</h4>
          <ul>
            <li><a href="https://www.facebook.com/Kinggems.us/" target="_blank"><img src="/images/ico-fb.png" alt=""></a></li>
            <li><a href="https://wa.me/84979997559" target="_blank"><img src="/images/ico-viber.png" alt=""></a></li>
            <li><a href="https://t.me/KINGGEMS1303" target="_blank"><img src="/images/ico-mb.png" alt=""></a></li>
            <li><a href="https://u.wechat.com/IK-OOlb-deUWqmVLUAnz-GA" target="_blank"><img src="/images/ico-wechat.png" alt=""></a></li>
            <li><a href="https://line.me/ti/p/6MOHXdDoCg" target="_blank"><img src="/images/ico-line.png" alt=""></a></li>
          </ul>
        </div>
      </div>
      <div class="small-container">
        <div class="copyright">
          <p>© 2019 KING GEMS - All rights reserved</p>
        </div>
      </div>
    </div>
  </div>
</section>
<?php if (Yii::$app->session->hasFlash('popup-annoucement')) : ?>
<a href="<?=Yii::$app->session->getFlash('popup-annoucement');?>" class='fancybox popup-annoucement' style="display: none"></a>
<?php endif;?>
<?php
$script = <<< JS
$('.popup-annoucement').trigger('click');
JS;
$this->registerJs($script);
?>