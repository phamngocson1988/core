<?php
use yii\helpers\Url;
use yii\helpers\Html;
use frontend\widgets\LinkPager;
$this->title = 'Notifications';
?>

<main>
  <section class="section-profile">
    <div class="container sec-content">
      <div class="mod-column">
        <section class="section-forum-heading">
          <h1 class="heading-title">Notifications</h1>
          <div class="heading-button">
            <a class="btn btn-primary read-all" href="<?= Url::toRoute(['notification/read-all']) ?>">Mark all as read</a>
          </div>
        </section>
        <div class="section-notification widget-box">
          <ul class="list-notification">
            <?php if($notifications): ?>
            <?php foreach($notifications as $notif): ?>
            <li class="<?=($notif['read']) ? 'read' : 'is-unread';?>" data-id="<?= $notif['id']; ?>" data-key="<?= $notif['key']; ?>">
              <a href="<?= $notif['url'] ?>">
                <div class="col-avatar">
                  <div class="user-photo"><img src="../img/common/sample_img_00.png" alt="Username"></div>
                </div>
                <div class="col-content">
                  <p class="notifition-message"><?= Html::encode($notif['message']); ?></p>
                  <div class="notifition-time"><?= $notif['timeago']; ?></div>
                </div>
                <div class="col-button"><span>Read more</span></div>
              </a>
            </li>
            <?php endforeach; ?>
            <?php else: ?>
                <li class="empty-row">There are no notifications to show</li>
            <?php endif; ?>
          </ul>
          <div class="pagination-wrap">
            <?=LinkPager::widget([
              'pagination' => $pagination, 
              'maxButtonCount' => 1, 
              'hideOnSinglePage' => false,
              'linkOptions' => ['class' => 'page-link'],
              'pageCssClass' => 'page-item',
            ]);?>
          </div>
        </div>
      </div>
      <div class="mod-sidebar">
        <div class="sidebar-col">
          <?=\frontend\widgets\TopOperatorWidget::widget();?>
        </div>
      </div>
    </div>
  </section>
</main>
<?php
$script = <<< JS
$('a.read-all').ajax_action({
  method: 'GET',
  callback: function(element, data) {
    location.reload();
  },
  error: function(errors) {
    location.reload();
  },
});
JS;
$this->registerJs($script);
?>