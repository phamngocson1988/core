<?php 
use yii\helpers\Url;
use common\components\helpers\TimeElapsed;
?>
<main>
  <div class="forum-container container">
    <div class="forum-main">
      <section class="section-forum-heading">
        <h1 class="heading-title">Forums</h1>
        <div class="heading-button"><a class="btn btn-primary" href="<?=Url::to(['forum/create']);?>">Start new topic</a></div>
      </section>
      <section class="section-forums">
        <?php foreach ($sections as $section) : ?>
        <?=\frontend\widgets\ForumOverviewWidget::widget(['section' => $section]);?>
        <?php endforeach;?>
      </section>
    </div>
    <aside class="forum-sidebar">
      <div class="side-topics widget-box">
        <h3 class="widget-head">Topics</h3>
        <div class="widget-inner">
          <ul class="list-posts">
            <li class="post-item"><a class="post-author-photo" href="#"><img src="../img/common/sample_img_00.png" alt="Username"></a>
              <div class="post-title"><a href="../forum/thread.html">Thread Title</a></div>
              <div class="post-author">
                By
                <a href="#">Username</a>
              </div>
              <div class="post-date">April 20, 2018</div>
              <div class="post-replies"><span>112</span></div>
            </li>
            <li class="post-item"><a class="post-author-photo" href="#"><img src="../img/common/sample_img_00.png" alt="Username"></a>
              <div class="post-title"><a href="../forum/thread.html">Thread Title</a></div>
              <div class="post-author">
                By
                <a href="#">Username</a>
              </div>
              <div class="post-date">April 20, 2018</div>
              <div class="post-replies"><span>112</span></div>
            </li>
            <li class="post-item"><a class="post-author-photo" href="#"><img src="../img/common/sample_img_00.png" alt="Username"></a>
              <div class="post-title"><a href="../forum/thread.html">Thread Title</a></div>
              <div class="post-author">
                By
                <a href="#">Username</a>
              </div>
              <div class="post-date">April 20, 2018</div>
              <div class="post-replies"><span>112</span></div>
            </li>
            <li class="post-item"><a class="post-author-photo" href="#"><img src="../img/common/sample_img_00.png" alt="Username"></a>
              <div class="post-title"><a href="../forum/thread.html">Thread Title</a></div>
              <div class="post-author">
                By
                <a href="#">Username</a>
              </div>
              <div class="post-date">April 20, 2018</div>
              <div class="post-replies"><span>112</span></div>
            </li>
            <li class="post-item"><a class="post-author-photo" href="#"><img src="../img/common/sample_img_00.png" alt="Username"></a>
              <div class="post-title"><a href="../forum/thread.html">Thread Title</a></div>
              <div class="post-author">
                By
                <a href="#">Username</a>
              </div>
              <div class="post-date">April 20, 2018</div>
              <div class="post-replies"><span>112</span></div>
            </li>
          </ul>
        </div>
      </div>
      <div class="side-delineation widget-box"></div>
    </aside>
  </div>
</main>
<?php 
$script = <<< JS
JS;
$this->registerJs($script);
?>
