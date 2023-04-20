<?php
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use common\components\helpers\FormatConverter;
use website\models\Paygate;
$this->registerJsFile('https://cdn.jsdelivr.net/npm/vue@2/dist/vue.js', ['depends' => ['\yii\web\JqueryAsset']]);
$this->registerJsFile('https://unpkg.com/axios/dist/axios.min.js', ['depends' => ['\yii\web\JqueryAsset']]);
$this->registerMetaTag(['property' => 'og:image', 'content' => $model->getImageUrl('150x150')], 'og:image');
$this->registerMetaTag(['property' => 'og:title', 'content' => $model->getMetaTitle()], 'og:title');
$this->registerMetaTag(['property' => 'og:description', 'content' => $model->getMetaDescription()], 'og:description');

$paygateObjects = Paygate::find()->where([
  'status' => Paygate::STATUS_ACTIVE
])->all();
$paygateArray = array_map(function($obj) {
  return [
    'id' => $obj->id, 
    'identifier' => $obj->identifier,
    'currency' => $obj->currency,
    'transfer_fee' => $obj->transfer_fee,
    'transfer_fee_type' => $obj->transfer_fee_type,
    'image' => $obj->getImageUrl(),
  ];
}, $paygateObjects);
$paygateJson = json_encode($paygateArray);

$user = Yii::$app->user->getIdentity();
$balance = $user ? $user->getWalletAmount() : 0;
$orderUrl = Url::to(['order/index']);
$isGuest = Yii::$app->user->isGuest ? 1 : 0;
$templateFile = Yii::$app->settings->get('ImportSettingForm', 'import_reseller_template');

$currencyJson = json_encode($currencies);
?>

