<?php
use yii\widgets\Pjax;
use yii\widgets\LinkPager;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\helpers\Html;
use frontend\models\Order;
use dosamigos\datepicker\DatePicker;

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
          <?php $form = ActiveForm::begin(['method' => 'get', 'action' => Url::to(['reseller/order', 'status' => $status]), 'options' => ['class' => 't-form-wrap pd-lr-30 row', 'id' => 'filter-form']]); ?>
          <?= $form->field($filterForm, 'start_date', [
            'options' => ['class' => 'form-group col-md-6'],
            'inputOptions' => ['name' => 'start_date', 'autocomplete' => 'off', 'readonly' => 'true']
          ])->widget(DatePicker::className(), [
            'inline' => false, 
            'template' => '<div class="input-group date" data-provide="datepicker">{input}<div class="input-group-addon"><span class="glyphicon glyphicon-th"></span></div></div>',
            'clientOptions' => [
                'autoclose' => true,
                'format' => 'yyyy-mm-dd'
            ]
          ]);?>
          <?= $form->field($filterForm, 'end_date', [
            'options' => ['class' => 'form-group col-md-6'],
            'inputOptions' => ['name' => 'end_date', 'autocomplete' => 'off', 'readonly' => 'true']
          ])->widget(DatePicker::className(), [
            'inline' => false, 
            'template' => '<div class="input-group date" data-provide="datepicker">{input}<div class="input-group-addon"><span class="glyphicon glyphicon-th"></span></div></div>',
            'clientOptions' => [
                'autoclose' => true,
                'format' => 'yyyy-mm-dd'
            ]
          ]);?>
          
          <?php $form = ActiveForm::end(); ?>
          <div class="pd-lr-30 mb-3 float-right d-inline-block">
              <button class="btn-product-detail-add-to-cart" id="filter-button">Search</button>
          </div>
          <div class="pd-lr-30 mb-3 t-flex-between">
            <div class="t-wrap-number-pagination">
              <span class="number-page font-weight-bold"><?=$pages->offset + 1;?> - <?=min($pages->offset + $pages->limit, $pages->totalCount);?></span>
              <span class="text-page">of <?=number_format($pages->totalCount);?> Orders</span>
            </div>
            <div class="t-wrap-pagination">
              <?=LinkPager::widget([
                'pagination' => $pages, 
                'maxButtonCount' => 1, 
                'hideOnSinglePage' => false,
                'linkOptions' => ['class' => 'page-link'],
                'pageCssClass' => 'page-item',
              ]);?>
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
                  $<?=number_format($model->total_price, 1);?>
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
                  <?=Html::fileInput("evidence", null, ['class' => 'file_upload', 'id' => 'evidence' . $model->id, 'style' => 'display:none']);?>
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
            <tfoot>
              <tr>
                <td colspan="2">Total:</td>
                <td>$<?=number_format($filterForm->getCommand()->sum('total_price'), 1);?></td>
                <td><?=number_format($filterForm->getCommand()->sum('quantity'), 1);?></td>
                <td></td>
              </tr>
            </tfoot>
          </table>
        </div>
        </div>
    </div>
  </div>
</section>

<?php
$script = <<< JS
$('#filter-button').on('click', function(){
  $('#filter-form').submit();
});
// Upload evidence
$('.file_upload').on('change', function() {
  $(this).closest('form').submit();
});
$('.action-link').on('click', function() {
  $(this).closest('form').find('.file_upload').trigger('click');
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
