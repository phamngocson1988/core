<?php
use yii\widgets\Pjax;
use yii\widgets\LinkPager;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\helpers\Html;
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
        <div class="profile-right" style="width: 100%;" id="reward-feed">
          <div class="profit-listing">
            <table class="table-custom table-hover">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Date</th>
                  <th>Amount</th>
                  <th>Method</th>
                  <th>Bank Invoice</th>
                  <th>Status</th>
                </tr>
              </thead>
              <tbody>
                <?php if (!$models) :?>
                <tr><td colspan="5">No data found</td></tr>
                <?php endif;?>
                <?php foreach ($models as $model) :?>
                <tr>
                  <td>#<?=$model->id;?></td>
                  <td><?=$model->created_at;?></td>
                  <td>$<?=number_format($model->total_price);?></td>
                  <td><?=$model->payment_method;?></td>
                  <td>
                  <?php if (!$model->isCompleted() && !$model->evidence) : ?>
                  <?php $form = ActiveForm::begin([
                      'action' => ['user/evidence', 'id' => $model->id],
                      'options' => ['enctype' => 'multipart/form-data', 'class' => 'upload-form']
                  ]); ?>
                  <?=Html::fileInput("evidence", null, ['class' => 'file_upload', 'id' => 'evidence' . $model->id, 'style' => 'display:none']);?>
                  <?=Html::a('Upload invoice', 'javascript:;', ['class' => 'action-link']);?>
                  <?php ActiveForm::end(); ?>
                  <?php elseif ($model->evidence) : ?>
                  <a href="<?=$model->evidence;?>" target="_blank">Invoice Image</a>
                  <?php endif;?>
                  </td>
                  <td><?=$model->status;?></td>
                </tr>
                <?php endforeach;?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<?php
$script = <<< JS
$('.file_upload').on('change', function() {
  $(this).closest('form').submit();
});
$('.action-link').on('click', function() {
  $(this).closest('form').find('.file_upload').trigger('click');
});
JS;
$this->registerJs($script);
?>
