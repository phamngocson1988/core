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
    'image' => $obj->getImageUrl()
  ];
}, $paygateObjects);
$paygateJson = json_encode($paygateArray);

$user = Yii::$app->user->getIdentity();
$balance = $user->getWalletAmount();

?>
<div id="cart_items">
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
          <?php if ($methods) : ?>
          <div class="btn-group-toggle multi-choose row" data-toggle="buttons">
            <?php foreach ($methods as $method) : ?>
            <?php 
            $settingMethodPrice = $method->price;
            $settingMethodSpeed = $method->speed;
            $settingMethodSafe = $method->safe;
            $settingMethodTitle = $method->title;
            ?>
            <div class="col-lg-4 col-md-6 col-xs-12 mb-3">
              <label class="btn btn-secondary w-100 <?=($method->id == $model->method) ? 'active' : '';?>" data-toggle="tooltip" data-placement="top" title="<?=$settingMethodTitle;?>">
                <input type="radio" name="method" id="<?=$method->id;?>" autocomplete="off" <?=($method->id == $model->method) ? 'checked' : '';?> data-price="<?=$settingMethodPrice;?>" data-speed="<?=$settingMethodSpeed;?>" data-safe="<?=$settingMethodSafe;?>"><?=$settingMethodTitle;?>
              </label>
            </div>
            <?php endforeach;?>
          </div>
          <?php endif;?>
          <div class="price py-3">
            <span class="price-value text-red mr-2" id="price">$<?=number_format($model->getPrice(), 1);?></span>
            <span class="badge badge-danger" id="save"><?=sprintf("save %s", number_format($model->getSavedPrice()));?>%</span>
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
              <span class="gems-value" id="game-unit"><?=sprintf("%s %s", number_format($model->getUnit()), strtoupper($model->getUnitName()));?></span>
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
          <?php $form = ActiveForm::begin(['action' => Url::to(['cart/add', 'id' => $model->id]), 'options' => ['id' => 'add-cart-form', 'data-calculatecart-url' => Url::to(['cart/calculate', 'id' => $model->id])]]);?>
          <div class="row multi-select p-2">
            <div class="col-md-4">
              <div class="form-group">
                <label for="exampleFormControlSelect1">Version</label>
                <select class="form-control" id="version"></select>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label for="exampleFormControlSelect2">Pack</label>
                <select class="form-control" id="package"></select>
              </div>
            </div>
            <div class="col-md-4">
              <?= $form->field($model, 'currency', [
                'inputOptions' => ['v-model' => 'currency']
              ])->dropdownList($model->fetchCurrency())->label('Currency') ?>
            </div>
          </div>
          <div class="multi-button d-flex justify-content-between align-items-center">
            <div class="w-100 flex-fill p-2">
              <?= $form->field($model, 'quantity', [
                'options' => ['class' => 'd-flex justify-content-between align-items-center'],
                'labelOptions' => ['class' => 'w-100 flex-fill', 'tag' => 'div'],
                'template' => '{label}<div class="w-100 flex-fill single-order"><div class="add-quantity d-flex justify-content-between align-items-center">
                    <span class="flex-fill minus">
                      <img class="icon-sm" src="/images/icon/minus.svg"/>
                    </span>{input}<span class="flex-fill plus">
                    <img class="icon-sm" src="/images/icon/plus.svg"/>
                  </span>
                </div></div>',
                'inputOptions' => ['class' => 'quantity-value', 'id' => 'quantity']
              ])->textInput()->label('Quantity') ?>
            </div>
            <?php if (!$model->isSoldout()) :?>
            <div class="w-100 flex-fill p-2">
              <?php if (Yii::$app->user->isGuest) : ?>
              <a href="#modalLogin" class="btn btn-buy" id='btn-buy' data-toggle="modal">Buy now</a>
              <?php else :?>
              <button type="submit" class="btn btn-buy" id='btn-buy'>Buy now</button>
              <?php endif;?>
            </div>
            <?php if ($is_reseller) : ?>
            <div class="w-100 flex-fill p-2">
              <?php if (Yii::$app->user->isGuest) : ?>
              <a href="#modalLogin" class="btn btn-quickbuy" data-toggle="modal"><img class="icon-sm" src="/images/icon/timer.svg" />Buy now</a>
              <?php else :?>
              <a href="<?=Url::to(['game/quick', 'id' => $model->id, 'slug' => $model->slug]);?>" id='btn-quickbuy' class="btn btn-quickbuy"><img class="icon-sm" src="/images/icon/timer.svg" /> Quick
                Buy</a>
              <?php endif;?>
            </div>
            <?php endif;?>
            <?php endif;?>
          </div>
          <?php ActiveForm::end(); ?>
          <hr />
          <div class="multi-rating d-flex justify-content-between align-items-center">
            <div class="p-2 flex-fill bd-highlight">
              <!-- Rating Stars Box -->
              <div class='rating-stars text-center'>
                Price
                <ul id='star-price'>
                  <li class='star' title='Poor'>
                    <span class="icon-star"></span>
                  </li>
                  <li class='star' title='Fair'>
                    <span class="icon-star"></span>
                  </li>
                  <li class='star' title='Good'>
                    <span class="icon-star"></span>
                  </li>
                  <li class='star' title='Excellent'>
                    <span class="icon-star"></span>
                  </li>
                  <li class='star' title='WOW!!!'>
                    <span class="icon-star"></span>
                  </li>
                </ul>
              </div>
            </div>
            <div class="p-2 flex-fill bd-highlight">
              <!-- Rating Stars Box -->
              <div class='rating-stars text-center'>
                Speed
                <ul id='star-speed'>
                  <li class='star' title='Poor' data-value='1'>
                    <span class="icon-star"></span>
                  </li>
                  <li class='star' title='Fair' data-value='2'>
                    <span class="icon-star"></span>
                  </li>
                  <li class='star' title='Good' data-value='3'>
                    <span class="icon-star"></span>
                  </li>
                  <li class='star' title='Excellent' data-value='4'>
                    <span class="icon-star"></span>
                  </li>
                  <li class='star' title='WOW!!!' data-value='5'>
                    <span class="icon-star"></span>
                  </li>
                </ul>
              </div>
            </div>
            <div class="p-2 flex-fill bd-highlight">
              <!-- Rating Stars Box -->
              <div class='rating-stars text-center'>
                Safe
                <ul id='star-safe'>
                  <li class='star' title='Poor' data-value='1'>
                    <span class="icon-star"></span>
                  </li>
                  <li class='star' title='Fair' data-value='2'>
                    <span class="icon-star"></span>
                  </li>
                  <li class='star' title='Good' data-value='3'>
                    <span class="icon-star"></span>
                  </li>
                  <li class='star' title='Excellent' data-value='4'>
                    <span class="icon-star"></span>
                  </li>
                  <li class='star' title='WOW!!!' data-value='5'>
                    <span class="icon-star"></span>
                  </li>
                </ul>
              </div>
            </div>
          </div>
          <hr />
          <div style="display:flex; justify-content: space-between;">
            <p class="lead mb-2"></p>
            <div>
              <a href="#" class="btn btn-primary" role="button" aria-pressed="true">
                <img class="icon-btn" src="/images/icon/more.svg"/> Download
              </a>
              <button type="button" class="btn btn-green" id="upload-excel-button">
                <img class="icon-btn" src="/images/icon/more.svg"/> Upload
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>

    
  </div><!-- END MAIN SINGLE -->

  <div class="container my-5 single-order">
    <template v-for="item in items">
      <cart-item :quantity="item.quantity" :id="item.id" :add-item="addItem" :delete-item="deleteItem" :update-quantity="updateQuantity"/>
    </template>
  </div>
  <div class="container my-5 single-order">
    <div class="row">
      <div class="col-md-5 info">
        <p class="lead mb-2">Payment method</p>
        <hr/>
        <div class="btn-group-toggle multi-choose multi-choose-payment d-flex flex-wrap" data-toggle="buttons">
          <template v-for="paygate in availablePaygates">
            <paygate-item
            :id="paygate.id"
            :image="paygate.image"
            :on-select="choosePaygate"
            />
          </template>
        </div>
      </div>
      <div class="col-md-7">
        <!-- CART SUMMARY -->
        <div class="card card-summary">
          <h5 class="card-header text-uppercase">Card summary</h5>
          <div class="card-body">
            <p class="card-text text-red font-weight-bold">Game: State of Survival - Discard (Pack Offer)</p>
            <p class="text-green card-text font-weight-bold">600 GEMS</p>
            <p class="card-text">Version Global</p>
            <h5 class="card-title">Price Details</h5>
            <hr />
            <div class="d-flex">
              <div class="flex-fill w-100">Price</div>
              <div class="flex-fill w-100 text-right">${{ subPrice }}</div>
            </div>
            <div class="d-flex">
              <div class="flex-fill w-100">Transfer fee</div>
              <div class="flex-fill w-100 text-right">${{ transferFee }}</div>
            </div>
            <hr />
            <div class="d-flex mb-3">
              <div class="flex-fill text-red font-weight-bold w-100">Total</div>
              <div class="flex-fill text-red font-weight-bold w-100 text-right">$43.0</div>
            </div>
            <div class="d-flex mb-3">
              <div class="flex-fill text-red font-weight-bold w-100 text-right">(other currency)</div>
            </div>
            <a href="#" class="btn btn-block btn-payment text-uppercase">Payment</a>
          </div>
        </div>
        <!-- END SUMMARY -->
      </div>
    </div>
  </div>
