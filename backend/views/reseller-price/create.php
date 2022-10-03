<?php
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\web\JsExpression;
use common\models\CurrencySetting;
$currencyModel = CurrencySetting::findOne(['code' => 'VND']);
$rate = (int)$currencyModel->exchange_rate;

$games = $model->fetchGames();
$gameTitles = ArrayHelper::map($games, 'id', 'title');
$gameOptions = ArrayHelper::map($games, 'id', function($game) use ($rate) {
  return ['data-supplier-price' => number_format((int)$game->price1 + ((int)$game->expected_profit / $rate), 1)];
});
?>
<!-- BEGIN PAGE BAR -->
<div class="page-bar">
  <ul class="page-breadcrumb">
    <li>
      <a href="/"><?=Yii::t('app', 'home')?></a>
      <i class="fa fa-circle"></i>
    </li>
    <li>
      <a href="<?=Url::to(['reseller-price/index']);?>">Giá Reseller</a>
      <i class="fa fa-circle"></i>
    </li>
    <li>
      <span>Tạo giá Reseller</span>
    </li>
  </ul>
</div>
<!-- END PAGE BAR -->
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title">Tạo giá Reseller</h1>
<!-- END PAGE TITLE-->
<div class="row">
  <div class="col-md-12">
  	<?php $form = ActiveForm::begin(['options' => ['class' => 'form-horizontal form-row-seperated']]);?>
      <div class="portlet">
        <div class="portlet-title">
          <div class="actions btn-set">
            <a href="<?=Url::to(['reseller-price/index']);?>" class="btn default">
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
                  <?=$form->field($model, 'reseller_id', [
                    'labelOptions' => ['class' => 'col-md-2 control-label'],
                    'inputOptions' => ['class' => 'slug form-control'],
                    'template' => '{label}<div class="col-md-6">{input}{hint}{error}</div>'
                  ])->widget(kartik\select2\Select2::classname(), [
                    'data' => $model->fetchResellers(),
                    'options' => ['class' => 'form-control', 'prompt' => 'Chọn Reseller', 'disabled' => $modeEdit],
                  ])->label('Reseller');?>
                  <?=$form->field($model, 'game_id', [
                    'labelOptions' => ['class' => 'col-md-2 control-label'],
                    'inputOptions' => ['class' => 'slug form-control', 'id' => 'game_id'],
                    'template' => '{label}<div class="col-md-6">{input}{hint}{error}</div>'
                    ])->widget(kartik\select2\Select2::classname(), [
                      'data' => $gameTitles,
                      'options' => ['class' => 'form-control', 'prompt' => 'Chọn Game', 'disabled' => $modeEdit, 'options' => $gameOptions],
                    ])->label('Game');?>
                  <?=$form->field($model, 'price', [
                    'labelOptions' => ['class' => 'col-md-2 control-label'],
                    'inputOptions' => ['id' => 'name', 'class' => 'form-control'],
                    'template' => '{label}<div class="col-md-6">{input}{hint}{error}</div>'
                  ])->textInput()->label('Giá Reseller (USD)')?>

                  <div class="form-group">
                    <label class="col-md-2 control-label">Giá bán chuẩn (USD)</label>
                    <div class="col-md-6">
                      <input type="text" id="game-supplier-price" disabled readonly class="form-control" aria-required="true" aria-invalid="true">
                    </div>
                  </div>
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
$('#game_id').on('change', function(){
  var val = $(this).val();
  var opt = $(this).find(":selected").data('supplier-price');
  $('#game-supplier-price').val(opt || 0);
});
$('#game_id').trigger('change');
JS;
$this->registerJs($script);
?>