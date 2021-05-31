<?php
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use backend\models\QuestionCategory;
use common\widgets\TinyMce;

$withdrawRequest = $model->getWithdrawRequest();
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
      <a href="<?=Url::to(['withdraw/index'])?>">Yêu cầu rút tiền</a>
      <i class="fa fa-circle"></i>
    </li>
    <li>
      <span>Xác minh yêu cầu rút tiền</span>
    </li>
  </ul>
</div>
<!-- END PAGE BAR -->
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title">Xác minh yêu cầu rút tiền</h1>
<!-- END PAGE TITLE-->
<div class="row">
  <div class="col-md-12">
    <?php $form = ActiveForm::begin(['options' => ['class' => 'form-horizontal form-row-seperated']]);?>
      <div class="portlet">
        <div class="portlet-title">
          <div class="actions btn-set">
            <a href="<?=Url::to(['withdraw/index'])?>" class="btn default">
            <i class="fa fa-angle-left"></i> <?=Yii::t('app', 'back')?></a>
            <button type="submit" class="btn btn-success">
            <i class="fa fa-check"></i> Xác minh yêu cầu rút tiền
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
                  <div class="form-group field-bank_code">
                    <label class="col-md-2 control-label" for="bank_code">Bank Code</label>
                    <div class="col-md-6">
                      <input type="text" id="bank_code" class="form-control" value="<?=$withdrawRequest->bank_code;?>" disabled="true">
                    </div>
                  </div>
                  <div class="form-group field-account_number">
                    <label class="col-md-2 control-label" for="account_number">Account Number</label>
                    <div class="col-md-6">
                      <input type="text" id="account_number" class="form-control" value="<?=$withdrawRequest->account_number;?>" disabled="true">
                    </div>
                  </div>
                  <div class="form-group field-account_name">
                    <label class="col-md-2 control-label" for="account_name">Account Name</label>
                    <div class="col-md-6">
                      <input type="text" id="account_name" class="form-control" value="<?=$withdrawRequest->account_name;?>" disabled="true">
                    </div>
                  </div>
                  <div class="form-group field-createwithdrawrequestform-amount">
                    <label class="col-md-2 control-label" for="createwithdrawrequestform-amount">Amount</label>
                    <div class="col-md-6">
                      <input type="text" id="createwithdrawrequestform-amount" class="form-control" value="<?=$withdrawRequest->amount;?>" disabled="true">
                    </div>
                  </div>

                  <hr/>
                  <?=$form->field($model, 'auth_key', [
                    'labelOptions' => ['class' => 'col-md-2 control-label'],
                    'inputOptions' => ['class' => 'form-control'],
                    'template' => '{label}
                    <div class="col-md-6">
                      <div class="input-group">
                        {input}
                        <span class="input-group-btn">
                          <a type="button" class="btn btn-warning" id="send-auth-key" href="' . Url::to(['withdraw/send-validate-code', 'id' => $model->id]) . '"><i class="fa fa-paper-plane"></i> Gửi mã xác minh</a>
                        </span>
                      </div>
                      {hint}{error}
                    </div>'
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