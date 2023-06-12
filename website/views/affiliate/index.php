<?php
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use website\widgets\LinkPager;
$user = Yii::$app->user->getIdentity();
$affiliateLink = Url::to(['site/signup', 'affiliate' => $user->getAffiliateCode()], true);
$this->registerCssFile('https://unpkg.com/tabulator-tables@5.5.0/dist/css/tabulator.min.css', ['depends' => [\website\assets\AppAsset::className()]]);
$this->registerJsFile('https://cdn.jsdelivr.net/npm/vue@2/dist/vue.js',  ['depends' => [\website\assets\AppAsset::className()]]);
$this->registerJsFile('https://unpkg.com/axios/dist/axios.min.js',  ['depends' => [\website\assets\AppAsset::className()]]);
$this->registerJsFile('https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.bundle.min.js', ['position' => \yii\web\View::POS_HEAD]);
$this->registerJsFile('https://unpkg.com/tabulator-tables@5.5.0/dist/js/tabulator.min.js',  ['depends' => [\website\assets\AppAsset::className()]]);
$balance = $user->affiliateBalance();
?>
<style>
  .table-wrapper .date-time{
    font-size: 10px;
    display: block;
    color: #606060;
  }
</style>
<div class="container profile profile-affiliate my-5">
  <div class="row">
    <div class="col-md-3">
      <div class="card card-info text-center">
        <img class="card-img-top" src="/images//icon/mask.svg" alt="Card image">
        <div class="card-body">
          <h4 class="card-title"><?=$user->name;?></h4>
          <p class="card-text">@<?=$user->username;?></p>
          <p class="font-weight-bold text-red">Balance: <?=number_format($balance);?> KCOIN</p>
          <a href="#" class="btn btn-green" data-toggle="modal" data-target="#choosePayment">
            WITHDRAW
          </a>
          <a href="#" class="btn" id="affliateLink" data-link="<?=$affiliateLink;?>">
            GET LINK
          </a>
        </div>
      </div>
      <!-- Modal Choose beneficiary account-->
      <div class="modal fade" id="choosePayment" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Choose beneficiary account</h5>
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
              ])->widget(\website\widgets\AffiliateAccountRadioListInput::className(), [
                'items' => $withdrawForm->fetchAccounts(),
                'options' => ['tag' => false]
              ])->label(false);?>
              <?php if ($user->hasAffiliateCommissionRequest()) : ?>
              <div class="input-group mt-4" style="color: red; font-weight: bold">You have 1 waiting request</div>
              <?php else : ?>
              <div class="input-group mt-4" style="max-width:300px">
                <?= $form->field($withdrawForm, 'amount', [
                  'template' => '{input}',
                  'options' => ['tag' => false],
                  'inputOptions' => ['class' => 'form-control', 'placeholder' => 'Withdraw Amount', 'aria-label' => 'Withdraw Amount', 'aria-describedby' => 'button-addon2', 'type' => 'number', 'max' => (int)$balance]
                ])->textInput()->label(false) ?>
                <div class="input-group-append">
                  <button class="btn btn-warning text-white" type="submit" id="button-addon2">Submit</button>
                </div>
              </div>
              <p class="help-block" style="color: grey; font-style: italic; font-size: small;"><?=sprintf("Max amount: %s", (int)$balance);?></p>
              <?php endif;?>
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
      <?=\website\widgets\AffiliateChartWidget::widget();?>
    </div>
    <div class="col-md-12">
      <hr class="my-5" />
    </div>
    <div class="col-md-12" id="affiliate-table-component">
      <!-- Transaction History Table -->
      <p class="lead mb-0">Transaction history</p>
      <div class="d-flex bd-highlight justify-content-between align-items-center orders-history-wrapper mb-3">
        <div class="d-flex ml-auto">
          <div class="flex-fill d-flex align-items-center mr-3">
            <label class="d-block w-100 mr-2 mb-0">Start date</label> 
            <input type="date" v-model="condition.start_date" data-date-format="DD MMMM YYYY" class="form-control"> 
          </div>
          <div class="flex-fill d-flex align-items-center mr-3">
            <label class="d-block w-100 mr-2 mb-0">End date</label> 
            <input type="date" v-model="condition.end_date" data-date-format="DD MMMM YYYY" class="form-control"> 
          </div>
        </div>
        <div class="d-flex ml-auto">
          <div class="flex-fill d-flex align-items-center mr-3">
            <label class="d-block w-100 mr-2 mb-0">By Buyer</label> 
            <input type="text" v-model="condition.customer_name" class="form-control"> 
          </div>
          <div class="flex-fill d-flex align-items-center mr-3">
            <label class="d-block w-100 mr-2 mb-0">By Game</label> 
            <input type="text" v-model="condition.game_title" class="form-control"> 
          </div>
          <div class="flex-fill d-flex align-items-center mr-3">
            <label class="d-block w-100 mr-2 mb-0">By Order No.</label> 
            <input type="text" v-model="condition.order_id" class="form-control"> 
          </div>
          
        </div>
        
      </div>
      <div class="d-flex bd-highlight justify-content-between align-items-center orders-history-wrapper mb-3">
        <div class="flex-fill" style="margin-left: auto">
          <button class="btn btn-primary" @click="filterAffiliate">Filter</button>
        </div>
      </div>
      <div class="table-wrapper table-responsive bg-white" ref="table"></div>
    </div>
    <!-- END Transaction History Table -->
  </div>
