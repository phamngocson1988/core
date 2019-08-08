<?php
use yii\helpers\Url;
use yii\widgets\LinkPager;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

$user = Yii::$app->user->identity;
$link = Url::to(['site/signup', 'affiliate' => $user->affiliate->code], true);
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
              <input type="text" readonly="true" value="<?=$link;?>" id="affiliate_link"/>
              <button type="button" class="cus-btn yellow has-shadow f20 click-to-copy-btn">COPY LINK</button>
            </div>
            <div class="has-left-border has-shadow">
              Your Members: <?=number_format($member);?>
              <a href="<?=Url::to(['affiliate/withdraw-request']);?>" style="color: #ff3600;">Withdraw your commission</a>
            </div>
          </div>
          <div class="affiliate-bottom">
            <div class="aff-tabs">
              <div class="aff-tabs-nav">
                <a href="<?=Url::to(['affiliate/index']);?>"><span tab-content="#reward-feed">Reward Feed</span></a>
                <a href="<?=Url::to(['affiliate/member']);?>"><span tab-content="#reflink">Members</span></a>
                <a href="javascript:void;"><span class="active" tab-content="#withdraw">Withdraw</span></a>
              </div>
              <div class="aff-tabs-content-block">
                <div class="aff-tabs-content has-shadow" id="reward-feed">
                </div>
                <div class="aff-tabs-content active" id="reflink">
                  <div class="profit-listing">
                    <table>
                      <thead>
                        <tr>
                          <th>No</th>
                          <th>Request date</th>
                          <th>Amount</th>
                          <th>Status</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php if (!$models) : ?>
                        <tr><td colspan="4">No data found</td></tr>
                        <?php else: ?>
                        <?php foreach ($models as $no => $model) : ?>
                        <tr>
                          <td><?=$no + $pages->offset + 1;?></td>
                          <td><?=$model->created_at;?></td>
                          <td><?=number_format($model->amount);?></td>
                          <td><?=$model->getStatusLabel();?></td>
                        </tr>
                        <?php endforeach; ?>
                        <?php endif;?>
                      </tbody>
                    </table>
                    <?=LinkPager::widget(['pagination' => $pages])?>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
