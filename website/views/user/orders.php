<?php
use yii\widgets\Pjax;
use yii\widgets\LinkPager;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\helpers\Html;
use frontend\models\Order;
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
              <span class="number-page font-weight-bold"><?=$pages->offset + 1;?> - <?=min($pages->offset + $pages->limit, $pages->totalCount);?></span>
              <span class="text-page">of <?=number_format($pages->totalCount);?> Orders</span>
            </div>
            <div class="right-top-profile-list">
              <?=LinkPager::widget([
                'pagination' => $pages, 
                'maxButtonCount' => 1, 
                'hideOnSinglePage' => false,
                'linkOptions' => ['class' => 'page-link'],
                'pageCssClass' => 'page-item',
              ]);?>
              <?php $form = ActiveForm::begin(['method' => 'get', 'action' => ['user/orders']]); ?>
                <?= $form->field($filterForm, 'status', [
                  'options' => ['tag' => false],
                  'template' => '{input}',
                  'inputOptions' => ['class' => 'form-control', 'name' => 'status', 'id' => 'status']
                ])->dropDownList($filterForm->fetchStatusList(), ['prompt' => 'All']);?>
              <?php $form = ActiveForm::end(); ?>
            </div>
          </div>
          <table class="table table-bordered">
            <thead>
              <tr>
                <th scope="col">No</th>
                <th scope="col">Game</th>
                <th scope="col">Amount</th>
                <th scope="col">No. of Packages</th>
                <th scope="col">Status</th>
                <th scope="col">Bank Invoice</th>
              </tr>
            </thead>
            <tbody>
              <?php if (!$models) :?>
              <tr><td colspan="6">No data found</td></tr>
              <?php endif;?>
              <?php foreach ($models as $model) :?>
              <tr>
                <td>
                  <a href="<?=Url::to(['user/detail', 'id' => $model->id]);?>" data-pjax="0" class="normal-link" style="display:block; clear: left; line-height: 10px;"><?=$model->id;?></a>
                  <i style="font-size:13px; color:#CCC"><?=$model->created_at;?></i>
                </td>
                <td><?=$model->game_title;?></td>
                <td>
                  $<?=number_format($model->total_price);?>
                  <?php if (!in_array($model->currency, ['USD', 'KINGGEMS'])) : ?>
                  <i style="font-size:13px; color: #CCC"><?=$model->currency;?>/<?=number_format($model->total_price_by_currency, 1);?></i>
                  <?php endif; ?>
                </td>
                <td><?=number_format($model->quantity, 1);?></td>
                <td><?=$model->getStatusLabel();?></td>
                <td>
                  <?php if (!$model->evidence) : ?>
                  <?php $form = ActiveForm::begin([
                      'action' => ['user/order-evidence', 'id' => $model->id],
                      'options' => ['enctype' => 'multipart/form-data', 'class' => 'upload-form']
                  ]); ?>
                  <?=Html::fileInput("evidence", null, ['class' => 'file_upload', 'id' => 'evidence' . $model->id, 'style' => 'display:none', 'accept' =>"image/*"]);?>
                  <?=Html::a('Upload Receipt', 'javascript:;', ['class' => 'action-link normal-link']);?>
                  <?php ActiveForm::end(); ?>
                  <?php else : ?>
                  <a href="<?=$model->evidence;?>" class="normal-link" target="_blank">View Receipt</a> | 
                  <a href="<?=Url::to(['user/remove-order-evidence', 'id' => $model->id]);?>" class="normal-link remove-link">Remove</a>
                  <?php endif;?>
                </td>
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
$('#status').on('change', function(){
  $(this).closest('form').submit();
});

// Upload evidence
$('.file_upload').on('change', function() {
  $(this).closest('form').submit();
});
$('.action-link').on('click', function() {
  $(this).closest('form').find('.file_upload').trigger('click');
});
$('#status').on('change', function(){
  $(this).closest('form').submit();
});
$('.remove-link').ajax_action({
  method: 'POST',
  confirm: true,
  confirm_text: 'Do you want to remove this receipt?',
  callback: function(eletement, data) {
    location.reload();
  }
});
JS;
$this->registerJs($script);
?>
