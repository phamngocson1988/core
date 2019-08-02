<?php
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\widgets\LinkPager;
if (!$user->refer_code) {
  $user->refer_code = Yii::$app->security->generateRandomString(6);
  $user->save();
}
$link = Url::to(['site/signup', 'refer' => $user->refer_code], true);
?>

<section class="page-title">
  <div class="container">
    <div class="row">
      <div class="col col-sm-12">
        <div class="refer-page-title">
          <div class="page-title-image">
            <img src="/images/text-refer.png" alt="">
          </div>
          <p>invite a friend to join kinggems<br>& get $5 bonus!</p>
          <p class="small">Share your unique referral link</p>
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
        </div>
      </div>
    </div>
  </div>
  <div id="refer-by-mails-popup" style="display: none; width: 770px; max-width: 100%;">
    <?php $form = ActiveForm::begin(['action' => Url::to(['refer/index'])]); ?>
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
          <tr tr-num="<?=++$i;?>">
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
    <div class="small-container">
      <div class="row">
        <div class="col col-lg-12 col-md-12 col-sm-12 col-12">
          <div class="affiliate-bottom">
            <div class="aff-tabs">
              <div class="aff-tabs-content-block">
                <div class="aff-tabs-content active" id="reflink">
                  <div class="profit-listing">
                    <table>
                      <thead>
                        <tr>
                          <th>No</th>
                          <th>Email</th>
                          <th>Name</th>
                          <th>Register Date</th>
                          <th>First transaction</th>
                          <th>Status</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php if (!$models) : ?>
                        <tr><td colspan="6">No data found</td></tr>
                        <?php else: ?>
                        <?php foreach ($models as $no => $model) : ?>
                        <tr>
                          <td><?=$no + $pages->offset + 1;?></td>
                          <td><?=$model->email;?></td>
                          <td><?=$model->name;?></td>
                          <td><?=$model->created_at;?></td>
                          <td>
                          <?php if ($model->transaction) : ?>
                          <?=sprintf("Date %s - Amount $ %s", $model->transaction->payment_at, number_format($model->transaction->total_price));?>
                          <?php endif;?>
                          </td>
                          <td>
                          <?php if ($model->isReady()) : ?>
                          <a href="<?=Url::to(['refer/take', 'id' => $model->id]);?>" class="btn btn-info link-action" role="button">Move to wallet</a>
                          <?php else:?>
                          <?=$model->status;?>
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

$(".link-action").ajax_action({
  confirm: true,
  confirm_text: 'Do you really want to transfer this commission to your wallet?',
  callback: function(eletement, data) {
    location.reload();
  }
});
JS;
$this->registerJs($script);
?>
