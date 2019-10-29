<?php
use yii\helpers\Url;
use yii\widgets\LinkPager;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

$user = Yii::$app->user->identity;
$link = Url::to(['site/register'], true);
$code = $user->affiliate->code; //affiliate
?>
<style type="text/css">
  .swal-button--copy {background-color: #ffdd00}
</style>

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
              <input type="hidden" value="<?=$code;?>" id="affiliate_code"/>
              <input type="text" value="<?=$link;?>" id="affiliate_link"/>
              <button type="button" class="cus-btn yellow has-shadow f20 click-to-copy-btn">SHOW LINK</button>
            </div>
            <div class="has-left-border has-shadow">
              Your Members: <?=number_format($member);?>
              <a href="<?=Url::to(['site/affiliate']);?>" style="color: #ff3600;">Tell me how to earn more</a>
            </div>
          </div>
          <div class="affiliate-bottom">
            <div class="aff-tabs">
              <div class="aff-tabs-nav">
                <a href="javascript:void;"><span class="active" tab-content="#reward-feed">Reward Feed</span></a>
                <a href="<?=Url::to(['affiliate/member']);?>"><span tab-content="#reflink">Members</span></a>
                <a href="<?=Url::to(['affiliate/withdraw']);?>"><span tab-content="#withdraw">Withdraw</span></a>
              </div>
              <div class="aff-tabs-content-block">
                <div class="aff-tabs-content has-shadow active" id="reward-feed">
                  <div class="profit-filter">
                    <?php $form = ActiveForm::begin(['method' => 'get']);?>
                    <span>Filter by</span>
                    <!-- <select name="created_at" id="">
                      <option value="<?=date('Y-m-d');?>">Today</option>
                      <option value="<?=date('Y-m-d', strtotime("yesterday"));?>">Last Day</option>
                    </select> -->
                    <select name="status" id="status">
                      <option value="">All</option>
                      <option value="pending" <?php if($status == 'pending'):?> selected <?php endif;?>>Pending</option>
                      <option value="ready" <?php if($status == 'ready'):?> selected <?php endif;?>>Ready</option>
                    </select>
                    <?php ActiveForm::end()?>

                  </div>
                 <!--  <div class="profit-amount">
                    <h3>Profit Today ~ 0</h3>
                    <span>(2019/06/15 - 2019/06/15)</span>
                  </div> -->
                  <div class="profit-notes">
                    <p>*Total profits = All currencies within the selected time filter, converted to USD</p>
                    <p>*Amount shown is approximate and subject to currency exchange rates.</p>
                  </div>
                  <div class="profit-listing">
                    <table>
                      <thead>
                        <tr>
                          <th>Date </th>
                          <th>Amount </th>
                          <th>Description </th>
                          <th>Status </th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php if (!$models) : ?>
                        <tr><td colspan="4">No data found</td></tr>
                        <?php else: ?>
                        <?php foreach ($models as $model) : ?>
                        <tr>
                          <td>
                          <?=$model->created_at;?>
                          </td>
                          <td><?=number_format($model->commission, 1);?></td>
                          <td><?=$model->description;?></td>
                          <td>
                          <?php if (!$can_withdraw) : ?>
                          <span class="label label-default"><?=sprintf("Waiting");?><span>
                          <?php else : ?>
                            <?php if ($model->isPending()) : ?>
                            <span class="label label-default"><?=sprintf("Pending up to %s", $model->valid_from_date);?><span>
                            <?php elseif ($model->isReady()) : ?>
                            <span class="label label-success"><?=sprintf("Ready");?><span>
                            <?php endif; ?>
                          <?php endif;?>
                          </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php endif;?>
                      </tbody>
                    </table>
                    <?=LinkPager::widget(['pagination' => $pages])?>
                  </div>
                </div>
                <div class="aff-tabs-content" id="reflink">
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
$('.click-to-copy-btn').click(function(){
  var link = $('#affiliate_link').val();
  var code = $('#affiliate_code').val();
  if (link.indexOf("?") != -1) { //have params
    link += '&affiliate=' + code;
  } else { // dont have params
    link += '?affiliate=' + code;
  }
  swal({
    title: "Your affiliate link",
    text: link,
    icon: "info",
    buttons: {
      copy: {text: "Copy", value: "copy"},
      ok: "OK"
    },
  }).then((value) => {
    if (value == 'copy') {
      copyToClipboard(link);
      swal({
        title: "Copied",
        icon: "success",
      });
    }
  });
});
$('.aff-tabs .aff-tabs-nav span').click(function(){
  var _tabContentId = $(this).attr('tab-content');
  $('.aff-tabs-content').removeClass('active');
  $('.aff-tabs-content'+_tabContentId).addClass('active');

  $('.aff-tabs .aff-tabs-nav span').removeClass('active');
  $(this).addClass('active');
});

$('#status').on('change', function(){
  $(this).closest('form').submit();
});

$(".link-action").ajax_action({
  confirm: true,
  confirm_text: 'Do you really want to transfer this commission to your wallet?',
  callback: function(eletement, data) {
    // location.reload();
    console.log(data);
  }
});
JS;
$this->registerJs($script);
?>
