<?php
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use backend\models\QuestionCategory;
use common\widgets\TinyMce;

$supplierBank = $model->getSupplierBank();
$bankList = $model->fetchBanks();
$supplier = $model->getSupplier();
$user = $supplier->user;
$emailName = explode("@", $user->email)[0];
$emailDomain = explode("@", $user->email)[1];
?>
<!-- BEGIN PAGE BAR -->
<div class="page-bar">
  <ul class="page-breadcrumb">
    <li>
      <a href="/"><?=Yii::t('app', 'home')?></a>
      <i class="fa fa-circle"></i>
    </li>
    <li>
      <a href="<?=Url::to(['bank/index'])?>">Tài khoản ngân hàng</a>
      <i class="fa fa-circle"></i>
    </li>
    <li>
      <span>Xác minh tài khoản ngân hàng</span>
    </li>
  </ul>
</div>
<!-- END PAGE BAR -->
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title">Xác minh tài khoản ngân hàng</h1>
<!-- END PAGE TITLE-->
<div class="row">
  <div class="col-md-12">
    <?php $form = ActiveForm::begin(['options' => ['class' => 'form-horizontal form-row-seperated']]);?>
      <div class="portlet">
        <div class="portlet-title">
          <div class="actions btn-set">
            <a href="<?=Url::to(['bank/index'])?>" class="btn default">
            <i class="fa fa-angle-left"></i> <?=Yii::t('app', 'back')?></a>
            <button type="submit" class="btn btn-success">
            <i class="fa fa-check"></i> Xác minh tài khoản
            </button>

            <a type="button" class="btn btn-warning" id="send-auth-key" href="<?=Url::to(['bank/send-validate-code', 'id' => $model->id]);?>"><i class="fa fa-paper-plane"></i> Gửi mã xác minh</a>
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
                  <div class="form-group field-createsupplierbankform-bank_code required has-success">
                    <label class="col-md-2 control-label" for="createsupplierbankform-bank_code">Bank Code</label>
                    <div class="col-md-6">
                      <select id="createsupplierbankform-bank_code" class="slug form-control" disabled="true" aria-required="true" aria-invalid="false">
                        <?php foreach ($bankList as $bankCode => $bankName) :?>
                          <option value="ABBANK" <?php if ($supplierBank->bank_code === $bankCode):?>selected<?php endif;?>><?=sprintf("(%s) %s", $bankCode, $bankName);?></option>
                        <?php endforeach;?>
                      </select>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-md-2 control-label">Account Number</label>
                    <div class="col-md-6">
                      <input type="text" class="form-control" disabled="true" value="<?=$supplierBank->account_number;?>" aria-required="true">
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-md-2 control-label">Account Name</label>
                    <div class="col-md-6">
                      <input type="text" class="form-control" disabled="true" value="<?=$supplierBank->account_name;?>" aria-required="true">
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-md-2 control-label">Province</label>
                    <div class="col-md-6">
                      <input type="text" class="form-control" disabled="true" value="<?=$supplierBank->province;?>">
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-md-2 control-label">City</label>
                    <div class="col-md-6">
                      <input type="text" class="form-control" disabled="true" value="<?=$supplierBank->city;?>">
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-md-2 control-label">Branch</label>
                    <div class="col-md-6">
                      <input type="text" class="form-control" disabled="true" value="<?=$supplierBank->branch;?>" >
                    </div>
                  </div>
                </div>
                <hr/>
                <div class="form-body">
                
                  <?=$form->field($model, 'auth_key', [
                    'labelOptions' => ['class' => 'col-md-2 control-label'],
                    'inputOptions' => ['id' => 'name', 'class' => 'form-control'],
                    'template' => '{label}<div class="col-md-6">{input}{hint}{error}</div>'
                  ])->textInput()->label('Mã xác minh')->hint(sprintf('Nhập mã code được gửi tới email %s@%s', str_pad(substr($emailName, 0, 3), strlen($emailName), '*'), $emailDomain));?>
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
$("#send-auth-key").ajax_action({
  confirm: true,
  callback: function(eletement, data) {
    toastr.success('Mã xác nhận đã được gửi tới email của bạn');
  },
  error: function(element, error) {
    toastr.error(error);
  }
});
JS;
$this->registerJs($script);
?>