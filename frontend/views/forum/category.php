<?php 
use yii\helpers\Url;
use common\components\helpers\TimeElapsed;
use frontend\widgets\LinkPager;
?>
<main>
  <div class="forum-container container">
    <div class="forum-main">
      <section class="section-forum-heading">
        <h1 class="heading-title"><?=$category->title;?></h1>
        <?php if (!Yii::$app->user->isGuest) : ?>
        <div class="heading-button"><a class="btn btn-primary" href="<?=Url::to(['forum/create', 'category_id' => $category->id]);?>"><?=Yii::t('app', 'Start new topic');?></a></div>
        <?php endif;?>
      </section>
      <section class="section-topics">
        <section class="topics-group widget-box">
          <div class="group-header">
            <?=LinkPager::widget([
              'options' => ['class' => 'pagination pagination-sm'],
              'pagination' => $pages, 
              'maxButtonCount' => 1, 
              'hideOnSinglePage' => false,
              'linkOptions' => ['class' => 'page-link'],
              'pageCssClass' => 'page-item',
            ]);?>
          </div>
          <div class="group-container">
            <ol class="topic-list">
              <?php foreach ($topics as $topic) : ?>
              <?php $user = $topic->creator;?>
              <li class="topic-row">
                <div class="topic-content">
                  <h3 class="topic-title"><a href="<?=Url::to(['forum/topic', 'id' => $topic->id, 'slug' => $topic->slug]);?>"><?=$topic->subject;?></a></h3>
                  <p class="topic-info"><?=Yii::t('app', 'By {user}', ['user' => sprintf('<a href="javascript:;">%s</a>', $user->getName())]);?>, <?=date('F j, Y', strtotime($topic->created_at));?></p>
                </div>
                <div class="topic-stat">
                  <span class="stat-reply"><?=Yii::t('app', '{count} replies', ['count' => number_format($topic->countPost())]);?></span>
                  <span class="stat-view"><?=Yii::t('app', '{count} views', ['count' => 0]);?></span></div>
                <div class="topic-author"><a class="author-photo" href="javascript:;"><img src="<?=$user->getAvatarUrl('34x34');?>" alt="<?=$user->getName();?>"></a>
                  <div class="author-username"><a href="javascript:;"><?=$user->getName();?></a></div>
                  <div class="post-date"><a href="#"><?=TimeElapsed::timeElapsed($topic->created_at);?></a></div>
                </div>
              </li>
              <?php endforeach ;?>
            </ol>
          </div>
          <div class="group-footer">
            <?=LinkPager::widget([
              'options' => ['class' => 'pagination pagination-sm'],
              'pagination' => $pages, 
              'maxButtonCount' => 1, 
              'hideOnSinglePage' => false,
              'linkOptions' => ['class' => 'page-link'],
              'pageCssClass' => 'page-item',
            ]);?>
          </div>
        </section>
      </section>
    </div>
    <aside class="forum-sidebar">
      <div class="side-delineation widget-box"></div>
    </aside>
  </div>
</main>