<div id="cart_items">
  <input id="fileInput" type="file" accept=".xlsx" style="display:none" @change="uploadFile"/>
  <div class="container my-5 single">
    <div class="d-flex justify-content-between align-items-centert bg-white" id="game-header">
      <div class="w-50 flex-fill">
        <div class="single-img-slider">
          <img class="single-img" id="image" src="<?=$model->getImageUrl();?>" />
        </div>
      </div>
      <div class="w-50 flex-fill">
        <div class="content p-4">
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="/">Home</a></li>
              <?php if ($category) : ?>
              <li class="breadcrumb-item"><a href="javascript:;"><?=$category->name;?></a></li>
              <?php endif;?>
              <li class="breadcrumb-item active" aria-current="page"><?=$model->title;?></li>
            </ol>
          </nav>
          <h1 class="text-red mb-0" id="title"><?=$model->title;?></h1>
          <p class="lead" id="package-name"></p>
          <div class="btn-group-toggle multi-choose row" data-toggle="buttons">
            <div class="col-lg-4 col-md-6 col-xs-12 mb-3" v-for="methodData of methodList" :key="methodData.id">
              <label class="btn btn-secondary w-100" :class="{ active: method == methodData.id }" data-toggle="tooltip" data-placement="top" :title="methodData.title" @click="changeMethod(methodData.id)">
                <input type="radio" name="method" autocomplete="off" :checked="method == methodData.id"> {{ methodData.title }}
              </label>
            </div>
          </div>
          <div class="price py-3">
            <span class="price-value text-red mr-2" v-if="canSale">${{ price }}</span>
            <span class="price-value text-red mr-2" v-if="!canSale">Contact</span>
            <span class="badge badge-danger" v-if="canSale && game">{{ game.save }}</span>
            <?php if (!Yii::$app->user->isGuest) : ?>
            <span class="btn-group-toggle bell" data-toggle="buttons" id="subscribe" data-subscribe="<?=Url::to(['user/subscribe']);?>" data-unsubscribe="<?=Url::to(['user/unsubscribe']);?>">
              <label class="btn <?=$isSubscribe ? 'active' : '';?>" data-toggle="tooltip" data-placement="top" title="Notify me when price is changed or new promotions">
                <input type="checkbox">
              </label>
            </span>
            <?php endif;?>
          </div>
          <div class="d-flex align-content-end">
            <div class="w-100 flex-fill">
              <span class="gems-value">{{ unit }}</span>
            </div>
            <div class="w-100 flex-fill">
              <div class="d-flex bd-highlight">
                <div class="w-100 flex-fill" style="cursor: help;" data-toggle="tooltip" data-placement="left" title="Estimate Time Arrival">ETA:</div>
                <div class="w-100 flex-fill"><b>2 - 4h</b></div>
              </div>
              <div class="d-flex bd-highlight">
                <div class="w-100 flex-fill">Status:</div>
                <div class="w-100 flex-fill"><b><?=$model->isSoldout() ? 'Out Stock' : 'In Stock';?></b></div>
              </div>
            </div>
          </div>
          <div class="row multi-select p-2" v-if="versionOptions.length && packageOptions.length">
            <div class="col-md-6" v-if="versionOptions.length">
              <div class="form-group">
                <label for="exampleFormControlSelect1">Version</label>
                <select class="form-control" v-model="version">
                  <option v-for="option in versionOptions" :key="option.value" :value="option.value">
                    {{ option.text }}
                  </option>
                </select>
              </div>
            </div>
            <div class="col-md-6" v-if="packageOptions.length">
              <div class="form-group">
                <label for="exampleFormControlSelect2">Pack</label>
                <select class="form-control" v-model="package">
                  <option v-for="option in packageOptions" :key="option.value" :value="option.value">
                    {{ option.text }}
                  </option>
                </select>
              </div>
            </div>
          </div>
          <hr />
          <div class="multi-rating d-flex justify-content-between align-items-center">
            <star-item v-for="item in starList" :title="item.title" :select-star="item.selectStar" v-bind:key="item.title"/>
          </div>
          <hr />
          <div style="display:flex; justify-content: space-between;" v-show="canSale && isUserLogin">
            <p class="lead mb-2"></p>
            <div>
              <a href="<?=$templateFile;?>" class="btn btn-primary" role="button" aria-pressed="true">
                <img class="icon-btn" src="/images/icon/more.svg"/> Download
              </a>
              <button type="button" class="btn btn-green" onclick="document.getElementById('fileInput').click()">
                <img class="icon-btn" src="/images/icon/more.svg"/> Upload
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>

    
  </div><!-- END MAIN SINGLE -->

  <div class="container my-5 single-order" v-show="canSale && isUserLogin">
    <cart-item v-for="item in items" :key="item.id" :quantity="item.quantity" :information="item.raw" :id="item.id" :add-item="addItem" :delete-item="deleteItem" :update-quantity="updateQuantity" :update-raw="updateRaw"/>
  </div>

  <div class="container my-5 single-order" v-show="canSale && isUserLogin">
    <div class="row">
      <div class="col-md-5 info">
        <p class="lead mb-2">Payment method</p>
        <hr/>
        <div class="btn-group-toggle multi-choose multi-choose-payment d-flex flex-wrap" data-toggle="buttons">
          <paygate-item
            v-for="paygate in availablePaygates"
            :key="paygate.id"
            :id="paygate.id"
            :identifier="paygate.identifier"
            :image="paygate.image"
            :on-select="choosePaygate"
            :total-price="totalPrice"
            :balance="balance"
          />
        </div>
      </div>
      <div class="col-md-7">
        <!-- CART SUMMARY -->
        <div class="card card-summary">
          <h5 class="card-header text-uppercase">Card summary</h5>
          <div class="card-body">
            <p class="card-text text-red font-weight-bold">{{ game && game.title }}</p>
            <p class="text-green card-text font-weight-bold">{{ unit }} x {{ quantity }}</p>
            <h5 class="card-title">Price Details</h5>
            <hr />
            <div class="d-flex">
              <div class="flex-fill w-100">Total Order</div>
              <div class="flex-fill w-100 text-right">{{ totalOrder }}</div>
            </div>
            <div class="d-flex">
              <div class="flex-fill w-100">Total Pack</div>
              <div class="flex-fill w-100 text-right">{{ quantity }}</div>
            </div>
            <div class="d-flex">
              <div class="flex-fill w-100">Price</div>
              <div class="flex-fill w-100 text-right">${{ price }}</div>
            </div>
            <div class="d-flex">
              <div class="flex-fill w-100">Transfer fee</div>
              <div class="flex-fill w-100 text-right">${{ transferFee }}</div>
            </div>
            <hr />
            <div class="d-flex mb-3">
              <div class="flex-fill text-red font-weight-bold w-100">Total</div>
              <div class="flex-fill text-red font-weight-bold w-100 text-right">${{ totalPrice }}</div>
            </div>
            <div class="d-flex mb-3" v-if="currency !== 'USD'">
              <div class="flex-fill text-red font-weight-bold w-100 text-right">({{ otherPrice }}) {{ currency }}</div>
            </div>

            <div class="mb-3">
              <div class="custom-control custom-checkbox" >
                <input type="checkbox" class="custom-control-input" :checked="policy1">
                <label class="custom-control-label" @click="togglePolicy('policy1')">I’ve read & agreed with <a class="text-red" href="javascript:;" data-toggle="modal" data-target="#disclaimer_policies">Disclaimer policies</a> of service</label>
              </div>
              <div class="custom-control custom-checkbox">
                <input type="checkbox" class="custom-control-input" :checked="policy2">
                <label class="custom-control-label" @click="togglePolicy('policy2')">By making this purchase, I’m confirming that I totally under-stand <a class="text-red" href="javascript:;" data-toggle="modal" data-target="#noRefundModal">no refund policy</a></label>
              </div>
            </div>
            <a href="javascript:;" class="btn btn-block btn-payment text-uppercase" @click="checkOut()">Check out</a>
          </div>
        </div>
        <!-- END SUMMARY -->
      </div>
    </div>
  </div>
