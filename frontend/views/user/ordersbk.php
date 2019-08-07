<?php
use yii\widgets\Pjax;
use yii\widgets\LinkPager;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
?>
<section class="section section-lg bg-default text-center">
        <!-- section wave-->
  <div class="container">
    <div class="row justify-content-sm-center">
      <div class="col-md-12 col-xl-12">
        <h3>Your orders</h3>
        <?php Pjax::begin();?>
        <?php $form = ActiveForm::begin(['method' => 'get', 'action' => Url::to(['user/orders']), 'options' => ['class' => 'text-left']]); ?>
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
              <?= $form->field($filterForm, 'game_id', [
                'options' => ['class' => 'form-wrap'],
                'inputOptions' => ['class' => 'form-input select-filter', 'name' => 'game_id'],
                'errorOptions' => ['tag' => 'span', 'class' => 'form-validation'],
                'template' => '{input}{hint}{error}'
              ])->dropDownList($filterForm->fetchGames()) ?>
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
              <th>Game</th>
              <th>Amount</th>
              <th>Date</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody>
            <?php if (!$models) :?>
            <tr><td colspan="5">No data found</td></tr>
            <?php endif;?>
            <?php foreach ($models as $model) :?>
            <tr>
              <td><a href="<?=Url::to(['user/detail', 'id' => $model->id]);?>" data-pjax="0"><?=$model->id;?></a></td>
              <td><?=$model->item_title;?></td>
              <td>(K) <?=number_format($model->total_price);?></td>
              <td><?=$model->created_at;?></td>
              <td><?=$model->getStatusLabel();?></td>
            </tr>
            <?php endforeach;?>
          </tbody>
          <tfoot>
            <tr>
              <td style="vertical-align: middle; backgroun-color: #CCC" colspan="2"><strong>Tổng đơn hàng: <?=number_format($filterForm->getCommand()->count());?></strong></td>
              <td style="vertical-align: middle; backgroun-color: #CCC" colspan="3"><strong>Tổng King Coin: <?=number_format($filterForm->getCommand()->sum('total_price'));?></strong></td>
              </td>
            </tr>
          </tfoot>
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
JS;
$this->registerJs($script);
?>