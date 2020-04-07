<?php
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use common\components\helpers\CommonHelper;
$currentCurrency = ArrayHelper::getColumn($models, 'currency');
?>
<!-- BEGIN PAGE BAR -->
<div class="page-bar">
  <ul class="page-breadcrumb">
    <li>
      <a href="/"><?=Yii::t('app', 'home')?></a>
      <i class="fa fa-circle"></i>
    </li>
    <li>
      <span>Quỹ tiền mặt</span>
    </li>
  </ul>
</div>
<!-- END PAGE BAR -->
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title">Quỹ tiền mặt</h1>
<!-- END PAGE TITLE-->
<div class="row">
  <div class="col-md-12">
    <!-- BEGIN EXAMPLE TABLE PORTLET-->
    <div class="portlet light">
      <div class="portlet-title">
        <div class="actions">
          <?php $currencyList = CommonHelper::fetchCurrency(); ;?>
          <?php foreach ($currencyList as $currencyCode => $currencyName) : ?>
            <?php if (!in_array($currencyCode, $currentCurrency)) : ?>
              <a class="btn green link-action" href="<?=Url::to(['cash/create', 'currency' => $currencyCode]);?>">Tạo quỹ tiền mặt <?=$currencyCode;?></a>
            <?php endif;?>
          <?php endforeach;?>
        </div>
      </div>
      <div class="table-responsive">
        <table class="table table-striped table-bordered table-hover table-checkable">
          <thead>
            <tr>
              <th> Quỹ tiền mặt </th>
              <th> Tiền tệ </th>
              <th> Tổng giá trị </th>
              <th class="dt-center"> <?=Yii::t('app', 'actions');?> </th>
            </tr>
          </thead>
          <tbody>
              <?php if (!$models) :?>
              <tr><td colspan="4"><?=Yii::t('app', 'no_data_found');?></td></tr>
              <?php endif;?>
              <?php foreach ($models as $model) :?>
              <tr>
                <td class="center"><?=$model->name;?></td>
                <td class="center"><?=$model->currency;?></td>
                <td class="center">
                  <?=number_format(ArrayHelper::getValue($report, $model->currency, 0));?>
                </td>
                <td class="center">
                  <a class="btn btn-md blue tooltips" href="<?=Url::to(['cash-transaction/index', 'bank_id' => $model->id]);?>" data-container="body" data-original-title="Xem chi tiết"><i class="fa fa-eye"></i> Xem</a>
                </td>
              </tr>
              <?php endforeach;?>
          </tbody>
        </table>
      </div>
    </div>
    <!-- END EXAMPLE TABLE PORTLET-->
  </div>
</div>
<?php
$script = <<< JS
$(".link-action").ajax_action({
  confirm: true,
  confirm_text: 'Bạn có muốn tạo quỹ tiền mặt này?',
  callback: function(eletement, data) {
    location.reload();
  },
  error: function(element, errors) {
      alert(errors);
  },
});
JS;
$this->registerJs($script);
?>