</div>

<?php 
$noRefundContent = Yii::$app->settings->get('TermsConditionForm', 'no_refund');
$disclaimerPolicies = Yii::$app->settings->get('TermsConditionForm', 'disclaimer_policies');
?>
<div class="modal fade" id="noRefundModal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">No Refund Policy</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <?=$noRefundContent;?>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="disclaimer_policies" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Disclaimer policies</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <?=$disclaimerPolicies;?>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>


<?php
$methodArray = [];
foreach($methods as $method) {
  $methodTempRow = [];
  $methodTempRow['id'] = $method->id;
  $methodTempRow['price'] = $method->price;
  $methodTempRow['speed'] = $method->speed;
  $methodTempRow['safe'] = $method->safe;
  $methodTempRow['title'] = $method->title;
  $methodArray[$method->id] = $methodTempRow;
}
$settingMethodMapping = json_encode($methodArray);
$calculateUrl = Url::to(['cart/calculates', 'id' => $model->id], true);
$checkoutsUrl = Url::to(['cart/checkouts', 'id' => $model->id], true);
$uploadUrl = Url::to(['shop/upload', 'id' => $model->id], true);
$walletUrl = Url::to(['wallet/index'], true);
$script = <<< JS

// React view on attributes
var currentMethod = '$model->method';
var currentVersion = '$model->version';
var currentPackage = "$model->package";
var currentTitle = "$model->title";
var mapping = $mapping;
var has_group = $has_group;
var settingMethodMapping = $settingMethodMapping;
var settingVersionMapping = $settingVersionMapping;
var settingPackageMapping = $settingPackageMapping;
var currencyList = $currencyJson;
var orderUrl = '$orderUrl';
var calculateUrl = '$calculateUrl';
var checkoutsUrl = '$checkoutsUrl';
var uploadUrl = '$uploadUrl';
var walletUrl = '$walletUrl';
console.log('mapping', mapping);
console.log('settingMethodMapping', settingMethodMapping);
console.log('settingVersionMapping', settingVersionMapping);
console.log('settingPackageMapping', settingPackageMapping);
console.log('currencyList', currencyList);

// subscribe
$('#subscribe').on('click', function() {
  var _url = $(this).find('.active').length ? $(this).data('unsubscribe') : $(this).data('subscribe');
  $.ajax({
    url: _url,
    type: 'POST',
    dataType : 'json',
    data: {game_id: $model->id},
    success: function (result, textStatus, jqXHR) {
      if (result.status == false) {
          toastr.error('Something went wrong');
          return false;
      }
    },
  });
});

JS;
$this->registerJs($script);
?>

