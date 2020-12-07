<?php
use yii\helpers\Url;
?>
<main>
  <section class="section-profile-user">
    <div class="container">
      <?php echo $this->render('@frontend/views/manage/header.php', ['operator' => $operator]);?>
      
      <h2 class="sec-heading-title">Player reviews</h2>
      <div class="widget-box timeline-post">
        <div class="timeline-heading">
          <div class="heading-text mb-0">
            <div class="dropdown dropdown-sort">
              <button class="dropdown-toggle" id="dropdownMenuSort" type="button" data-toggle="dropdown" aria-expanded="false">Sort</button>
              <div class="dropdown-menu" aria-labelledby="dropdownMenuSort">
                <ul class="list-sort">
                  <li><a class="trans" href="javascript:;" id='sort-latest'>Latest</a></li>
                  <li><a class="trans" href="javascript:;" id='sort-oldest'>Oldest</a></li>
                </ul>
              </div>
            </div>
          </div>
          <div class="dropdown dropdown-fillter">
            <button class="dropdown-toggle" id="dropdownMenuButton" type="button" data-toggle="dropdown" aria-expanded="false"><i class="fas fa-glass-martini"></i>FILLTER</button>
            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
              <ul class="list-tabs tabs-none">
                <li><a class="trans" href="<?=Url::to(['manage-review/index', 'operator_id' => $operator->id, 'slug' => $operator->slug]);?>" id='search-all'>All (<?=number_format($operator->countReview());?>)</a></li>
                <li><a class="trans" href="<?=Url::to(['manage-review/index', 'operator_id' => $operator->id, 'slug' => $operator->slug, 'status' => 'responded']);?>" id='search-responded'>Responded (<?=number_format($operator->countResponsedReview());?>)</a></li>
                <li><a class="trans" href="<?=Url::to(['manage-review/index', 'operator_id' => $operator->id, 'slug' => $operator->slug, 'status' => 'unresponded']);?>" id='search-unresponded'>Unresponded (<?=number_format($operator->countUnResponsedReview());?>)</a></li>
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
// Review Form
var reviewForm = new AjaxFormSubmit({
  element : 'form#add-review-form'
});
reviewForm.error = function (errors) {
  toastr.error(errors);
}
reviewForm.success = function (data, form) {
  toastr.success(data.message);
  setTimeout(() => {  
    location.reload();
  }, 1000);
}

// Review List
var reviewListLoading = new AjaxPaging({
  container: '.review-list',
  request_url: '###REVIEWLIST###',
  auto_first_load: true,
});
$('#load-more-reivew').on('click', function() {
  reviewListLoading.load();
});
$('#sort-latest').on('click', function() {
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
JS;
$listReviewLink = Url::current();
$script = str_replace('###REVIEWLIST###', $listReviewLink, $script);
$this->registerJs($script);
?>