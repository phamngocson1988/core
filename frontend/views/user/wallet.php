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
        <h3>Wallet</h3>
        <?php Pjax::begin();?>
        <?php $form = ActiveForm::begin(['method' => 'get', 'action' => Url::to(['user/wallet']), 'options' => ['class' => 'text-left']]); ?>
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
              <?= $form->field($filterForm, 'type', [
                'options' => ['class' => 'form-wrap'],
                'inputOptions' => ['class' => 'form-input select-filter', 'data-placeholder' => 'choose all', 'name' => 'type'],
                'errorOptions' => ['tag' => 'span', 'class' => 'form-validation'],
                'template' => '{input}{hint}{error}'
              ])->dropDownList($filterForm->getWalletType()) ?>
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
              <th>In/Out</th>
              <th>Coin</th>
              <th>Description</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody>
            <?php if (!$models) :?>
            <tr><td colspan="6">No data found</td></tr>
            <?php endif;?>
            <?php foreach ($models as $no => $model) :?>
            <tr>
              <td>#<?=($pages->offset + $no + 1)?></td>
              <td><?=$model->payment_at;?></td>
              <td><?=$model->getTypeLabel();?></td>
              <td>(K)<?=number_format($model->coin);?></td>
              <td><?=$model->description;?></td>
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
      <section class="section section-xs novi-background bg-cover text-center">
        <div class="container container-wide">
          <div class="box-cta-thin">
            <p class="big"><span class="label-cta label-cta-primary">Hot!</span><span>The number of your King Coin now is: </span><strong><?=number_format($coin);?></strong>&nbsp;&nbsp;<a class="link-bold" href="<?=Url::to(['pricing/index']);?>">Buy more!</a></p>
          </div>
        </div>
      </section>
    </div>
    <div class="form-button">
      <a href="<?=Url::to(['user/index']);?>" class="button button-primary button-nina">Back</a>
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