<?php
$csrfTokenName = Yii::$app->request->csrfParam;
$csrfToken = Yii::$app->request->csrfToken;
$script = <<< JS
Vue.component("cartItem", {
  props: ["id", "quantity", "information", "addItem", "deleteItem", "updateQuantity", "updateRaw"],
  data() {
    return {
      value: this.quantity,
      raw: this.information
    }
  },
  methods: {
    minusQuantity() {
      this.changeQuantity(this.value - 1);
    },
    plusQuantity() {
      this.changeQuantity(this.value + 1);
    },
    validateQuantity(num) {
      if (num <= 0) {
        return false;
      }
      if (isNaN(num)) {
        return false;
      }
      return true;
    },
    changeQuantity(value) {
      value = parseFloat(value);
      if (this.validateQuantity(value)) {
        this.updateQuantity(this.id, value);
        this.value = value;
      } else {
        this.value = this.quantity;
      }
    }
  },
  template: `<div class="d-flex align-items-centert bg-white">
    <div class="col-md-12">
      <hr/>
      <div class="mb-3 d-flex justify-content-between align-items-center">
        <div class="flex-fill mr-0 mb-0 w-100">
          <h3>Account Info</h3>
          <p style="color: #CCC;font-style: italic;font-size: 0.9rem;">Kindly provide correct information to avoid long waiting time. Thank you</p>
          <div class="add-quantity d-flex justify-content-between align-items-center">
            <span class="flex-fill minus" @click="minusQuantity()"><img class="icon-sm" src="/images/icon/minus.svg"></span>
            <input type="text" class="quantity-value flex-fill text-center" v-model="value" @blur="event => changeQuantity(event.target.value)">
            <span class="flex-fill plus" @click="plusQuantity()"><img class="icon-sm" src="/images/icon/plus.svg"></span>
          </div>
        </div>
        <div class="flex-fill w-100 field-cartitem-raw">
          <textarea placeholder="Login method:\nUsername:\nPassword:\nCharacter name:\nRecovery codes:" class="form-control raw" v-model="raw" @blur="updateRaw(id, raw)" rows="5">{{ id }}</textarea>
        </div>
      </div>
      <div class="text-right">
        <a href="javascript:;" class="trash" @click="event => deleteItem(id)"><img class="icon-sm" src="/images/icon/trash-can.svg"></a>
        <button type="button" class="btn btn-red" @click="event => addItem(id)">
          <img class="icon-btn" src="/images/icon/more.svg"/> Add order
        </button>
      </div>
    </div> 
  </div>`,
});
Vue.component("paygateItem", {
  props: ['id', 'image', 'onSelect', 'identifier', "totalPrice", "balance"],
  methods: {
    selectPaygate(id) {
      if (!this.disabled) {
        this.onSelect(id);
      } else {
        this.onSelect(null);
      }
    }
  },
  computed: {
    disabled() {
      if (this.identifier === 'kinggems') {
        return this.totalPrice > this.balance;
      }
      return false;
    }
  },
  template: `<label class="btn flex-fill btn-secondary" @click="selectPaygate(id)" :class="{disabled: disabled}" >
      <input type="radio" name="paygates" autocomplete="off" :disabled="disabled">
      <img v-if="identifier !== 'kinggems'" class="icon" :src="image"/>
      <template v-else>
      <div>Balance</div>
      <div class="lead text-red font-weight-bold">{{image}}</div>
      </template>
    </label>`
});
Vue.component("starItem", {
  props: ['title', 'selectStar'],
  data() {
    return {
      list: [
        { title: 'Poor' },
        { title: 'Fair' },
        { title: 'Good' },
        { title: 'Excellent' },
        { title: 'WOW!!!' }
      ]
    }
  },
  template: `<div class="p-2 flex-fill bd-highlight">
              <!-- Rating Stars Box -->
              <div class='rating-stars text-center'>
                {{ title }}
                <ul id='star-safe'>
                  <li v-for="(item, index) in list" :class='{star: true, selected: index < selectStar}' :title='item.title' :data-value='index + 1' :key="index" >
                    <span class="icon-star"></span>
                  </li>
                </ul>
              </div>
            </div>`
});

