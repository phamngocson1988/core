<?php
use yii\widgets\Pjax;
use yii\widgets\LinkPager;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\helpers\Html;
?>
<section class="section section-lg bg-default text-center">
        <!-- section wave-->
  <div class="container">
    <div class="row justify-content-sm-center">
      <div class="col-md-12 col-xl-12">
        <h3>Transactions</h3>
        <?php Pjax::begin();?>
        <?php $form = ActiveForm::begin(['method' => 'get', 'action' => Url::to(['user/transaction']), 'options' => ['class' => 'text-left']]); ?>
          <div class="row">
            <div class="col-md-3">
              <?= $form->field($filterForm, 'start_date', [
                'options' => ['class' => 'form-wrap'],
                'inputOptions' => ['class' => 'form-input date-picker'],
                'labelOptions' => ['class' => 'form-label'],
                'errorOptions' => ['tag' => 'span', 'class' => 'form-validation'],
                'template' => '{input}{label}{hint}{error}'
              ])->textInput(['name' => 'start_date']) ?>
            </div>
            <div class="col-md-3">
              <?= $form->field($filterForm, 'end_date', [
                'options' => ['class' => 'form-wrap'],
                'inputOptions' => ['class' => 'form-input date-picker'],
                'labelOptions' => ['class' => 'form-label'],
                'errorOptions' => ['tag' => 'span', 'class' => 'form-validation'],
                'template' => '{input}{label}{hint}{error}'
              ])->textInput(['name' => 'end_date']) ?>
            </div>
            <div class="col-md-3">
              <div class="form-button">
                <button class="button button-secondary button-nina" type="submit">Filter</button>
              </div>
            </div>
          </div>
        <?php ActiveForm::end(); ?>
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
              <td>#<?=$model->auth_key;?></td>
              <td><?=$model->created_at;?></td>
              <td>$<?=number_format($model->total_price);?></td>
              <td><?=$model->payment_method;?></td>
              <td>
              <?php $form = ActiveForm::begin([
                  'action' => ['user/evidence', 'id' => $model->id],
                  'options' => ['enctype' => 'multipart/form-data', 'class' => 'upload-form']
              ]); ?>
              <?=Html::fileInput("evidence", null, ['class' => 'file_upload']);?>
              <?=Html::submitButton('Upload', ['class' => 'cus-btn yellow fl-left apply-coupon-btn']);?>
              <?php ActiveForm::end(); ?>

              </td>
              <td><?=$model->status;?></td>
            </tr>
            <?php endforeach;?>
          </tbody>
        </table>
        <?=LinkPager::widget([
          'pagination' => $pages,
          'options' => ['class' => 'pagination-custom'],
          'linkContainerOptions' => ['class' => 'page-item'],
          'linkOptions' => ['class' => 'page-link'],
        ]);?>
        <?php Pjax::end();?>
      </div>
      <div class="form-button">
        <a href="<?=Url::to(['user/index']);?>" class="button button-primary button-nina">Back</a>
      </div>
    </div>
  </div>
</section>
<?php
$script = <<< JS
var cur= new Date();
$('.date-picker').bootstrapMaterialDatePicker({
  format: 'YYYY-MM-DD',
  maxDate: cur,
  minDate: moment().subtract(3, 'months'),
  time: false,
  year: false
});

// $('form.upload-form').on('submit', function() {
//   if (!document.getElementById("file_upload").files.length) return false;
// });
JS;
$this->registerJs($script);
?>