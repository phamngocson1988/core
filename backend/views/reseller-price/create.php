<?php
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\web\JsExpression;
use common\components\helpers\StringHelper;

$games = $model->fetchGames();
$gameTitles = ArrayHelper::map($games, 'id', 'title');
$gameOptions = ArrayHelper::map($games, 'id', function($game) {
  return [
    'data-amplitude' => StringHelper::numberFormat($game->reseller_price_amplitude, 1),
    'data-supplier-price' => StringHelper::numberFormat($game->price1 + $game->expected_profit, 1)
  ];
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
<?php $form = ActiveForm::begin(['options' => ['class' => 'form-horizontal form-row-seperated']]);?>
<div class="row">
  <div class="col-md-12">
      <div class="portlet">
        <div class="portlet-title">
          <div class="actions btn-set">
            <a href="<?=Url::to(['reseller-price/index']);?>" class="btn default">
            <i class="fa fa-angle-left"></i> <?=Yii::t('app', 'back')?></a>
            <button type="button" class="btn btn-success" data-toggle="modal" data-target="#code-request">
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
                    <label class="col-md-2 control-label">Biên độ</label>
                    <div class="col-md-6">
                      <input type="text" id="game-amplitude" disabled readonly class="form-control" aria-required="true" aria-invalid="true">
                    </div>
                  </div>
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
  </div>
</div>

<div class="modal fade" id="code-request" tabindex="-1" role="basic" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
        <h4 class="modal-title">Nhập mã đề xuất</h4>
      </div>
      <div class="modal-body"> 
        <div class="row">
          <div class="col-md-12">
            <?=$form->field($model, 'change_price_request_code', ['options' => ['class' => '']])->textInput()->label('Mã đề xuất')?>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-default" data-toggle="modal"><i class="fa fa-send"></i> Lưu</button>
        <button type="button" class="btn dark btn-outline" data-dismiss="modal">Close</button>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<?php ActiveForm::end()?>

<?php
$script = <<< JS
$('#game_id').on('change', function(){
  var val = $(this).val();
  var optAmplitude = $(this).find(":selected").data('amplitude');
  var optPrice = $(this).find(":selected").data('supplier-price');
  
  $('#game-amplitude').val(optAmplitude || 0);
  $('#game-supplier-price').val(optPrice || 0);
});
$('#game_id').trigger('change');
JS;
$this->registerJs($script);
?>