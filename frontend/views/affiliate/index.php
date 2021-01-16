<?php
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use frontend\widgets\LinkPager;
$user = Yii::$app->user->getIdentity();
$this->registerJsFile('https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.bundle.min.js', ['position' => \yii\web\View::POS_HEAD]);
?>
<div class="container profile profile-affiliate my-5">
  <div class="row">
    <div class="col-md-3">
      <div class="card card-info text-center">
        <img class="card-img-top" src="/images//icon/mask.svg" alt="Card image">
        <div class="card-body">
          <h4 class="card-title"><?=$user->name;?></h4>
          <p class="card-text">@<?=$user->username;?></p>
          <p class="font-weight-bold text-red">Balance: <?=number_format($user->walletBalance());?> KCOIN</p>
          <a href="#" class="btn btn-green" data-toggle="modal" data-target="#choosePayment">
            WITHDRAW
          </a>
        </div>
      </div>
      <!-- Modal Choose beneficiary account-->
      <div class="modal fade" id="choosePayment" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="">Choose beneficiary account</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <div class="d-flex mb-4">
                <button data-toggle="modal" data-target="#addBeneficiary" type="button" class="btn btn-green align-self-center mr-3">
                  <img class="icon-btn" src="/images//icon/more.svg"> Add more
                </button>
                <span class="align-self-center text-muted">(Maximum 4 accounts)</span>
              </div>
              <?php $form = ActiveForm::begin(['action' => Url::to(['affiliate/send-withdraw-request']), 'options' => ['id' => 'withdraw-form']]);?>
              <?= $form->field($withdrawForm, 'account_id', [
                'options' => ['class' => 'btn-group-toggle multi-choose d-flex beneficiary', 'data-toggle' => 'buttons'],
              ])->widget(\frontend\widgets\AffiliateAccountRadioListInput::className(), [
                'items' => $withdrawForm->fetchAccounts(),
                'options' => ['tag' => false]
              ])->label(false);?>
              <div class="input-group mt-4" style="max-width:300px">
                <?= $form->field($withdrawForm, 'amount', [
                  'template' => '{input}',
                  'options' => ['tag' => false],
                  'inputOptions' => ['class' => 'form-control', 'placeholder' => 'Withdraw Amount', 'aria-label' => 'Withdraw Amount', 'aria-describedby' => 'button-addon2', 'type' => 'number']
                ])->textInput()->label(false) ?>
                <div class="input-group-append">
                  <button class="btn btn-warning text-white" type="submit" id="button-addon2">Submit</button>
                </div>
              </div>
              <?php ActiveForm::end();?>
              <div class="note py-5">
                <p class="lead">Withdraw Policy</p>
                <p class="mb-0"><em>- Cut off time: 13:00 (GMT +7) Monday to Friday</em></p>
                <p class="mb-0"><em>- Payment time: 14:00 (GMT +7) Monday to Friday</em></p>
                <p class="mb-0"><em>- Transaction fee 5% of widthdraw amount</em></p>
                <p class="mb-2"><em>- Min amount = $50 (USD). Max account = $2000 (USD)</em></p>
                <p class="">Note:</p>
                <p class="mb-0"><em>The transaction are made after 13:00 (GMT + 7) on friday, will be resolve on Monday of coming week</em></p>
              </div>
              <div class="table-wrapper table-responsive bg-white table-vertical-midle">
                <table class="table table-hover">
                  <thead>
                    <tr>
                      <th scope="col">No.</th>
                      <th scope="col">ID</th>
                      <th class="text-center" scope="col">Amount ($)</th>
                      <th class="text-center" scope="col">Status</th>
                      <th class="text-center" scope="col">Details</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach ($withdraws as $no => $withdraw) : ?>
                      <tr>
                        <td class="text-center"><?=$no+1;?></td>
                        <th scope="row">
                          <a href="#">#<?=$withdraw->id;?></a>
                          <span class="date-time"><?=$withdraw->created_at;?></span>
                        </th>
                        <td><?=number_format($withdraw->amount, 1);?>$</td>
                        <td class="text-center"><?=$withdraw->getStatusLabel();?></td>
                        <td class="text-center"><span href="#" class="text-green"><?=$withdraw->note;?></span></td>
                      </tr>
                    <?php endforeach;?>
                    
                    <tr>
                      <th scope="row" class="text-left">GRAND TOTAL</th>
                      <td class="text-center"></td>
                      <td class="text-center"><b class="text-red">$<?=number_format($withdrawTotalAmount, 1);?></b></td>
                      <td class="text-center" colspan="4"></td>
                    </tr>
                  </tbody>
                </table>
              </div>
            <!-- END Transaction History Table -->
            </div>
          </div>
        </div>
      </div>
      <!-- End Modal Choose beneficiary account-->

      <!-- MODAL ADD BENEFICIARY -->
      <div class="modal fade" id="addBeneficiary" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="">Add new beneficiary account</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body p-5">
              <h3 class="text-center">Beneficiary account info</h3>
              <?php $form = ActiveForm::begin(['action' => Url::to(['affiliate/add-account']), 'options' => ['id' => 'add-account-form']]);?>
                <?= $form->field($addAccountForm, 'payment_method')->textInput()->label('Payment method') ?>
                <?= $form->field($addAccountForm, 'account_number')->textInput()->label('Account ID/No') ?>
                <?= $form->field($addAccountForm, 'account_name')->textInput()->label('Name of Holder') ?>
                <?= $form->field($addAccountForm, 'region')->textInput()->label('Region') ?>
                <button type="submit" class="btn mt-3 btn-warning text-white btn-block">SAVE</button>
              <?php ActiveForm::end();?>
            </div>
          </div>
        </div>
      </div>
      <!-- END MODAL ADD BENEFICIARY -->
    </div>
    <div class="col-md-9">
      <?=\frontend\widgets\AffiliateChartWidget::widget();?>
    </div>
    <div class="col-md-12">
      <hr class="my-5" />
    </div>
    <div class="col-md-12">
      <!-- Transaction History Table -->
      <div class="d-flex bd-highlight justify-content-between align-items-center orders-history-wrapper mb-3">
        <p class="lead mb-0">Transaction history</p>
        <?php $form = ActiveForm::begin(['method' => 'get']);?>
        <div class="d-flex ml-auto">
          <?= $form->field($searchCommission, 'start_date', [
            'options' => ['class' => 'flex-fill d-flex align-items-center mr-3'],
            'labelOptions' => ['class' => 'd-block w-100 mr-2 mb-0'],
            'inputOptions' => ['class' => 'form-control', 'type' => 'date', 'name' => 'start_date', 'data-date-format'=>"DD MMMM YYYY"]
          ])->textInput()->label('Start date') ?>
          <?= $form->field($searchCommission, 'end_date', [
            'options' => ['class' => 'flex-fill d-flex align-items-center mr-3'],
            'labelOptions' => ['class' => 'd-block w-100 mr-2 mb-0'],
            'inputOptions' => ['class' => 'form-control', 'type' => 'date', 'name' => 'end_date']
          ])->textInput()->label('End date') ?>
          <?= $form->field($searchCommission, 'status', [
            'options' => ['class' => 'flex-fill d-flex align-items-center mr-3'],
            'labelOptions' => ['class' => 'd-block w-100 mr-2 mb-0'],
            'inputOptions' => ['class' => 'form-control', 'name' => 'status']
          ])->dropdownList($searchCommission->fetchStatusList(), ['prompt' => 'Select status'])->label('Status') ?>
          <div class="flex-fill">
            <button class="btn btn-primary" type="submit">Filter</button>
          </div>
        </div>
        <?php ActiveForm::end(); ?>
      </div>

      <div class="table-wrapper table-responsive bg-white">
        <table class="table table-hover">
          <thead>
            <tr>
              <th scope="col">ID</th>
              <th class="text-center" scope="col">Amount ($)</th>
              <th class="text-center" scope="col">Commission ($)</th>
              <th class="text-center" scope="col">Status</th>
              <th class="text-center" scope="col">Details</th>
            </tr>
          </thead>
          <tbody>
            <?php if (!$commissions) : ?>
            <tr>
              <td class="text-center" colspan="5">No data</td>
            </tr>
            <?php endif; ?>
            <?php foreach ($commissions as $commission) : ?>
            <?php $order = $commission->order;?>
            <tr>
              <th scope="row">
                <a href="javascript:;">#<?=$order->id;?></a>
                <span class="date-time"><?=$order->created_at;?></span>
              </th>
              <td class="text-center"><?=number_format($order->sub_total_price, 1);?>$</td>
              <td class="text-center"><?=number_format($commission->commission, 1);?>$</td>
              <td class="text-center"><?=$commission->getStatusLabel();?></td>
              <td class="text-center"><span href="javascript:;" class="text-green"><?=$commission->description;?></span></td>
            </tr>
            <?php endforeach;?>
          </tbody>
        </table>
      </div>
      <nav aria-label="Page navigation" class="mt-2 mb-5">
        <?=LinkPager::widget([
          'pagination' => $pages,
          'options' => ['class' => 'pagination justify-content-end']
        ]);?>
      </nav>
    </div>
    <!-- END Transaction History Table -->
  </div>
</div>
<?php
$script = <<< JS
// Add account form
var addAccountForm = new AjaxFormSubmit({element: 'form#add-account-form'});
addAccountForm.success = function (data, form) {
  setTimeout(() => {  
      location.reload();
  }, 2000);
  toastr.success(data.message); 
}
addAccountForm.error = function(errors) {
  toastr.error(errors);
  return false;
}

// Withdraw form
var withdrawForm = new AjaxFormSubmit({element: 'form#withdraw-form'});
withdrawForm.success = function (data, form) {
  setTimeout(() => {  
      location.reload();
  }, 2000);
  toastr.success(data.message); 
}
withdrawForm.error = function(errors) {
  toastr.error(errors);
  return false;
}

// Delete account
$(".delete-account-link").ajax_action({
  method: 'POST',
  confirm: true,
  confirm_text: 'Do you want to delete this account?',
  callback: function(element, data) {
    $(element).closest('label').remove();
    toastr.success(data.message); 
  },
  error: function(element, errors) {
    toastr.error(errors);
    return false;
  }
});
JS;
$this->registerJs($script);
?>