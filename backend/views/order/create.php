<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use dosamigos\datepicker\DatePicker;
use dosamigos\datepicker\DateRangePicker;
use yii\web\JsExpression;
use common\models\Game;
use common\models\Product;
?>
<!-- BEGIN PAGE BAR -->
<div class="page-bar">
  <ul class="page-breadcrumb">
    <li>
      <a href="/"><?=Yii::t('app', 'home')?></a>
      <i class="fa fa-circle"></i>
    </li>
    <li>
      <a href="<?=Url::to(['task/index'])?>">Quản lý đơn hàng</a>
      <i class="fa fa-circle"></i>
    </li>
    <li>
      <span>Tạo đơn hàng</span>
    </li>
  </ul>
</div>
<!-- END PAGE BAR -->
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title">Tạo đơn hàng</h1>
<!-- END PAGE TITLE-->
<div class="row">
  <div class="col-md-12">
    <?php $form = ActiveForm::begin(['options' => ['class' => 'form-horizontal form-row-seperated']]);?>
      <div class="portlet">
        <div class="portlet-title">
          <div class="actions btn-set">
            <a href="{$back}" class="btn default">
            <i class="fa fa-angle-left"></i> <?=Yii::t('app', 'back')?></a>
            <button type="submit" class="btn btn-success">
            <i class="fa fa-check"></i> <?=Yii::t('app', 'save')?>
            </button>
          </div>
        </div>
        <div class="portlet-body">
          <div class="tabbable-bordered">
            <ul class="nav nav-tabs">
              <li class="active">
                <a href="#tab_general" data-toggle="tab"> <?=Yii::t('app', 'main_content')?></a>
              </li>
            </ul>
            <div class="tab-content">
              <div class="tab-pane active" id="tab_general">
                <div class="form-body">
                  <?php $customer = $order->customer;?>
                  <?=$form->field($order, 'customer_id', [
                    'labelOptions' => ['class' => 'col-md-2 control-label'],
                    'template' => '{label}<div class="col-md-6">{input}{hint}{error}</div>'
                  ])->widget(kartik\select2\Select2::classname(), [
                    'initValueText' => ($customer) ? sprintf("%s - %s", $customer->username, $customer->email) : '',
                    'options' => ['class' => 'form-control'],
                    'pluginOptions' => [
                      'placeholder' => 'Tìm khách hàng',
                      'allowClear' => true,
                      'minimumInputLength' => 3,
                      'ajax' => [
                          'url' => Url::to(['user/suggestion']),
                          'dataType' => 'json',
                          'processResults' => new JsExpression('function (data) {return {results: data.data.items};}')
                      ]
                    ]
                  ])->label('Khách hàng')?>
                  <hr/>
                  <h4>Thông tin game</h4>
                  <?php 
                  $games = Game::find()->where(['<>', 'status', Game::STATUS_DELETE])->all();
                  $games = ArrayHelper::map($games, 'id', 'title');
                  ?>
                  <?=$form->field($item, 'game_id', [
                    'labelOptions' => ['class' => 'col-md-2 control-label'],
                    'template' => '{label}<div class="col-md-6">{input}{hint}{error}</div>',
                    'inputOptions' => ['id' => 'game', 'class' => 'form-control']
                  ])->dropDownList($games)->label('Game');?>

                  <?php
                  $products = Product::find()->where(['<>', 'status', Product::STATUS_DELETE])->all();
                  $productMeta = [];
                  foreach ($products as $product) {
                    $productMeta[$product->id] = ['game_id' => $product->game_id];
                  }
                  $productItems = ArrayHelper::map($products, 'id', 'title');
                  ?>

                  <?=$form->field($item, 'product_id', [
                    'labelOptions' => ['class' => 'col-md-2 control-label'],
                    'template' => '{label}<div class="col-md-6">{input}{hint}{error}</div>',
                    'inputOptions' => ['id' => 'product', 'class' => 'form-control']
                  ])->dropDownList($productItems, ['prompt' => Yii::t('app', 'choose'), 'options' => $productMeta])->label('Gói game');?>

                  <?=$form->field($item, 'quantity', [
                    'labelOptions' => ['class' => 'col-md-2 control-label'],
                    'inputOptions' => ['id' => 'name', 'class' => 'form-control', 'type' => 'number'],
                    'template' => '{label}<div class="col-md-6">{input}{hint}{error}</div>'
                  ])->textInput()?>

                  <?=$form->field($item, 'username', [
                    'labelOptions' => ['class' => 'col-md-2 control-label'],
                    'inputOptions' => ['id' => 'name', 'class' => 'form-control'],
                    'template' => '{label}<div class="col-md-6">{input}{hint}{error}</div>'
                  ])->textInput()?>
                  <?=$form->field($item, 'password', [
                    'labelOptions' => ['class' => 'col-md-2 control-label'],
                    'inputOptions' => ['id' => 'name', 'class' => 'form-control'],
                    'template' => '{label}<div class="col-md-6">{input}{hint}{error}</div>'
                  ])->textInput()?>
                  <?=$form->field($item, 'platform', [
                    'labelOptions' => ['class' => 'col-md-2 control-label'],
                    'inputOptions' => ['id' => 'name', 'class' => 'form-control'],
                    'template' => '{label}<div class="col-md-6">{input}{hint}{error}</div>'
                  ])->dropDownList(['ios' => 'Ios', 'android' => 'Android'])?>
                  <?=$form->field($item, 'login_method', [
                    'labelOptions' => ['class' => 'col-md-2 control-label'],
                    'inputOptions' => ['id' => 'name', 'class' => 'form-control'],
                    'template' => '{label}<div class="col-md-6">{input}{hint}{error}</div>'
                  ])->dropDownList(['google' => 'Google', 'facebook' => 'Facebook'])?>
                  <?=$form->field($item, 'character_name', [
                    'labelOptions' => ['class' => 'col-md-2 control-label'],
                    'inputOptions' => ['id' => 'name', 'class' => 'form-control'],
                    'template' => '{label}<div class="col-md-6">{input}{hint}{error}</div>'
                  ])->textInput()?>
                  <?=$form->field($item, 'recover_code', [
                    'labelOptions' => ['class' => 'col-md-2 control-label'],
                    'inputOptions' => ['id' => 'name', 'class' => 'form-control'],
                    'template' => '{label}<div class="col-md-6">{input}{hint}{error}</div>'
                  ])->textInput()?>
                  <?=$form->field($item, 'server', [
                    'labelOptions' => ['class' => 'col-md-2 control-label'],
                    'inputOptions' => ['id' => 'name', 'class' => 'form-control'],
                    'template' => '{label}<div class="col-md-6">{input}{hint}{error}</div>'
                  ])->textInput()?>
                  <?=$form->field($item, 'note', [
                    'labelOptions' => ['class' => 'col-md-2 control-label'],
                    'inputOptions' => ['id' => 'name', 'class' => 'form-control'],
                    'template' => '{label}<div class="col-md-6">{input}{hint}{error}</div>'
                  ])->textInput()?>

                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    <?php ActiveForm::end()?>
  </div>
</div>
<?php
$script = <<< JS
$('#product>option').hide();
$('#game').on('change', function(){
  $('#product').val('');
  $('#product>option').hide();
  $('#product>option[game_id='+$(this).val()+']').show();
  // $('#product').val($('#product>option[game_id='+$(this).val()+']:first-child').attr('value'));
});
$('#game').trigger('change');
JS;
$this->registerJs($script);
?>