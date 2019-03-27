<?php
use yii\widgets\LinkPager;
use yii\widgets\Pjax;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\web\JsExpression;
use dosamigos\datepicker\DateRangePicker;

$this->registerCssFile('vendor/assets/global/plugins/bootstrap-select/css/bootstrap-select.css', ['depends' => ['\yii\bootstrap\BootstrapAsset']]);
$this->registerCssFile('vendor/assets/apps/css/todo.min.css', ['depends' => ['\yii\bootstrap\BootstrapAsset']]);
$this->registerJsFile('vendor/assets/global/plugins/bootstrap-select/js/bootstrap-select.min.js', ['depends' => '\backend\assets\AppAsset']);
$this->registerJsFile('vendor/assets/pages/scripts/components-bootstrap-select.min.js', ['depends' => '\backend\assets\AppAsset']);
?>
<style>
.hide-text {
    white-space: nowrap;
    width: 100%;
    max-width: 500px;
    text-overflow: ellipsis;
    overflow: hidden;
}
</style>
<!-- BEGIN PAGE BAR -->
<div class="page-bar">
  <ul class="page-breadcrumb">
    <li>
      <a href="/"><?=Yii::t('app', 'home')?></a>
      <i class="fa fa-circle"></i>
    </li>
    <li>
      <span><?=Yii::t('app', 'manage_tasks')?></span>
    </li>
  </ul>