const paygates = $paygateJson;
const balance = parseFloat('$balance');
const isUserLogin = !$isGuest;
var app = new Vue({
  el: '#cart_items',
  data: {
    price: 0,
    unit: 0,
    paygate: null,
    items: [],
    isUserLogin: isUserLogin,
    methodList: settingMethodMapping,
    versionList: settingVersionMapping,
    packageList: settingPackageMapping,
    method: currentMethod,
    version: currentVersion,
    package: currentPackage,
    game: {
      title: currentTitle,
      calculateUrl: calculateUrl,
      checkoutsUrl: checkoutsUrl,
    },
    canSale: false,
    policy1: false,
    policy2: false,
    balance: balance || 0,
    isSubmiting: false
  },
  watch: {
    method() {
      const version = this.versionOptions.length ? this.versionOptions[0].value : '';
      const package = this.packageOptions.length ? this.packageOptions[0].value : '';
      if (version != this.version || package != this.package) {
        this.version = version;
        this.package = package;
      } else {
        this.getGameInfor();
      }
    },
    version() {
      const package = this.packageOptions.length ? this.packageOptions[0].value : '';
      if (package != this.package) {
        this.package = package;
      } else {
        this.getGameInfor();
      }
    },
    package() {
      this.getGameInfor();
    }
  },
  computed: {
    availablePaygates() {
      const kinggems = {
        identifier: 'kinggems',
        currency: 'USD',
        id: 'kinggems',
        image: balance + ' Kcoin',
        transfer_fee: 0,
        transfer_fee_type: 'fix',
      };
      return [kinggems, ...paygates];
    },
    quantity() {
      return this.items.reduce((p,c) => p + parseFloat(c.quantity), 0);
    },
    totalOrder() {
      return this.items.length;
    },
    subPrice() {
      return this.price * this.quantity;
    },
    transferFee() {
      if (!this.paygate) return 0;
      const paygate = this.availablePaygates.find(({ id }) => id === this.paygate);
      const fee = paygate.transfer_fee;
      if (fee) {
          const type = paygate.transfer_fee_type;
          return type === 'fix' ? fee * this.totalOrder : fee * this.quantity;
      } else {
          return 0;
      }
    },
    totalPrice() {
      return this.subPrice + this.transferFee;
    },
    versionOptions() {
      if (!Object.keys(mapping).length) return [];
      const versionKeys = Object.keys(mapping[this.method] || {});
      return versionKeys.map(key => {
        return { value: key, text: this.versionList[key] };
      })
    },
    packageOptions() {
      if (!Object.keys(mapping).length) return [];
      const packageKeys = Object.keys(mapping[this.method][this.version] || {});
      return packageKeys.map(key => {
        return { value: key, text: this.packageList[key] };
      })
    },
    methodPrice() {
      try {
        return this.methodList[this.method].price;
      } catch (e) {
        return 0;
      }
    },
    methodSpeed() {
      try {
        return this.methodList[this.method].speed;
      } catch (e) {
        return 0;
      }
    },
    methodSafe() {
      try {
        return this.methodList[this.method].safe;
      } catch (e) {
        return 0;
      }
    },
    starList() {
      return [
        { title: 'Price', selectStar: this.methodPrice },
        { title: 'Speed', selectStar: this.methodSpeed },
        { title: 'Safe', selectStar: this.methodSafe },
      ]
    },
    otherPrice() {
      const currencyInfo = currencyList.find(({ code }) => code === this.currency);
      if (currencyInfo) {
        const { exchange_rate = 1 } = currencyInfo;
        const otherCurrency = this.totalPrice * exchange_rate;
        return otherCurrency.toFixed(2);
      }
      return this.totalPrice;
    },
    currency() {
      const paygateInfo = this.availablePaygates.find(({ id }) => id === this.paygate);
      return paygateInfo ? paygateInfo.currency : 'USD';
    }
  },
  methods: {
    addItem() {
      this.items = [...this.items, { id: this.uuidv4(), quantity: 1, raw: '' }];
    },
    deleteItem(id) {
      console.log('deleteItem', id);
      if (this.items.length <= 1) {
        alert('You need to keep at least one item');
        return;
      }

      console.log('deleteItem items before', this.items);
      const items = this.items.filter((item) => {
        return item.id !== id;
      });
      this.items = [...items];
      console.log('deleteItem items after', this.items);
    },
    updateQuantity(id, quantity) {
      this.items = this.items.map(item => {
        if (item.id === id) {
          item.quantity = quantity;
        }
        return item;
      });
    },
    updateRaw(id, raw) {
      this.items = this.items.map(item => {
        if (item.id === id) {
          item.raw = raw;
        }
        return item;
      });
    },
    choosePaygate(id) {
      this.paygate = id;
    },
    uuidv4() {
      return ([1e7]+-1e3+-4e3+-8e3+-1e11).replace(/[018]/g, c =>
        (c ^ crypto.getRandomValues(new Uint8Array(1))[0] & 15 >> c / 4).toString(16)
      );
    },
    getGameInfor() {
      this.game = mapping[this.method][this.version][this.package];
      console.log('Game Info', this.method, this.version, this.package, this.game);
      if (!this.game) return;
      
      axios.post(this.game.calculateUrl, {
        'currency': this.currency,
        'quantity': this.quantity,
        '$csrfTokenName': '$csrfToken' 
      }, {
        headers: {
          'Content-Type': 'application/json'
        }
      }).then(( { data: result }) => {
        const { status = false, data } = result || {};
        console.log('data', data);
        this.canSale = parseInt(result.data.amount);
        this.unit = data.unit;
        this.price = data.amount;
        history.pushState({}, this.game.title, this.game.viewUrl);
      });
    },
    togglePolicy(policy) {
      this[policy] = !this[policy];
    },
    validate() {
      let flag = true;
      let message = '';
      if (!this.policy1 || !this.policy2) {
        message = 'Please agree with our policy';
        flag = false;
      } else if (!this.paygate) {
        message = 'Please choose paygate';
        flag = false;
      } else if (!this.quantity) {
        message = 'Not valid to make purchase';
        flag = false;
      } else if (!this.game) {
        message = 'Game information is not valid';
        flag = false;
      } else if (!this.totalOrder) {
        message = 'Please add your order';
        flag = false;
      } else if (this.paygate === 'kinggems' && this.balance < this.totalPrice) {
        message = 'Not enough amount in your wallet.';
        flag = false;
      } else if (this.items.some((item) => {
        return !item.quantity || !item.raw.trim()
      })) {
        message = 'One of item is not valid';
        flag = false;
      }
      if (!flag) {
        Swal.fire({
          icon: 'error',
          title: 'Oops...',
          text: message,
          allowOutsideClick: false
        });
        return false;
      }
      if (this.items.length > 1 && this.paygate !== 'kinggems') {
        Swal.fire({
          icon: 'error',
          title: 'Oops...',
          html: 'To use this feature you must deposit Kcoin first. <a href="' + walletUrl + '">Click here!</a>',
          confirmButtonText: 'OK',
          allowOutsideClick: false
        });
        return false;
      }
      return true;
    },
    checkOut() {
      if (this.isSubmiting || !this.validate() ) {
        return false;
      }        
      this.isSubmiting = true;
      Swal.showLoading();
      const paygate = this.availablePaygates.find(( { id }) => id === this.paygate);

      axios.post(this.game.checkoutsUrl, {
        'paygate': paygate.identifier,
        'items': this.items,
        '$csrfTokenName': '$csrfToken' 
      }, {
        headers: {
          'Content-Type': 'application/json'
        }
      }).then((result) => {
        const { data } = result;
        const { status, success, errors } = data;
        if (status === true) {
          Swal.close();
          Swal.fire({
            title: 'Success',
            confirmButtonText: 'Go to orders',
            allowOutsideClick: false
          }).then((result) => {
            if (result.isConfirmed) {
              window.location.href = success;
            }
          })
        } else {
          Swal.close();
          Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: errors,
            confirmButtonText: 'OK',
            allowOutsideClick: false
          });
          this.isSubmiting = false;
        }
      });
    },
    uploadFile(event) {
      console.log(event);
      const file = event.target.files[0];
      var formData = new FormData();
      formData.append("excel", file);
      formData.append('$csrfTokenName', '$csrfToken');
      axios.post(uploadUrl, formData, {
        headers: {
          'Content-Type': 'multipart/form-data'
        }
      }).then(( { data: result }) => {
        console.log('upload', result);
        const items = result.map(row => {
          return { id: this.uuidv4(), quantity: parseFloat(row[0]), raw: row[1] }
        });
        this.items = items;
        console.log('uploadFile', this.items);
      })
    },
    changeMethod(method) {
      this.method = method;
    }
  },
  created() {
    // init game
    if (!this.game) {
      this.game = mapping[this.method][this.version][this.package];
    }
    if (!this.items.length) {
      this.addItem();
    }
    this.getGameInfor();    
  }
});
JS;
$this->registerJs($script);
?>