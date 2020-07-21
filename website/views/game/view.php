<?php
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use common\components\helpers\FormatConverter;

$this->registerMetaTag(['property' => 'og:image', 'content' => $model->getImageUrl('150x150')], 'og:image');
$this->registerMetaTag(['property' => 'og:title', 'content' => $model->getMetaTitle()], 'og:title');
$this->registerMetaTag(['property' => 'og:description', 'content' => $model->getMetaDescription()], 'og:description');
?>
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
        <p class="lead">Pack name here</p>
        <?php if ($methods) : ?>
        <div class="btn-group-toggle multi-choose d-flex" data-toggle="buttons">
          <?php foreach ($methods as $method) : ?>
          <?php 
          $settingMethodParam = ArrayHelper::getValue($settingMethodParams, $method, []);
          $settingMethodPrice = ArrayHelper::getValue($settingMethodParam, 'price', 0);
          $settingMethodSpeed = ArrayHelper::getValue($settingMethodParam, 'speed', 0);
          $settingMethodSafe = ArrayHelper::getValue($settingMethodParam, 'safe', 0);
          $settingMethodTitle = ArrayHelper::getValue($settingMethodParam, 'name', '');
          ?>
          <label class="btn flex-fill w-100 mr-2 btn-secondary <?=($method == $model->method) ? 'active' : '';?>">
            <input type="radio" name="method" id="<?=$method;?>" autocomplete="off" <?=($method == $model->method) ? 'checked' : '';?> data-price="<?=$settingMethodPrice;?>" data-speed="<?=$settingMethodSpeed;?>" data-safe="<?=$settingMethodSafe;?>"><?=$settingMethodTitle;?>
          </label>
          <?php endforeach;?>
        </div>
        <?php endif;?>
        <div class="price py-3">
          <span class="price-value text-red mr-2" id="price">$<?=number_format($model->getPrice());?></span>
          <span class="badge badge-danger" id="save"><?=sprintf("save %s", number_format($model->getSavedPrice()));?>%</span>
          <span class="btn-group-toggle bell" data-toggle="buttons">
            <label class="btn">
              <input type="checkbox">
            </label>
          </span>
        </div>
        <div class="d-flex align-content-end">
          <div class="w-100 flex-fill">
            <span class="gems-value" id="game-unit"><?=sprintf("%s %s", number_format($model->getUnit()), strtoupper($model->getUnitName()));?></span>
          </div>
          <div class="w-100 flex-fill">
            <div class="d-flex bd-highlight">
              <div class="w-100 flex-fill">ETA:</div>
              <div class="w-100 flex-fill"><b>2 - 4h</b></div>
            </div>
            <div class="d-flex bd-highlight">
              <div class="w-100 flex-fill">Status:</div>
              <div class="w-100 flex-fill"><b>Available</b></div>
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
            <!-- <div class="form-group">
              <label for="exampleFormControlSelect3">Currency</label>
              <select class="form-control" id="exampleFormControlSelect3">
                <option>USD</option>
                <option>CNY</option>
              </select>
            </div> -->
            <?= $form->field($model, 'currency')->dropdownList($model->fetchCurrency())->label('Quantity') ?>
          </div>
        </div>
        <div class="multi-button d-flex justify-content-between align-items-center">
          <div class="w-100 flex-fill p-2">
            <?= $form->field($model, 'quantity', [
              'options' => ['class' => 'd-flex justify-content-between align-items-center'],
              'labelOptions' => ['class' => 'w-100 flex-fill'],
              'template' => '{label}<div class="w-100 flex-fill">{input}</div>',
              'inputOptions' => ['class' => 'form-control', 'type' => 'number', 'id' => 'quantity', 'min' => 1]
            ])->textInput()->label('Quantity') ?>
          </div>
          <div class="w-100 flex-fill p-2">
            <?php if (Yii::$app->user->isGuest) : ?>
            <a href="#modalLogin" class="btn btn-buy" data-toggle="modal">Buy now</a>
            <?php else :?>
            <button type="submit" class="btn btn-buy">Buy now</button>
            <?php endif;?>
          </div>
          <?php if ($is_reseller) : ?>
          <div class="w-100 flex-fill p-2">
            <?php if (Yii::$app->user->isGuest) : ?>
            <a href="#modalLogin" class="btn btn-quickbuy" data-toggle="modal"><img class="icon-sm" src="/images/icon/timer.svg" />Buy now</a>
            <?php else :?>
            <a href="<?=Url::to(['game/quick', 'id' => $model->id, 'slug' => $model->slug]);?>" class="btn btn-quickbuy"><img class="icon-sm" src="/images/icon/timer.svg" /> Quick
              Buy</a>
            <?php endif;?>
          </div>
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
      </div>
    </div>
  </div>
  <!-- DES -->
  <div class="card bg-light mt-3">
    <div class="card-body" id="content">
      <h4 class="card-title">Description</h4>
      <?=$model->content;?>
    </div>
  </div>
