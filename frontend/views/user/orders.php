<?php
use yii\widgets\Pjax;
use yii\widgets\LinkPager;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\helpers\Html;
use frontend\models\Game;
?>
<section class="page-title">
  <div class="container">
    <div class="row">
      <div class="col col-sm-12">
        <div class="page-title-content text-center">
          <img src="/images/text-your-account.png" alt="">
        </div>
        <div class="page-title-sub">
          <p>Manage your account</p>
        </div>
      </div>
    </div>
  </div>
</section>
<section class="profile-page">
  <div class="container-fluid">
    <div class="row">
      <?php require_once(Yii::$app->basePath . '/views/user/_left_menu.php');?>
      <div class="wrap-profile-right col col-lg-8 col-md-9 col-sm-12 col-12">
        <div class="profile-list">
          <div class="top-profile-list">
            <div class="left-top-profile-list">
              <!-- <span class="number-page font-weight-bold">1 - 5</span>
              <span class="text-page">of 20 Orders</span> -->
            </div>
            <div class="right-top-profile-list">
              <!-- LinkPager::widget([
                'pagination' => $pages, 
                'maxButtonCount' => 1, 
                'hideOnSinglePage' => false,
                'linkOptions' => ['class' => 'page-link'],
                'pageCssClass' => 'page-item',
                // 'prevPageCssClass' => 'page-item btn-pagi',
                // 'prevPageLabel' => '<span aria-hidden="true"><i class="fas fa-caret-left"></i></span><span class="sr-only">Previous</span>',
                // 'nextPageCssClass' => 'page-item btn-pagi',
                // 'nextPageLabel' => '<span aria-hidden="true"><i class="fas fa-caret-right"></i></span><span class="sr-only">Next</span>',
              ]); -->
              <!-- <ul class="pagination">
                <li class="page-item btn-pagi">
                  <a class="page-link btn-pagi" href="#" aria-label="Previous">
                  <span aria-hidden="true"><i class="fas fa-caret-left"></i></span>
                  <span class="sr-only">Previous</span>
                  </a>
                </li>
                <li class="page-item"><a class="page-link" href="#">1/10</a></li>
                <li class="page-item btn-pagi">
                  <a class="page-link " href="#" aria-label="Next">
                  <span aria-hidden="true"><i class="fas fa-caret-right"></i></span>
                  <span class="sr-only">Next</span>
                  </a>
                </li>
              </ul> -->
              <!-- <select id="inputState" class="form-control">
                <option selected>Sort by</option>
                <option>Name</option>
              </select> -->
            </div>
          </div>
          <table class="table table-bordered">
            <thead>
              <tr>
                <th scope="col">#</th>
                <th scope="col">Game</th>
                <th scope="col">Amount</th>
                <th scope="col">Created date</th>
                <th scope="col">Status</th>
              </tr>
            </thead>
            <tbody>
              <?php if (!$models) :?>
              <tr><td colspan="5">No data found</td></tr>
              <?php endif;?>
              <?php foreach ($models as $model) :?>
              <tr>
                <td><a href="<?=Url::to(['user/detail', 'id' => $model->id]);?>" data-pjax="0"><?=$model->id;?></a></td>
                <td><?=$model->game_title;?></td>
                <td>$<?=number_format($model->total_price);?></td>
                <td><?=$model->created_at;?></td>
                <td><?=$model->getStatusLabel();?></td>
              </tr>
              <?php endforeach;?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</section>

<?php
$script = <<< JS
JS;
$this->registerJs($script);
?>