</div>
<?php
$fetchCommissionUrl = Url::to(['affiliate/fetch'], true);
$csrfTokenName = Yii::$app->request->csrfParam;
$csrfToken = Yii::$app->request->csrfToken;
$script = <<< JS

var app = new Vue({
  el: '#affiliate-table-component',
  data: function () {
    return {
      condition: {
        order_id: '',
        start_date: '',
        end_date: '',
        customer_name: '',
        game_title: '',
      },
      tableData: [], //data for table to display
    }
  },
  watch: {
    condition() {
      console.log('condition', this.condition);
    }
  },
  methods: {
    filterAffiliate() {
      // load data
    axios.post('$fetchCommissionUrl', {
      '$csrfTokenName': '$csrfToken',
      condition: this.condition
    }, {
      headers: {
        'Content-Type': 'application/json'
      }
    }).then(({ data }) => {
      console.log('data', data.items);
      this.tableData = data.items.map((item, index) => {
        item['no'] = index + 1;
        return item;
      });
      new Tabulator(this.\$refs.table, {
        placeholder:"No Data Available", //display message to user on empty table
        data: this.tableData, //link data to table
        reactiveData:true, //enable data reactivity
        layout:"fitColumns",
        columns:[
          {title:"No.", field:"no", hozAlign:"center"},
          {title:"Order No.", field:"order_id",formatter:function(cell, formatterParams, onRendered){
              var cellElement = cell.getElement();
              var row = cell.getRow();
              var rowData = row.getData();
              cellElement.style.color = '#8cc63e';
              return '<a href="javascript:;">'+cell.getValue()+'</a><span class="date-time">'+rowData.created_at+'</span>';
          }},
          {title:"Buyer", field:"buyer"},
          {title:"Game", field:"game"},
          {title:"Amount (package)", field:"quantity", hozAlign:"center"},
          {title:"Commission", field:"commission", formatter:"money", hozAlign:"center", formatterParams:{
              decimal:".",
              thousand:",",
              symbol:"$",
              symbolAfter:"p",
              negativeSign:true,
              precision:false,
          }},
          {title:"Status", field:"status", hozAlign:"center"},
          {title:"Note", field:"description",formatter:function(cell, formatterParams, onRendered){
              var cellElement = cell.getElement();
              cellElement.style.color = '#8cc63e';
              return cell.getValue();
          }},
        ],
      });
    });

    }
  },
  created(){
    this.filterAffiliate();
  }
});


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

// Copy affiliate link
$("#affliateLink").on("click", function() {
  copyToClipboard($(this).data('link'));
  toastr.success('Your affiliate link was coppied to clipboard.'); 
});
JS;
$this->registerJs($script);
?>