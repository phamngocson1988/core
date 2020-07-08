<?php
use yii\helpers\Url;
?>
<main>
  <section class="section-profile-user">
    <div class="container">
      <div class="sec-heading-profile widget-box mb-4">
        <div class="heading-banner"><img class="object-fit" src="../img/profile/profile_bnr.jpg" alt="image"></div>
        <div class="heading-body">
          <div class="heading-avatar col-avatar">
            <div class="heading-image"><img class="object-fit" src="../img/common/avatar_img_01.png" alt="image"><a class="edit-camera fas fa-camera trans" href="#"></a></div>
            <h1 class="heading-name">Henderson &amp; Bench</h1>
          </div>
          <div class="heading-right">
            <ul class="profile-link profile-link-custom">
              <li class="favorites"><a class="trans" href="#"><i class="fas fa-home"></i><span>BACK TO PAGE</span></a></li>
              <li class="edit-profile"><a class="trans" href="#"><i class="fas fa-cog"></i><span>EDIT MY PAGE</span></a></li>
            </ul>
          </div>
        </div>
      </div>
      <h2 class="sec-heading-title">Player reviews</h2>
      <div class="widget-box timeline-post">
        <div class="timeline-heading">
          <div class="heading-text mb-0">
            <div class="dropdown dropdown-sort">
              <button class="dropdown-toggle" id="dropdownMenuSort" type="button" data-toggle="dropdown" aria-expanded="false">Sort</button>
              <div class="dropdown-menu" aria-labelledby="dropdownMenuSort">
                <ul class="list-sort">
                  <li><a class="trans" href="#">Latest</a></li>
                  <li><a class="trans" href="#">Oldest</a></li>
                </ul>
              </div>
            </div>
          </div>
          <div class="dropdown dropdown-fillter">
            <button class="dropdown-toggle" id="dropdownMenuButton" type="button" data-toggle="dropdown" aria-expanded="false"><i class="fas fa-glass-martini"></i>FILLTER</button>
            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
              <ul class="list-tabs tabs-none">
                <li><a class="trans" href="#">All (1592)</a></li>
                <li><a class="trans" href="#">Responded (100)</a></li>
                <li><a class="trans" href="#">Unresponded (2)</a></li>
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
  auto_first_load: true
});
$('#load-more-reivew').on('click', function() {
  reviewListLoading.load();
});
$('#sort-review-by-date').on('click', function() {
  reviewListLoading.reset({
    condition: {
      sort: 'date',
      type: $(this).hasClass('is-down') ? 'desc' : 'asc',
    }
  });
});
$('#sort-review-by-rate').on('click', function() {
  reviewListLoading.reset({
    condition: {
      sort: 'rate',
      type: $(this).hasClass('is-down') ? 'desc' : 'asc',
    }
  });
});
JS;
$listReviewLink = Url::to(['manage/list-review', 'id' => $model->id]);
$script = str_replace('###REVIEWLIST###', $listReviewLink, $script);
$this->registerJs($script);
?>