</div>

<?php
$script = <<< JS
// Review Form
function calculateCart() {
  var form = $('form#add-cart-form');
  var calculateUrl = form.attr('data-calculatecart-url');
  $.ajax({
      url: calculateUrl,
      type: 'POST',
      dataType : 'json',
      data: form.serialize(),
      success: function (result, textStatus, jqXHR) {
        if (result.status == false) {
            toastr.error(errors);
        } else {
            var canSale = parseInt(result.data.amount);
            if (canSale) {
              $('#price').html('$' + result.data.amount);
              $('#btn-buy').show();
              $('#btn-quickbuy').show();
            } else {
              $('#price').html('Contact');
              $('#btn-buy').hide();
              $('#btn-quickbuy').hide();
            }
            $('#game-unit').html(result.data.unit);
        }
      },
  });
}
$('#quantity').on('change', function() {  
  standarizeQuantity();
  calculateCart();
});

function standarizeQuantity() {
  if (!validateQuantity()) {
    $('#quantity').val(1);
  }
}

function validateQuantity() {
    var num = $("#quantity").val();
    num = parseFloat(num);
    $('#quantity').val(num);
    if (num <= 0) {
        return false;
    }
    if (isNaN(num)) {
        return false;
    }
    return true;
}

// React view on attributes
var currentMethod = '$model->method';
var currentVersion = '$model->version';
var currentPackage = "$model->package";
var mapping = $mapping;
var has_group = $has_group;
var settingVersionMapping = $settingVersionMapping;
var settingPackageMapping = $settingPackageMapping;

