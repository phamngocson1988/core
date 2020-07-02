<?php 
use yii\helpers\Url;
use common\components\helpers\TimeElapsed;
?>
<main>
  <div class="section-user-message-wrapper">
    <!-- <section class="section-user-storage">
      <div class="block-storage">
        <div class="progress-meter"><span style="width:40%"></span></div>
        <div class="progress-text">Used 0% message storage</div>
      </div>
    </section> -->
    <section class="section-user-message container">
      <aside class="sec-sidebar">
        <div class="block-header">
          <div class="header-title">Inbox</div>
          <div class="header-button"><a class="btn btn-primary btn-sm" href="<?=Url::to(['mail/compose']);?>">Compose new</a></div>
        </div>
        <div class="block-main widget-box">
          <div class="box-title widget-head">
            <div class="head-text">Messages</div>
          </div>
          <ul class="list-message" id="js-list-message">
            <?php foreach ($threads as $thread) : ?>
            <li id="thread<?=$thread->id;?>">
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
$('#js-list-message>li').on('click', function(){
  $(this).find('.mailthread-item').trigger('click');
});
$(".mailthread-item").on('click', function(e) {
  $.ajax({
    url: $(this).attr('href'),
    type: 'get',
    dataType : 'html',
    complete: function(result) {
        $('#js-message-main').html(result.responseText);
    }
  });
});

// Review Form
$('#js-message-main').on('submit', 'form#reply-form', function(e) {
    e.preventDefault();
    e.stopImmediatePropagation();
    var form = $(this);
    $.ajax({
        url: form.attr('action'),
        type: form.attr('method'),
        dataType : 'json',
        data: form.serialize(),
        success: function (result, textStatus, jqXHR) {
            if (result.status == false) {
              toastr.error(result.errors);
              return false;
            } else {
              toastr.success(result.data.message);
              $('#thread' + result.data.id).trigger('click');
            }
        },
    });
    return false;
});
JS;
$this->registerJs($script);
?>
