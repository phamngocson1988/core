<?php
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
if (!$user->refer_code) {
  $user->refer_code = Yii::$app->security->generateRandomString(6);
  $user->save();
}
$link = Url::to(['site/signup', 'refer' => $user->refer_code], true);
$gift_value = Yii::$app->settings->get('ReferProgramForm', 'gift_value', 5);
?>

<section class="page-title">
  <div class="container">
    <div class="row">
      <div class="col col-sm-12">
        <div class="refer-page-title">
          <div class="page-title-image">
            <img src="/images/text-refer.png" alt="">
          </div>
          <p>invite a friend to join kinggems<br>& get $<?=$gift_value;?> bonus!</p>
          <p class="small">Share your unique referral link</p>
          <?php if ($user->isActiveMember()) :?>
          <div class="refer-link-copy">
            <input type="text" value="<?=$link;?>">
            <button class="click-to-copy-btn" type="button">Copy</button>
          </div>
          <div class="refer-socials">
            <a class="refer-social-item mail open-mail-popup" href="#refer-by-mails-popup"></a>
						<a class="refer-social-item fb" href="https://www.facebook.com/sharer/sharer.php?u=<?=$link;?>&t=Kinggems Title"onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=300,width=600');return false;" target="_blank" title="Share on Facebook"></a>
						<a class="refer-social-item tw" href="https://twitter.com/share?url=<?=$link;?>&via=TWITTER_HANDLE&text=Kinggems Title"
						   onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=300,width=600');return false;"
						   target="_blank" title="Share on Twitter">
						</a>
          </div>
          <?php endif;?>
        </div>
      </div>
    </div>
  </div>
  <div id="refer-by-mails-popup" style="display: none; width: 770px; max-width: 100%;">
    <?php $form = ActiveForm::begin(); ?>
      <table>
        <thead>
          <tr>
            <td></td>
            <td style="width: 30%;">Friend's Name</td>
            <td style="width: 67%;">Friend's Mail</td>
          </tr>
        </thead>
        <tbody>
        	<?php foreach(range(0, 19) as $i) {?>
        		<?php 
        		?>
          <tr tr-num="<?=++$i;?>" class="<?=($i > 5) ? 'hide-element' : '';?>">
            <td><?=$i;?></td>
            <td><?=Html::textInput("refers[$i][name]", '', ['class' => 'refer_name']);?></td>
            <td><?=Html::textInput("refers[$i][email]", '', ['class' => 'refer_email']);?></td>
          </tr>
          <?php }?>
        </tbody>
        <tfoot>
          <tr>
            <td></td>
            <td>
              <a class="btn-add-more-refer-mail" href="javascript:;">Add more</a>
            </td>
            <td>
              <?=Html::submitButton('Send', ['class' => 'cus-btn yellow']);?>
            </td>
          </tr>
        </tfoot>
      </table>
    <?php ActiveForm::end();?>
  </div>
</section>
<section class="topup-page">
  <div class="container">
    <div class="row">
      <div class="col col-lg-12 col-md-12 col-sm-12 col-12">
        <div class="refer-qa-list">
          <div class="qa-item">
            <div class="qa-question">
              What is KingGems Refer Friend Bonus?
            </div>
            <div class="qa-answer">
              <p>Invite your friends to join in the fun at KingGems.us and we’ll give you an $5 bonus! Simply send an invitation link and once your friend has signed up and funded their account your bonus will be credited automatically.</p>
              <table class="refer-values">
                <tr class="tab-head">
                  <td>Deposit Amount</td>
                  <td>Referal Bonus</td>
                </tr>
                <tr class="tab-body">
                  <td>$50</td>
                  <td>$5</td>
                </tr>
              </table>
            </div>
          </div>
          <div class="qa-item">
            <div class="qa-question">
              How do I refer my friends to KingGems.us?
            </div>
            <div class="qa-answer">
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
          <div class="qa-item">
            <div class="qa-question">
              Terms & Conditions
            </div>
            <div class="qa-answer">
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
          <div class="qa-item">
            <div class="qa-question">
              Have any futher questions?
            </div>
            <div class="qa-answer">
              <p>Call us on <b>+84979997559</b> or email to <b>customerservice.kinggems@gmail.com</b></p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<?php
$script = <<< JS
$('.click-to-copy-btn').click(function(){
    var _tempElement = $("<input>");
    $(this).parent().find('input').val($(this).parent().find('input').val()).select();
    document.execCommand("copy");
});

$(".open-mail-popup").fancybox({
    maxWidth	: 800,
    maxHeight	: 600,
    fitToView	: false,
    width		: '70%',
    height		: '70%',
    autoSize	: false,
    closeClick	: false,
    openEffect	: 'none',
    closeEffect	: 'none'
});

$('.btn-add-more-refer-mail').click(function(){
    // var _newReferMailRow = $('#refer-by-mails-popup table tbody tr:last-child').clone();
    // var _newNum = parseInt(_newReferMailRow.attr('tr-num')) + 1;
    // _newReferMailRow.attr('tr-num', _newNum);
    // _newReferMailRow.find('td:first-child').html(_newNum);
    // $('#refer-by-mails-popup table tbody').append(_newReferMailRow);
    $('#refer-by-mails-popup table tbody tr').show();
    $(this).hide();
});

$('.refer-qa-list .qa-item .qa-question').click(function(){
    if(!$(this).next().hasClass('showing')){
        $('.refer-qa-list .qa-item .qa-answer.showing').slideToggle().removeClass('showing');
        $(this).next().slideToggle().addClass('showing');
    }else{
        $(this).next().slideToggle().removeClass('showing');
    }
});

// Hide elements on refer popup
$('.hide-element').hide();
JS;
$this->registerJs($script);
?>
