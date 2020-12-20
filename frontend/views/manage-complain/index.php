<?php
use yii\helpers\Url;
use yii\helpers\ArrayHelper;;
use frontend\models\Complain;
$currentUserId = Yii::$app->user->id;
$assignComplainUrl = Url::to(['manage-complain/assign', 'operator_id' => $operator->id, 'slug' => $operator->slug]);
?>
<main>
  <section class="section-profile-user">
    <div class="container">
      <?php echo $this->render('@frontend/views/manage/header.php', ['operator' => $operator, 'isAdmin' => $isAdmin]);?>
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
                </ul>
              </div>
            </div>
          </div>
          <div class="dropdown dropdown-fillter">
            <button class="dropdown-toggle" id="dropdownMenuButton" type="button" data-toggle="dropdown" aria-expanded="false"><i class="fas fa-glass-martini"></i>FILLTER</button>
            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
              <ul class="list-tabs tabs-none">
                <li><a class="trans" href="<?=Url::to(['manage-complain/index', 'operator_id' => $operator->id, 'slug' => $operator->slug]);?>" id='search-all'>All (<?=number_format(array_sum($countByStatus));?>)</a></li>
                <li><a class="trans" href="<?=Url::to(['manage-complain/index', 'operator_id' => $operator->id, 'slug' => $operator->slug, 'status' => 'open']);?>" id='search-open'>Open cases (<?=number_format(ArrayHelper::getValue($countByStatus, Complain::STATUS_OPEN, 0));?>)</a></li>
                <li><a class="trans" href="<?=Url::to(['manage-complain/index', 'operator_id' => $operator->id, 'slug' => $operator->slug, 'status' => 'resolve']);?>" id='search-resolve'>Resolved (<?=number_format(ArrayHelper::getValue($countByStatus, Complain::STATUS_RESOLVE, 0));?>)</a></li>
                <li><a class="trans" href="<?=Url::to(['manage-complain/index', 'operator_id' => $operator->id, 'slug' => $operator->slug, 'status' => 'reject']);?>" id='search-reject'>Rejected (<?=number_format(ArrayHelper::getValue($countByStatus, Complain::STATUS_REJECT, 0));?>)</a></li>
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
});

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

// Assign complain to member
$('.review-list').on('click', '.assign-to-me', function(e) {
  e.preventDefault();
  var complain_id = $(this).closest('article').data('id');
  assignComplain($currentUserId, complain_id);
  return false;
});
$('.review-list').on('change', '.assign-to-admin', function(e) {
  e.preventDefault();
  var admin_id = $(this).val();
  if (!admin_id) return false;
  
  var complain_id = $(this).closest('article').data('id');
  assignComplain(admin_id, complain_id);
  return false;
});
function assignComplain(user_id, complain_id) {
  $.ajax({
    url: '$assignComplainUrl',
    type: 'POST',
    dataType : 'json',
    data: {user_id, complain_id},
    success: function (result, textStatus, jqXHR) {
      if (result.status == false) {
        toastr.error(result.errors);
      } else {
        location.reload();
      }
    },
  });
}
JS;
$listReviewLink = Url::current();
$script = str_replace('###REVIEWLIST###', $listReviewLink, $script);
$this->registerJs($script);
?>