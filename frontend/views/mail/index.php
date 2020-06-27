<?php 
use yii\helpers\Url;
use common\components\helpers\TimeElapsed;
?>
<main>
  <div class="section-user-message-wrapper">
    <section class="section-user-storage">
      <div class="block-storage">
        <div class="progress-meter"><span style="width:40%"></span></div>
        <div class="progress-text">Used 0% message storage</div>
      </div>
    </section>
    <section class="section-user-message container">
      <aside class="sec-sidebar">
        <div class="block-header">
          <div class="header-title">Inbox</div>
          <div class="header-button"><a class="btn btn-primary btn-sm" href="<?=Url::to(['mail/compose']);?>">Compose new</a></div>
        </div>
        <div class="block-main widget-box">
          <div class="box-title widget-head">
            <div class="head-text">Messages</div>
            <div class="head-button">
              <div class="dropdown">
                <div class="btn btn-sm dropdown-toggle" id="dropdown-select" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  <input type="checkbox">
                </div>
                <div class="dropdown-menu" aria-labelledby="dropdown-select"><a class="dropdown-item" href="#">All</a><a class="dropdown-item" href="#">None</a></div>
              </div>
            </div>
          </div>
          <ul class="list-message" id="js-list-message">
            <?php foreach ($threads as $thread) : ?>
            <li>
              <div class="col-avatar"><a class="user-photo" href="javascript:;"><img src="<?=$thread->sender->getAvatarUrl('50x50');?>" alt="Username"></a></div>
              <div class="col-content">
                <div class="message-title"><a class="mailthread-item" href="<?=Url::to(['mail/view', 'id' => $thread->id]);?>"><?=$thread->subject;?></a></div>
                <div class="message-info">
                  <div class="sender"><a href="#"><?=$thread->sender->username;?></a></div>
                  <div class="date"><?=TimeElapsed::timeElapsed($thread->updated_at);?></div>
                </div>
              </div>
            </li>
            <?php endforeach;?>
          </ul>
        </div>
      </aside>
      <div class="sec-main" id="js-message-main">
        <div class="sec-empty"><i class="fa fa-envelope"></i>
          <p>No message selected</p>
        </div>
        <div class="sec-button d-block d-md-none"><a class="btn btn-sm btn-primary js-back" href="#">Back</a></div>
      </div>
    </section>
  </div>
</main>
<?php 
$script = <<< JS
$(".mailthread-item").on('click', function(e) {
  $.ajax({
    url: $(this).attr('href'),
    type: 'get',
    dataType : 'html',
    complete: function(result) {
        $('#js-message-main').html(result.responseText);
    }
  });
})
JS;
$this->registerJs($script);
?>