</div>
<!-- END PAGE BAR -->
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title"><?=Yii::t('app', 'manage_tasks')?></h1>
<!-- END PAGE TITLE-->
<div class="row">
  <div class="col-md-12">
    <!-- BEGIN EXAMPLE TABLE PORTLET-->
    <div class="portlet light bordered">
      <div class="portlet-title">
        <div class="caption font-dark">
          <i class="icon-settings font-dark"></i>
          <span class="caption-subject bold uppercase"> <?=Yii::t('app', 'manage_tasks')?></span>
        </div>
        <div class="actions">
          <div class="btn-group btn-group-devided">
            <?php if (Yii::$app->user->can('admin')):?>
            <a class="btn purple" href="<?=Url::to(['order/index', 'handler_id' => 'not', 'status' => Order::STATUS_PENDING])?>">Chưa có người quản lý</a>
            <?php elseif (Yii::$app->user->can('handle')):?>
            <a class="btn purple" href="<?=Url::to(['order/index', 'handler_id' => 'not', 'status' => Order::STATUS_PENDING])?>">Chưa có người quản lý</a>
            <a class="btn purple" href="<?=Url::to(['order/index', 'handler_id' => Yii::$app->user->id])?>">Những đơn hàng của tôi</a>
            <?php elseif (Yii::$app->user->can('sale')):?>
            <a class="btn purple" href="<?=Url::to(['order/index', 'saler_id' => Yii::$app->user->id])?>">Những đơn hàng của tôi</a>
            <?php endif;?>
          </div>
          <div class="btn-group btn-group-devided">
            <a class="btn green" href="<?=Url::to(['order/create', 'ref' => $ref])?>"><?=Yii::t('app', 'add_new')?></a>
          </div>
        </div>
      </div>
      <div class="portlet-body">
        <div class="row margin-bottom-10">
          <?php $form = ActiveForm::begin(['method' => 'GET', 'action' => ['order/index']]);?>     
            <?php $customer = $search->getCustomer();?>
            <?=$form->field($search, 'customer_id', [
              'options' => ['class' => 'form-group col-md-2'],
            ])->widget(kartik\select2\Select2::classname(), [
              'initValueText' => ($search->customer_id) ? sprintf("%s - %s", $customer->username, $customer->email) : '',
              'options' => ['class' => 'form-control', 'name' => 'customer_id'],
              'pluginOptions' => [
                'placeholder' => Yii::t('app', 'select_customer'),
                'allowClear' => true,
                'minimumInputLength' => 3,
                'ajax' => [
                    'url' => Url::to(['user/suggestion']),
                    'dataType' => 'json',
                    'processResults' => new JsExpression('function (data) {return {results: data.data.items};}')
                ]
              ]
            ])->label('Khách hàng')?>

            <?php $game = $search->getGame();?>
            <?=$form->field($search, 'game_id', [
              'options' => ['class' => 'form-group col-md-2'],
            ])->widget(kartik\select2\Select2::classname(), [
              'initValueText' => ($search->game_id) ? $game->title : '',
              'options' => ['class' => 'form-control', 'name' => 'game_id'],
              'pluginOptions' => [
                'placeholder' => Yii::t('app', 'select_game'),
                'allowClear' => true,
                'minimumInputLength' => 3,
                'ajax' => [
                    'url' => Url::to(['game/suggestion']),
                    'dataType' => 'json',
                    'processResults' => new JsExpression('function (data) {return {results: data.data.items};}')
                ]
              ]
            ])->label('Game')?>

            <?=$form->field($search, 'start_date', [
              'options' => ['class' => 'form-group col-md-2'],
            ])->widget(DateRangePicker::className(), [
              'attributeTo' => 'end_date', 
              'labelTo' => '-',
              'form' => $form,
              'clientOptions' => [
                  'autoclose' => true,
                  'format' => 'yyyy-mm-dd',
                  'keepEmptyValues' => true,
                  'todayHighlight' => true
              ]
            ])->label('Ngày tạo')?>

            <div class="form-group col-md-2">
              <label><?=Yii::t('app', 'status')?>: </label> 
              <select class="bs-select form-control" name="status[]" multiple="true" >
                <?php foreach ($search->getStatus() as $statusKey => $statusLabel) :?>
                <option value="<?=$statusKey?>" <?= in_array($statusKey, (array)$search->status) ? "selected" : ''?> ><?=$statusLabel?></option>
                <?php endforeach;?>
              </select>
            </div>



            <div class="form-group col-md-2">
              <button type="submit" class="btn btn-success table-group-action-submit" style="margin-top: 25px;">
                <i class="fa fa-check"></i> <?=Yii::t('app', 'search')?>
              </button>
            </div>
          <?php ActiveForm::end()?>
        </div>
        
        <?php Pjax::begin(); ?>
        <table class="table table-striped table-bordered table-hover table-checkable">
          <thead>
            <tr>
              <th style="width: 5%;"> <?=Yii::t('app', 'no');?> </th>
              <th style="width: 10%;"> <?=Yii::t('app', 'title');?> </th>
              <th style="width: 10%;"> <?=Yii::t('app', 'created_date');?> </th>
              <th style="width: 5%;"> <?=Yii::t('app', 'total');?> </th>
              <th style="width: 10%;"> <?=Yii::t('app', 'status');?> </th>
              <th style="width: 10%;" class="dt-center"> <?=Yii::t('app', 'actions');?> </th>
            </tr>
          </thead>
          <tbody>
              <?php if (!$models) :?>
              <tr><td colspan="6"><?=Yii::t('app', 'no_data_found');?></td></tr>
              <?php endif;?>
              <?php foreach ($models as $model) :?>
              <tr>
                <td style="vertical-align: middle;">Order #<?=$model->id;?></td>
                <td style="vertical-align: middle;"><?=$model->customer_name;?></td>
                <td style="vertical-align: middle;"><?=$model->created_at;?></td>
                <td style="vertical-align: middle;">$<?=$model->total_price;?></td>
                <td style="vertical-align: middle;"><?=$model->status;?></td>
                <td style="vertical-align: middle;">
                    <a href="javascript:;" target="_blank" class="btn btn-xs grey-salsa"><i class="fa fa-eye"></i></a>
                    <a href='<?=Url::to(['order/delete', 'id' => $model->id, 'ref' => $ref]);?>' class="btn btn-xs grey-salsa delete-action"><i class="fa fa-trash-o"></i></a>
                </td>
              </tr>
              <?php endforeach;?>
          </tbody>
        </table>
        <?=LinkPager::widget(['pagination' => $pages])?>
        <?php Pjax::end(); ?>
      </div>
    </div>
    <!-- END EXAMPLE TABLE PORTLET-->
  </div>
</div>
<?php
$script = <<< JS
$(".delete-action").ajax_action({
  confirm: true,
  confirm_text: 'Do you want to delete this order?',
  callback: function(eletement, data) {
    location.reload();
  }
});
JS;
$this->registerJs($script);
?>