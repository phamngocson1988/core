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
        <div class="profile-list">
          <div class="top-profile-list">
            <div class="left-top-profile-list">
              <span class="number-page font-weight-bold"><?=$pages->offset + 1;?> - <?=min($pages->offset + $pages->limit, $pages->totalCount);?></span>
              <span class="text-page">of <?=number_format($pages->totalCount);?> transactions</span>
            </div>
            <div class="right-top-profile-list">
              <?=LinkPager::widget([
                'pagination' => $pages, 
                'maxButtonCount' => 1, 
                'hideOnSinglePage' => false,
                'linkOptions' => ['class' => 'page-link'],
                'pageCssClass' => 'page-item',
              ]);?>
              <?php $form = ActiveForm::begin(['method' => 'get']); ?>
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
                <th scope="col">Date</th>
                <th scope="col">Amount</th>
                <th scope="col">Method</th>
                <th scope="col">Bank Invoice</th>
                <th scope="col">Status</th>
              </tr>
            </thead>
            <tbody>
              <?php if (!$models) :?>
              <tr><td colspan="6">No data found</td></tr>
              <?php endif;?>
              <?php foreach ($models as $model) :?>
                <tr>
                  <td><?=$model->getId();?></td>
                  <td><?=$model->created_at;?></td>
                  <td>$<?=number_format($model->total_price);?></td>
                  <td><?=$model->payment_method;?></td>
                  <td>
                  <?php if (!$model->evidence) : ?>
                  <?php $form = ActiveForm::begin([
                      'action' => ['user/evidence', 'id' => $model->id],
                      'options' => ['enctype' => 'multipart/form-data', 'class' => 'upload-form']
                  ]); ?>
                  <?=Html::fileInput("evidence", null, ['class' => 'file_upload', 'id' => 'evidence' . $model->id, 'style' => 'display:none']);?>
                  <?=Html::a('Upload Receipt', 'javascript:;', ['class' => 'action-link normal-link']);?>
                  <?php ActiveForm::end(); ?>
                  <?php else : ?>
                  <a href="<?=$model->evidence;?>" class="normal-link" target="_blank">View Receipt</a>
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
</section>

<?php
$script = <<< JS
$('.file_upload').on('change', function() {
  $(this).closest('form').submit();
});
$('.action-link').on('click', function() {
  $(this).closest('form').find('.file_upload').trigger('click');
});
$('#status').on('change', function(){
  $(this).closest('form').submit();
});
JS;
$this->registerJs($script);
?>
