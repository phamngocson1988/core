<?php 
use yii\helpers\Url;
use common\components\helpers\TimeElapsed;
$loadThreadUrl = Url::to(['mail/list-thread'])
?>
<main>
  <div class="section-user-message-wrapper">
    <section class="section-user-storage">
      <div class="block-storage">
        <div class="progress-meter"><span style="width:<?=$percent;?>%"></span></div>
        <div class="progress-text">Used <?=$percent;?>% message storage</div>
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
                  <!-- <input type="checkbox"> -->
                </div>
                <div class="dropdown-menu" aria-labelledby="dropdown-select"><a class="dropdown-item" href="javascript:;" id="sort-desc">SORT DESC</a><a class="dropdown-item" href="javascript:;" id="sort-asc">SORT ASC</a></div>
              </div>
            </div>
          </div>
          <ul class="list-message" id="js-list-message">
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
// $('#js-list-message').on('click', 'li', function(){
//   $(this).find('.mailthread-item').trigger('click');
// });
$("#js-list-message").on('click', '.mailthread-item', function(e) {
  e.preventDefault();
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
              $('#js-list-message #thread' + result.data.id + ' .mailthread-item').trigger('click');
            }
        },
    });
    return false;
});

// threads
var threadListLoading = new AjaxPaging({
  container: '#js-list-message',
  request_url: '$loadThreadUrl',
  limit: 50,
  auto_first_load: true
});
// $('#load-more-reivew').on('click', function() {
//   threadListLoading.load();
// });
$('#sort-desc').on('click', function() {
  threadListLoading.reset({
    condition: {
      sort: 'desc',
    }
  });
});
$('#sort-asc').on('click', function() {
  threadListLoading.reset({
    condition: {
      sort: 'asc',
    }
  });
});
JS;
$this->registerJs($script);
?>