</div><!-- END MAIN SINGLE -->
<?php if ($relatedGames) : ?>
<div class="container my-5">
  <div class="d-flex justify-content-between align-items-center mb-5">
    <h2 class="block-title text-uppercase mb-0 flex-fill">You may also like</h2>
    <div class="flex-fill">
      <a href="<?=Url::to(['game/index', 'category_id' => $category->id]);?>" class="link-dark font-weight-bold">View all <img class="icon-sm" src="/images/icon/next.svg"/></a>
    </div>
  </div>
  <div class="post-wrapper post-slider">
    <?php foreach ($relatedGames as $game) : ?>
    <?php $viewUrl = Url::to(['game/view', 'id' => $game->id, 'slug' => $game->slug]);?>
    <div class="post-item card">
      <div class="post-thumb">
        <a href="<?=$viewUrl;?>" class="hover-img">
          <img src="<?=$game->getImageUrl('300x300');?>" />
        </a>
        <?php if ($game->getSavedPrice()) : ?>
        <span class="tag save">save <?=number_format($game->getSavedPrice());?>%</span>
        <?php endif;?>
        <span class="tag promotion">promotion</span>
        <?php if ($game->isBackToStock()) : ?>
        <span class="tag bts">back to stock</span>
        <?php endif;?>
      </div>
      <div class="post-content">
        <h4 class="post-title">
          <a href="<?=$viewUrl;?>"><?=Html::encode($game->title);?></a>
        </h4>
        <?php if ($game->hasCategory()) : ?>
        <div class="tags">
          <img src="/images/icon/tag.svg" />
          <?php foreach ($game->categories as $category) : ?>
          <span class="badge badge-primary"><?=$category->name;?></span>
          <?php endforeach; ?>
        </div>
        <?php endif;?>
        <div class="d-flex justify-content-between align-items-center py-2">
          <div class="flex-fill value">
            <span class="num"><?=number_format($game->pack);?></span>
            <br />
            <span class="text"><?=Html::encode($game->unit_name);?></span>
          </div>
          <div class="flex-fill price">
            <strike>$<?=number_format($game->getOriginalPrice());?></strike> <span class="num">$<?=number_format($game->getPrice());?></span>
          </div>
        </div>
        <div class="d-flex justify-content-between align-items-center">
          <div class="flex-fill status">
            <hr>
            <img class="icon-fire" src="/images/icon/fire.svg" />
            <?php if ($game->isSoldout()) :?>
            <span class="text" style="color: gray">out stock</span>
            <?php else : ?>
            <span class="text">in stock</span>
            <?php endif;?>
          </div>
          <div class="flex-fill">
            <a href="<?=$viewUrl;?>" class="main-btn">
            <span>BUY NOW</span>
            </a>
          </div>
        </div>
      </div>
    </div>
    <?php endforeach;?>
  </div> <!-- END POST SLIDER -->
</div><!-- END container -->
<?php endif;?>

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
            $('#price').html('$' + result.data.amount);
            $('#game-unit').html(result.data.unit);
        }
      },
  });
}
$('#quantity').on('change', function() {  
  calculateCart();
});

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
  changeView();
});
// changeStar();

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
JS;
$this->registerJs($script);
?>