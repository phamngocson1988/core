<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use backend\models\ThreadTransaction;

?>
<!-- BEGIN PAGE BAR -->
<div class="page-bar">
  <ul class="page-breadcrumb">
    <li>
      <a href="/"><?=Yii::t('app', 'home')?></a>
      <i class="fa fa-circle"></i>
    </li>
    <li>
      <a href="<?=Url::to(['bank-transaction/index'])?>"> Các giao dịch tiền mặt</a>
      <i class="fa fa-circle"></i>
    </li>
    <li>
      <span>Chuyển tiền mặt cho nhân viên khác</span>
    </li>
  </ul>
</div>
<!-- END PAGE BAR -->
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title">Chuyển tiền mặt từ nhân viên <strong><?=$account->account_name;?></strong> đến một nhân viên khác</h1>
<!-- END PAGE TITLE-->
<div class="row">
  <div class="col-md-12">
    <?php $form = ActiveForm::begin(['options' => ['class' => 'form-horizontal form-row-seperated']]);?>
      <div class="portlet">
        <div class="portlet-title">
          <div class="caption">
              <i class="fa fa-money font-green"></i>
              <span class="caption-subject bold font-green uppercase"> <?=$account->account_name;?></span>
              <div class="caption-desc font-grey-cascade"> Số tiền mặt hiện có trong <?=$account->account_name;?>: <pre class="mt-code"><?=sprintf("%s %s", number_format($account_amount), $account->bank->currency);?></pre> 
              </div>
          </div>
          <div class="actions btn-set">
            <button type="submit" class="btn btn-warning" name="status" value="pending"><i class="fa fa-plus"></i> Tạo giao dịch tạm</button>
            <button type="submit" class="btn btn-success" name="status" value="completed"><i class="fa fa-check"></i> Tạo giao dịch</button>
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
                  <?=$form->field($model, 'amount', [
                    'labelOptions' => ['class' => 'col-md-2 control-label'],
                    'template' => '{label}<div class="col-md-6">{input}{hint}{error}</div>',
                    'parts' => ['{hint}' => sprintf("Số tiền mặt hiện có trong %s: %s %s", $account->account_name, number_format($account_amount), $root->bank->currency)],
                  ])->textInput()?>

                  <?=$form->field($model, 'other_bank_account_id', [
                    'labelOptions' => ['class' => 'col-md-2 control-label'],
                    'template' => '{label}<div class="col-md-6">{input}{hint}{error}</div>'
                  ])->dropdownList($model->fetchAccount())?>

                  <?=$form->field($model, 'description', [
                    'labelOptions' => ['class' => 'col-md-2 control-label'],
                    'template' => '{label}<div class="col-md-6">{input}{hint}{error}</div>'
                  ])->textArea()?>
                  
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <?php ActiveForm::end()?>
  </div>
</div>