$(":radio[name=method]").on('change', function() {
  currentMethod = $(this).attr('id');
  var method = mapping[currentMethod];
  currentVersion = Object.keys(method)[0];
  var version = Object.values(method)[0];
  currentPackage = Object.keys(version)[0];
  changeView();
  changeStar();
});
$("#version").on('change', function() {
  currentVersion = $(this).val();
  var method = mapping[currentMethod];
  var version = method[currentVersion];
  currentPackage = Object.keys(version)[0];
  changeView();
});
$("#package").on('change', function() {
  currentPackage = $(this).val();
  $('#package-name').html(settingPackageMapping[currentPackage]);
  changeView();
});
changeStar();

function changeView() {
  if (!has_group) return;
  var method = mapping[currentMethod];
  console.log('method', method, mapping);
  var versions = Object.keys(method).reduce((p, c) => {
    p[c] = settingVersionMapping[c];
    return p;
  }, {});
  var version = method[currentVersion];
  console.log('version', version);
  var packages = Object.keys(version).reduce((p, c) => {
    p[c] = settingPackageMapping[c];
    return p;
  }, {});
  var game = version[currentPackage];
  console.log('game', game);
  $('#version').html(buildOptions(versions, currentVersion));
  $('#package').html(buildOptions(packages, currentPackage));
  var viewUrl = game['viewUrl'];
  var cartUrl = game['cartUrl'];
  var calculateUrl = game['calculateUrl'];
  var save = game['save'];
  var title = game['title'];
  var content = game['content'];
  var image = game['image'];
  history.pushState({}, '', viewUrl);
  $('#add-cart-form').attr('action', cartUrl);
  $('#add-cart-form').attr('data-calculatecart-url', calculateUrl);
  $('#title').html(title);
  $('#image').attr('src', image);
  $('.breadcrumb-item:last').html(title);
  $('#content').html('<h4 class="card-title">Description</h4>' + content);
  $('#save').html(save);
  $('#quantity').val(1).trigger('change');
};
function changeStar() {
  if (!has_group) return;
  console.log('changeStar', currentMethod);
  var method = $('#' + currentMethod);
  console.log('changeStar', method);
  var price = method.data('price');
  var speed = method.data('speed');
  var safe = method.data('safe');
  console.log('method', price, speed, safe);

  $('#star-price li, #star-speed li, #star-safe li').removeClass('selected');
  $('#star-price li').filter(function( index ) {
    return index + 1 <= price;
  }).addClass('selected');

  $('#star-speed li').filter(function( index ) {
    return index + 1 <= speed;
  }).addClass('selected');

  $('#star-safe li').filter(function( index ) {
    return index + 1 <= safe;
  }).addClass('selected');
}
function buildOptions(obj, sel) {
  console.log('buildOptions', obj);
  html = '';
  for (var index in obj) {
    var item = obj[index];
    var selected = sel == index ? 'selected' : '';
    html += '<option value="'+index+'" '+selected+'>'+item+'</option>';
  };
  return html;
}
changeView();

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
$script = <<< JS
Vue.component("cartItem", {
  props: ["id", "quantity", "addItem", "deleteItem", "updateQuantity"],
  data() {
    return {
      raw: ''
    }
  },
  methods: {
    minusQuantity() {
      this.updateQuantity(this.id,  Math.max(1, this.quantity - 1));
    }
  },
  template: `<div class="d-flex align-items-centert bg-white">
    <div class="col-md-12">
      <hr/>
      <div class="mb-3 d-flex justify-content-between align-items-center">
        <div class="flex-fill mr-0 mb-0 w-100">
          <h3>Account Info : </h3>
          <p style="color: #CCC;font-style: italic;font-size: 0.9rem;">Kindly provide correct information to avoid long waiting time. Thank you</p>
          <div class="add-quantity d-flex justify-content-between align-items-center">
            <span class="flex-fill minus" @click="minusQuantity()"><img class="icon-sm" src="/images/icon/minus.svg"></span>
            <input type="text" class="quantity-value flex-fill text-center" :value="quantity" @blur="event => updateQuantity(id, event.target.value)">
            <span class="flex-fill plus" @click="updateQuantity(id, quantity + 1)"><img class="icon-sm" src="/images/icon/plus.svg"></span>
          </div>
        </div>
        <div class="flex-fill w-100 field-cartitem-raw">
          <textarea class="form-control raw" :model="raw" rows="3" placeholder="Enter infomation here ...">{{ id }}</textarea>
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
  props: ['id', 'image', 'onSelect'],
  template: `<label class="btn flex-fill btn-secondary" @click="onSelect(id)">
      <input type="radio" name="paygates" autocomplete="off">
      <img class="icon" :src="image"/>
    </label>`
})

const paygates = $paygateJson;
const balance = $balance;

var app = new Vue({
  el: '#cart_items',
  data: {
    price: 10,
    paygate: null,
    items: [],
    currency: 'USD',
  },
  watch: {
  },
  computed: {
    availablePaygates() {
      return paygates.filter(({ currency }) => currency == this.currency);
    },
    quantity() {
      return this.items.reduce((p,c) => p + c.quantity, 0);
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
          return type === 'fix' ? fee : fee * this.quantity;
      } else {
          return 0;
      }
    },
    totalPrice() {
      return this.subPrice + this. this.transferFee;
    }
  },
  methods: {
    addItem() {
      this.items = [...this.items, { id: this.uuidv4(), quantity: 1 }];
    },
    deleteItem(id) {
      if (this.items.length <= 1) {
        alert('You need to keep at least one item');
        return;
      }
      this.items = this.items.filter((item) => {
        return item.id !== id;
      });
    },
    updateQuantity(id, quantity) {
      this.items = this.items.map(item => {
        if (item.id === id) {
          item.quantity = quantity;
        }
        return item;
      });
    },
    choosePaygate(id) {
      console.log('choosePaygate', id);
      this.paygate = id;
    },
    uuidv4() {
      return ([1e7]+-1e3+-4e3+-8e3+-1e11).replace(/[018]/g, c =>
        (c ^ crypto.getRandomValues(new Uint8Array(1))[0] & 15 >> c / 4).toString(16)
      );
    }
  },
  created() {
    if (!this.items.length) {
      this.addItem();
    }
  }
});
JS;
$this->registerJs($script);
?>