<?php
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
if (!$user->refer_code) {
  $user->refer_code = Yii::$app->security->generateRandomString(6);
  $user->save();
}
$link = Url::to(['site/register', 'refer' => $user->refer_code], true);
$gift_value = Yii::$app->settings->get('ReferProgramForm', 'gift_value', 5);
?>

<div class="referral py-5 mb-5">
  <div class="container">
    <h1 class="text-center mb-4">Refer a friend bonus</h1>
    <p class="text-center lead text-uppercase text-white">Invite a friend to join kinggems <br/><span class="text-hight-light" data-aos="flip-left"
      data-aos-easing="ease-out-cubic"
      data-aos-duration="1000"> get $<?=$gift_value;?> bonus!</span></p>
    <div class="input-group mb-3">
      <input type="text" id="referral-link" class="form-control form-control-lg" value="<?=$link;?>" aria-label="Recipient's username" aria-describedby="basic-addon2">
      <div class="input-group-append">
        <button class="btn" id="click-to-copy-btn" type="button">COPY LINK</button>
      </div>
    </div>
    <ul class="list-inline text-center mt-5">

      <li class="list-inline-item">
        <a href="https://www.facebook.com/sharer/sharer.php?u=<?=$link;?>&t=Kinggems Title"onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=300,width=600');return false;" target="_blank" title="Share on Facebook"><img class="icon-md" src="/images/icon/facebook.svg"></a>
      </li>
      <li class="list-inline-item">
        <a href="https://twitter.com/share?url=<?=$link;?>&via=TWITTER_HANDLE&text=Kinggems Title"
           onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=300,width=600');return false;"
           target="_blank" title="Share on Twitter"><img class="icon-md" src="/images/icon/twitter.svg">
        </a>
      </li>
      <!-- <li class="list-inline-item"><a href="#"><img class="icon-md" src="/images/icon/instagram.svg"></a></li> -->
      <li class="list-inline-item"><a href="#"><img class="icon-md" src="/images/icon/gmail.svg"></a></li>
      <!-- <li class="list-inline-item"><a href="#"><img class="icon-md" src="/images/icon/wechat-icon.svg"></a></li> -->
      <!-- <li class="list-inline-item"><a href="whatsapp://send?text=<?=$link;?>" data-action="share/whatsapp/share" onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=300,width=600');return false;"
           target="_blank" title="Share on Whatsapp"><img class="icon-md" src="/images/icon/whatsapp-icon.svg"></a></li> -->
      <li class="list-inline-item"><a href="https://t.me/share/url?url=<?=$link;?>&text=Kinggems Title" onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=300,width=600');return false;"
           target="_blank" title="Share on Telegram"><img class="icon-md" src="/images/icon/telegram-icon.svg"></a></li>

    </ul>
  </div>
</div>


<div class="container mb-5">
  <div id="accordion" class="accordion-qa">
    <div class="card">
      <div class="card-header" id="headingOne">
        <h5 class="mb-0">
          <button class="btn btn-link" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
            What is KingGems Refer Friend Bonus?
          </button>
        </h5>
      </div>
  
      <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion">
        <div class="card-body">
          <p>Invite your friends to join in the fun at KingGems.us and we’ll give you an $5 bonus! Simply send an invitation link and once your friend has signed up and funded their account your bonus will be credited automatically.</p>
          <center>
            <table class="refer-values" border="1" width="50%" style="text-align: center;">
              <tr class="tab-head" style="background-color: yellow">
                <td>Deposit Amount</td>
                <td>Referal Bonus</td>
              </tr>
              <tr class="tab-body">
                <td>$50</td>
                <td>$5</td>
              </tr>
            </table>
          </center>
        </div>
      </div>
    </div>
    <div class="card">
      <div class="card-header" id="headingTwo">
        <h5 class="mb-0">
          <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
            How do I refer my friends to KingGems.us?
          </button>
        </h5>
      </div>
      <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">
        <div class="card-body">
          <p>Inviting your friends to join KingGems.us is simple, simply follow the steps below to start earning your bonus!</p>
          <ul>
            <li>1. Log in to your account.</li>
            <li>2. On the Refer a Friend page you will see your unique referral link.</li>
            <li>3. You can email, Tweet or share it with your friends on Facebook.</li>
            <li>4. Your friend must click the link and register then fund their account with minimumminimum of $50.</li>
            <li>5. Once your friend has met the funding requirement your bonus will be credited automatically to your account.</li>
          </ul>
          <p>Referring your friends to KingGems.us is quick and easy and you can invite as many as you wish. The more you invite, the more bonus you earn!</p>
        </div>
      </div>
    </div>
    <div class="card">
      <div class="card-header" id="headingThree">
        <h5 class="mb-0">
          <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
            Terms & Conditions
          </button>
        </h5>
      </div>
      <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordion">
        <div class="card-body">
          <p>1. The refer a friend promotion will run from the <b>1st July 2019 00:00:01 (GMT+7)</b> until further notice.</p>
              <p>2. The promotion is available for all active KingGems.us members. To qualify as an active member you must have placed at least one order since opening your account.</p>
              <p>3. In order to quality for the referral bonus, friends must fund their account with a minimum of USD 50 or currency equivalent within 10 days of registration.</p>
              <p>4. In order to qualify for the referral bonus, friends must be new customers and not already hold an account with KingGems.us.</p>
              <p>5. The referral bonus will be automatically credited to your account when your friend meets the deposit requirements. The bonus will be USD5 or currency equivalent per successful referral.</p>
              <p>7. The referral bonuses must be used within 10 days of being credited.</p>
              <p>9. The company reserves the right to disqualify members or refuse to accept new referrals at it’s own discretion.</p>
              <p>10. The company reserves the right to change or end the promotion at any time.</p>
              <p>11. General Terms & Conditions of Promotions apply.</p>
        </div>
      </div>
    </div>

    <div class="card">
      <div class="card-header" id="heading4">
        <h5 class="mb-0">
          <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapse4" aria-expanded="false" aria-controls="collapse4">
            Have any futher questions?
          </button>
        </h5>
      </div>
      <div id="collapse4" class="collapse" aria-labelledby="heading4" data-parent="#accordion">
        <div class="card-body">
          <p>Call us on <b>+84979997559</b> or email to <b>customerservice.kinggems@gmail.com</b></p>
        </div>
      </div>
    </div>
  </div>
</div>
<?php
$script = <<< JS
$('#click-to-copy-btn').click(function(){
    copyToClipboard($('#referral-link').val());
    toastr.success('Copied!'); 
});
JS;
$this->registerJs($script);
?>
