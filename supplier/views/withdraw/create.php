<?php
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use backend\models\QuestionCategory;
use common\widgets\TinyMce;
use supplier\behaviors\UserSupplierBehavior;
use supplier\models\SupplierWallet;
$supplier = $model->getSupplier();
?>
<!-- BEGIN PAGE BAR -->
<div class="page-bar">
  <ul class="page-breadcrumb">
    <li>
      <a href="/"><?=Yii::t('app', 'home')?></a>
      <i class="fa fa-circle"></i>
    </li>
    <li>
      <a href="<?=Url::to(['bank/index'])?>">Yêu cầu rút tiền</a>
      <i class="fa fa-circle"></i>
    </li>
    <li>
      <span>Tạo yêu cầu rút tiền</span>
    </li>
  </ul>
</div>
<!-- END PAGE BAR -->
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title">Tạo yêu cầu rút tiền</h1>

<div class="row">
  <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
      <div class="dashboard-stat blue">
          <div class="visual">
              <i class="fa fa-shopping-cart"></i>
          </div>
          <div class="details">
              <div class="number"> <?=number_format($supplier->walletTotalInput());?> VNĐ</div>
              <div class="desc"> Doanh Thu </div>
          </div>
          <a class="more" href="<?=Url::to(['wallet/index', 'type' => SupplierWallet::TYPE_INPUT]);?>"> Xem thêm
              <i class="m-icon-swapright m-icon-white"></i>
          </a>
      </div>
  </div>
  <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 margin-bottom-10">
      <div class="dashboard-stat green">
          <div class="visual">
              <i class="fa fa-briefcase fa-icon-medium"></i>
          </div>
          <div class="details">
              <div class="number"> <?=number_format($supplier->walletTotal());?> VNĐ</div>
              <div class="desc"> Số Dư Khả Dụng </div>
          </div>
          <a class="more" href="<?=Url::to(['wallet/index']);?>"> Xem thêm
              <i class="m-icon-swapright m-icon-white"></i>
          </a>
      </div>
  </div>
  <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
      <div class="dashboard-stat yellow">
          <div class="visual">
              <i class="fa fa-group fa-icon-medium"></i>
          </div>
          <div class="details">
              <div class="number"> <?=number_format($supplier->walletTotalOutput());?> VNĐ</div>
              <div class="desc"> Tổng tiền rút </div>
          </div>
          <a class="more" href="<?=Url::to(['wallet/index', 'type' => SupplierWallet::TYPE_OUTPUT]);?>"> Xem thêm
              <i class="m-icon-swapright m-icon-white"></i>
          </a>
      </div>
  </div>
</div>
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
                  <div class="form-group field-bank_id">
                    <label class="col-md-2 control-label" for="bank_id">Bank Id</label>
                    <div class="col-md-6">
                      <select id="bank_id" class="slug form-control">
                        <?php foreach ($model->fetchBanks() as $bank) : ?>
                        <option value="4" data-bank_code="<?=$bank->bank_code;?>" data-account_number="<?=$bank->account_number;?>" data-account_name="<?=$bank->account_name;?>"><?=sprintf("(%s) %s - %s", $bank->bank_code, $bank->account_name, $bank->account_number);?></option>
                        <?php endforeach;?>
                      </select>
                    </div>
                  </div>

                  <?=$form->field($model, 'bank_code', [
                    'labelOptions' => ['class' => 'col-md-2 control-label'],
                    'inputOptions' => ['id' => 'bank_code', 'class' => 'form-control', 'readonly' => true],
                    'template' => '{label}<div class="col-md-6">{input}{hint}{error}</div>'
                  ])->textInput()?>

                  <?=$form->field($model, 'account_number', [
                    'labelOptions' => ['class' => 'col-md-2 control-label'],
                    'inputOptions' => ['id' => 'account_number', 'class' => 'form-control', 'readonly' => true],
                    'template' => '{label}<div class="col-md-6">{input}{hint}{error}</div>'
                  ])->textInput()?>

                  <?=$form->field($model, 'account_name', [
                    'labelOptions' => ['class' => 'col-md-2 control-label'],
                    'inputOptions' => ['id' => 'account_name', 'class' => 'form-control', 'readonly' => true],
                    'template' => '{label}<div class="col-md-6">{input}{hint}{error}</div>'
                  ])->textInput()?>

                  <?=$form->field($model, 'amount', [
                    'labelOptions' => ['class' => 'col-md-2 control-label'],
                    'inputOptions' => ['class' => 'form-control'],
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
$('#bank_id').on('change', function(){
  var option = $(this).find(":selected");
  $('#account_name').val(option.data('account_name'));
  $('#account_number').val(option.data('account_number'));
  $('#bank_code').val(option.data('bank_code'));
});
$('#bank_id').trigger('change');
JS;
$this->registerJs($script);
?>
