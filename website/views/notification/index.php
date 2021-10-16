<?php
use common\components\helpers\FormatConverter;
use common\components\helpers\StringHelper;
use website\widgets\LinkPager;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;

?>
<style>
  tbody > tr {
    background-color: #f3f2f2;
  }
  tbody > tr.read {
    background-color: white;
  }
</style>
<div class="container order-page">
  <h1 class="text-uppercase mt-5">Notification</h1>
  <nav aria-label="breadcrumb">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="/">Home</a></li>
      <li class="breadcrumb-item active" aria-current="page">Notification</li>
    </ol>
  </nav>
  <div style="display: flex; justify-content: space-between;">
  <p class="lead mb-2">Notification</p>
  <a href="javascript:;" id="notification-read-all-button">Mark all as read</a>
  </div>
  <div class="table-wrapper table-responsive">
    <table class="table table-hover">
      <thead>
        <tr>
          <th class="text-center" scope="col" style="width:50%">Message</th>
          <th class="text-center" scope="col" style="width:15%">Created Date</th>
          <th class="text-center" scope="col" style="width:15%">Action</th>
        </tr>
      </thead>
      <tbody>
        <?php if (!$notifications) : ?>
        <tr><td class="text-center" colspan="3">No data found.</td></tr>
        <?php endif;?>
      	<?php foreach ($notifications as $notification) : ?>
        <tr <?=$notification['read'] ? " class='read' " : "";?>>
          <td><?=$notification['message'];?></td>
          <td class="text-center"><?=$notification['timeago'];?></td>
          <td class="text-center">
            <?php if (!$notification['read']) : ?>
              <a href='<?=Url::to(['notification/read', 'id' => $notification['id']]);?>' class="notification-read-button btn btn-outline-success btn-sm" type="button">Mark as read</a>
            <?php endif;?>
            <a href="<?=$notification['url'];?>" class="btn btn-outline-info btn-sm" type="button">View details</a>
          </td>
        </tr>
      	<?php endforeach;?>
      </tbody>
    </table>
  </div>
  <nav aria-label="Page navigation" class="mt-2 mb-5">
    <?=LinkPager::widget(['pagination' => $pagination]);?>
  </nav>
</div>

<?php
$readAllUrl = Url::to(['notification/read-all']);
$script = <<< JS
$('#notification-read-all-button').on('click', function(e){
  e.stopPropagation();
  $.ajax({
    url: '$readAllUrl',
    type: "GET",
    dataType: "json",
    success: function (data) {
      $('tbody > tr').removeClass('read');
      $('tbody > tr').addClass('read');
      $('tbody > tr').find('.notification-read-button').remove();
    }
  });
  return false;
});
$('.notification-read-button').on('click', function(e){
  e.stopPropagation();
  var ele = $(this);
  var link = ele.attr('href');
  $.ajax({
    url: link,
    type: "GET",
    dataType: "json",
    success: function (data) {
      ele.closest('tr').addClass('read');
      ele.remove();
    }
  });
  return false;
});
JS;
$this->registerJs($script);
?>