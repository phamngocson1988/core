<?php
use yii\helpers\Url;
use frontend\models\Complain;
?>
<main>
  <section class="section-profile-user">
    <div class="container">
      <?php echo $this->render('@frontend/views/manage/header.php', ['operator' => $operator]);?>
      <h2 class="sec-heading-title">Player Complaints</h2>
      <div class="widget-box timeline-post">
        <div class="timeline-heading">
          <div class="heading-text mb-0">
            <div class="dropdown dropdown-sort">
              <button class="dropdown-toggle" id="dropdownMenuSort" type="button" data-toggle="dropdown" aria-expanded="false">Sort</button>
              <div class="dropdown-menu" aria-labelledby="dropdownMenuSort">
                <ul class="list-sort">
                  <li><a class="trans" href="javascript:;" id='sort-lastest'>Latest</a></li>
                  <li><a class="trans" href="javascript:;" id='sort-oldest'>Oldest</a></li>
                  <!-- <li><a class="trans" href="javascript:;" id='order-lastest'>Assigned To</a></li> -->
                </ul>
              </div>
            </div>
          </div>
          <div class="dropdown dropdown-fillter">
            <button class="dropdown-toggle" id="dropdownMenuButton" type="button" data-toggle="dropdown" aria-expanded="false"><i class="fas fa-glass-martini"></i>FILLTER</button>
            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
              <ul class="list-tabs tabs-none">
                <li><a class="trans" href="javascript:;" id='search-all'>All (<?=number_format($operator->totalComplain());?>)</a></li>
                <li><a class="trans" href="javascript:;" id='search-open'>Open cases (<?=number_format($operator->totalComplainOpen());?>)</a></li>
                <li><a class="trans" href="javascript:;" id='search-resolve'>Resolved (<?=number_format($operator->totalComplainResolve());?>)</a></li>
                <li><a class="trans" href="javascript:;" id='search-reject'>Rejected (<?=number_format($operator->totalComplainReject());?>)</a></li>
              </ul>
            </div>
          </div>
        </div>
        <div class="widget-main">
          <div class="review-list">
          </div>
        </div>
      </div>
    </div>
  </section>
</main>

<?php
$script = <<< JS
$('.review-list').on('click', '.reply-complain-button', function() {
  var form = $(this).closest('form.reply-complain-form');
  $.ajax({
    url: form.attr('action'),
    type: form.attr('method'),
    dataType : 'json',
    data: form.serialize(),
    success: function (result, textStatus, jqXHR) {
        if (result.status == false) {
          toastr.error(result.errors);
        } else {
          toastr.success(result.data.message);
          setTimeout(() => {  
            location.reload();
          }, 1000);
        }
    },
  });
  return false;
})

// Review List
var reviewListLoading = new AjaxPaging({
  container: '.review-list',
  request_url: '###REVIEWLIST###',
  auto_first_load: true,
});
$('#load-more-reivew').on('click', function() {
  reviewListLoading.load();
});
$('#sort-lastest').on('click', function() {
  reviewListLoading.reset({
    condition: {
      sort: 'date',
      type: 'desc',
    }
  });
});
$('#sort-oldest').on('click', function() {
  reviewListLoading.reset({
    condition: {
      sort: 'date',
      type: 'asc',
    }
  });
});
$('#search-all').on('click', function() {
  reviewListLoading.reset({
    request_url: '###REVIEWLIST###',
  });
});
$('#search-open').on('click', function() {
  reviewListLoading.reset({
    condition: {
      status: '###STATUS_OPEN###'
    }
  });
});
$('#search-resolve').on('click', function() {
  reviewListLoading.reset({
    condition: {
      status: '###STATUS_RESOLVE###'
    }
  });
});
$('#search-reject').on('click', function() {
  reviewListLoading.reset({
    condition: {
      status: '###STATUS_REJECT###'
    }
  });
});
JS;
$listReviewLink = Url::to(['manage/list-complain', 'operator_id' => $operator->id]);
$script = str_replace('###REVIEWLIST###', $listReviewLink, $script);
$script = str_replace('###STATUS_OPEN###', Complain::STATUS_OPEN, $script);
$script = str_replace('###STATUS_RESOLVE###', Complain::STATUS_RESOLVE, $script);
$script = str_replace('###STATUS_REJECT###', Complain::STATUS_REJECT, $script);
$this->registerJs($script);
?>