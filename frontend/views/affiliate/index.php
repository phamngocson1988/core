<?php
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
if (!$user->affiliate_code) {
  $user->affiliate_code = Yii::$app->security->generateRandomString(6);
  $user->save();
}
$link = Url::to(['site/signup', 'affiliate' => $user->affiliate_code], true);
?>

<section class="page-title">
  <div class="container">
    <div class="row">
      <div class="col col-sm-12">
        <div class="page-title-content text-center pad-bot">
          <div class="page-title-image">
            <img src="/images/text-affiliate.png" alt="">
          </div>
          <p class="no-upper">Link & Earn</p>
          <p class="small-txt">Earn up to <span>20%</span> of Kinggems Net Profit....</p>
        </div>
      </div>
    </div>
  </div>
</section>
<section class="topup-page">
  <div class="container">
    <div class="small-container">
      <div class="row">
        <div class="col col-lg-12 col-md-12 col-sm-12 col-12">
          <div class="affiliate-top">
            <div class="affiliate-terms">
              <h3>This will help you earn more profit. Start now!</h3>
              <p>By creating a reflink, you are indicating that you have read and agreed to the <a style="color: #ff3600;" href="#">Terms of Service.</a></p>
            </div>
            <div class="affiliate-create-reflink">
              <button type="button" class="cus-btn yellow has-shadow f20">CREATE YOUR FIRST REFLINK</button>
              <!-- <a href="#" class="cus-btn yellow has-shadow">CREATE YOUR FIRST REFLINK</a> -->
            </div>
            <div class="has-left-border has-shadow">
              Your Members: 0
              <a href="#" style="color: #ff3600;">Tell me how to earn more</a>
            </div>
          </div>
          <div class="affiliate-bottom">
            <div class="aff-tabs">
              <div class="aff-tabs-nav">
                <span class="active" tab-content="#reward-feed">Reward Feed</span>
                <span tab-content="#reflink">Reflink</span>
              </div>
              <div class="aff-tabs-content-block">
                <div class="aff-tabs-content has-shadow active" id="reward-feed">
                  <div class="profit-filter">
                    <span>Filter by</span>
                    <select name="" id="">
                      <option value="">Today</option>
                      <option value="">Last Day</option>
                    </select>
                    <select name="" id="">
                      <option value="">All currencies</option>
                      <option value="">Vietnam Dong</option>
                    </select>
                    <select name="" id="">
                      <option value="">All Status</option>
                      <option value="">All Status</option>
                    </select>
                  </div>
                  <div class="profit-amount">
                    <h3>Profit Today ~ 0</h3>
                    <span>(2019/06/15 - 2019/06/15)</span>
                  </div>
                  <div class="profit-notes">
                    <p>*Total profits = All currencies within the selected time filter, converted to USD</p>
                    <p>*Amount shown is approximate and subject to currency exchange rates.</p>
                  </div>
                  <div class="profit-listing">
                    <table>
                      <thead>
                        <tr>
                          <th>Date <span class="tool-tip">?</span></th>
                          <th>Amount <span class="tool-tip">?</span></th>
                          <th>Status <span class="tool-tip">?</span></th>
                          <th>Reward Eta <span class="tool-tip">?</span></th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <td>a</td>
                          <td>b</td>
                          <td>c</td>
                          <td>d</td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
                <div class="aff-tabs-content" id="reflink">
                  <p style="padding: 40px;"><?=$link;?></p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<?php
$script = <<< JS
$('.aff-tabs .aff-tabs-nav span').click(function(){
  var _tabContentId = $(this).attr('tab-content');
  $('.aff-tabs-content').removeClass('active');
  $('.aff-tabs-content'+_tabContentId).addClass('active');

  $('.aff-tabs .aff-tabs-nav span').removeClass('active');
  $(this).addClass('active');
});
JS;
$this->registerJs($script);
?>
