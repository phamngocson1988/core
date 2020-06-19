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
  <div class="d-flex justify-content-between align-items-centert bg-white">
    <div class="w-50 flex-fill">
      <div class="single-img-slider">
        <img class="single-img" src="<?=$model->getImageUrl('555x691');?>" />
      </div>
    </div>
    <div class="w-50 flex-fill">
      <div class="content p-4">
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">Home</a></li>
            <?php if ($model->hasCategory()) : ?>
            <?php $category =  reset($model->categories);?>
            <li class="breadcrumb-item"><a href="javascript:;"><?=$category->name;?></a></li>
            <?php endif;?>
            <li class="breadcrumb-item active" aria-current="page"><?=$model->title;?></li>
          </ol>
        </nav>
        <h1 class="text-red mb-0"><?=$model->title;?></h1>
        <p class="lead">Pack name here</p>
        <div class="btn-group-toggle multi-choose d-flex" data-toggle="buttons">
          <label class="btn flex-fill w-100 mr-2 btn-secondary active">
            <input type="radio" name="options" id="option1" autocomplete="off" checked>Cost Saving
          </label>
          <label class="btn flex-fill w-100 mr-2 btn-secondary">
            <input type="radio" name="options" id="option2" autocomplete="off"> Fast top-up
          </label>
          <label class="btn flex-fill w-100 btn-secondary">
            <input type="radio" name="options" id="option3" autocomplete="off"> Gift top-up
          </label>
        </div>
        <div class="price py-3">
          <span class="price-value text-red mr-2" id="price">$<?=number_format($model->getPrice());?></span>
          <span class="badge badge-danger">save <?=number_format($model->getSavedPrice());?>%</span>
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
        <div class="row multi-select p-2">
          <div class="col-md-4">
            <div class="form-group">
              <label for="exampleFormControlSelect1">Version</label>
              <select class="form-control" id="exampleFormControlSelect1">
                <option>1</option>
                <option>2</option>
                <option>3</option>
                <option>4</option>
                <option>5</option>
              </select>
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              <label for="exampleFormControlSelect2">Pack</label>
              <select class="form-control" id="exampleFormControlSelect2">
                <option>1</option>
                <option>2</option>
                <option>3</option>
                <option>4</option>
                <option>5</option>
              </select>
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              <label for="exampleFormControlSelect3">Currency</label>
              <select class="form-control" id="exampleFormControlSelect3">
                <option>1</option>
                <option>2</option>
                <option>3</option>
                <option>4</option>
                <option>5</option>
              </select>
            </div>
          </div>
        </div>

        <?php $form = ActiveForm::begin(['action' => Url::to(['cart/add', 'id' => $model->id]), 'options' => ['id' => 'add-cart-form', 'data-calculatecart-url' => Url::to(['cart/calculate', 'id' => $model->id])]]);?>
        <div class="multi-button d-flex justify-content-between align-items-center">
          <div class="w-100 flex-fill p-2">
            <?= $form->field($model, 'quantity', [
              'options' => ['class' => 'd-flex justify-content-between align-items-center'],
              'labelOptions' => ['class' => 'w-100 flex-fill'],
              'template' => '{label}<div class="w-100 flex-fill">{input}</div>',
              'inputOptions' => ['class' => 'form-control', 'type' => 'number', 'id' => 'quantity', 'min' => 1]
            ])->textInput()->label('Quanity') ?>
          </div>
          <div class="w-100 flex-fill p-2">
            <button type="submit" class="btn btn-buy">Buy now</button>
          </div>
          <div class="w-100 flex-fill p-2">
            <button type="button" class="btn btn-quickbuy"><img class="icon-sm" src="/images/icon/timer.svg" /> Quick
              Buy</button>
          </div>
        </div>
        <?php ActiveForm::end(); ?>
        <hr />
        <div class="multi-rating d-flex justify-content-between align-items-center">
          <div class="p-2 flex-fill bd-highlight">
            <!-- Rating Stars Box -->
            <div class='rating-stars text-center'>
              Price
              <ul id=''>
                <li class='star selected' title='Poor' data-value='1'>
                  <span class="icon-star"></span>
                </li>
                <li class='star selected' title='Fair' data-value='2'>
                  <span class="icon-star"></span>
                </li>
                <li class='star selected' title='Good' data-value='3'>
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
              Speed
              <ul id=''>
                <li class='star selected' title='Poor' data-value='1'>
                  <span class="icon-star"></span>
                </li>
                <li class='star selected' title='Fair' data-value='2'>
                  <span class="icon-star"></span>
                </li>
                <li class='star selected' title='Good' data-value='3'>
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
              <ul id=''>
                <li class='star selected' title='Poor' data-value='1'>
                  <span class="icon-star"></span>
                </li>
                <li class='star selected' title='Fair' data-value='2'>
                  <span class="icon-star"></span>
                </li>
                <li class='star selected' title='Good' data-value='3'>
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
    <div class="card-body">
      <h4 class="card-title">Description</h4>
      <?=$model->content;?>
    </div>
  </div>
</div><!-- END MAIN SINGLE -->
<div class="container my-5">
  <div class="d-flex justify-content-between align-items-center mb-5">
    <h2 class="block-title text-uppercase mb-0 flex-fill">You may also like</h2>
    <div class="flex-fill">
      <a href="#" class="link-dark font-weight-bold">View all <img class="icon-sm" src="/images/icon/next.svg"/></a>
    </div>
  </div>
  <div class="post-wrapper post-slider">
    <div class="post-item card">
      <div class="post-thumb">
        <a href="#" class="hover-img">
          <img src="/images/post-item01.jpg" />
        </a>
        <span class="tag save">
          save 60%
        </span>
        <span class="tag promotion">
          promotion
        </span>
        <span class="tag bts">
          back to stock
        </span>
      </div>
      <div class="post-content">
        <h4 class="post-title">
          <a href="#">Extraordinary Ones</a>
        </h4>
        <div class="tags">
          <img src="/images/icon/tag.svg" />
          <span class="badge badge-primary">action</span>
          <span class="badge badge-primary">role-playing</span>
          <span class="badge badge-primary">moba</span>
        </div>
        <div class="d-flex justify-content-between align-items-center py-2">
          <div class="flex-fill value">
            <span class="num">6,000</span>
            <br />
            <span class="text">Gems</span>
          </div>
          <div class="flex-fill price">
            <strike>$100</strike> <span class="num">$36</span>
          </div>
        </div>
        <div class="d-flex justify-content-between align-items-center">
          <div class="flex-fill status">
            <hr>
            <img class="icon-fire" src="/images/icon/fire.svg" />
            <span class="text">
              in stock
            </span>
          </div>
          <div class="flex-fill">
            <a href="#" class="main-btn">
              <span>BUY NOW</span>
            </a>
          </div>
        </div>
      </div>
    </div><!-- END POST ITEM -->
    <div class="post-item card">
      <div class="post-thumb">
        <a href="#" class="hover-img">
          <img src="/images/post-item01.jpg" />
        </a>
        <span class="tag save">
          save 60%
        </span>
        <span class="tag promotion">
          promotion
        </span>
        <span class="tag bts">
          back to stock
        </span>
      </div>
      <div class="post-content">
        <h4 class="post-title">
          <a href="#">Extraordinary Ones</a>
        </h4>
        <div class="tags">
          <img src="/images/icon/tag.svg" />
          <span class="badge badge-primary">action</span>
          <span class="badge badge-primary">role-playing</span>
          <span class="badge badge-primary">moba</span>
        </div>
        <div class="d-flex justify-content-between align-items-center py-2">
          <div class="flex-fill value">
            <span class="num">6,000</span>
            <br />
            <span class="text">Gems</span>
          </div>
          <div class="flex-fill price">
            <strike>$100</strike> <span class="num">$36</span>
          </div>
        </div>
        <div class="d-flex justify-content-between align-items-center">
          <div class="flex-fill status">
            <hr>
            <img class="icon-fire" src="/images/icon/fire.svg" />
            <span class="text">
              in stock
            </span>
          </div>
          <div class="flex-fill">
            <a href="#" class="main-btn">
              <span>BUY NOW</span>
            </a>
          </div>
        </div>
      </div>
    </div><!-- END POST ITEM -->
    <div class="post-item card">
      <div class="post-thumb">
        <a href="#" class="hover-img">
          <img src="/images/post-item01.jpg" />
        </a>
        <span class="tag save">
          save 60%
        </span>
        <span class="tag promotion">
          promotion
        </span>
        <span class="tag bts">
          back to stock
        </span>
      </div>
      <div class="post-content">
        <h4 class="post-title">
          <a href="#">Extraordinary Ones</a>
        </h4>
        <div class="tags">
          <img src="/images/icon/tag.svg" />
          <span class="badge badge-primary">action</span>
          <span class="badge badge-primary">role-playing</span>
          <span class="badge badge-primary">moba</span>
        </div>
        <div class="d-flex justify-content-between align-items-center py-2">
          <div class="flex-fill value">
            <span class="num">6,000</span>
            <br />
            <span class="text">Gems</span>
          </div>
          <div class="flex-fill price">
            <strike>$100</strike> <span class="num">$36</span>
          </div>
        </div>
        <div class="d-flex justify-content-between align-items-center">
          <div class="flex-fill status">
            <hr>
            <img class="icon-fire" src="/images/icon/fire.svg" />
            <span class="text">
              in stock
            </span>
          </div>
          <div class="flex-fill">
            <a href="#" class="main-btn">
              <span>BUY NOW</span>
            </a>
          </div>
        </div>
      </div>
    </div><!-- END POST ITEM -->
    <div class="post-item card">
      <div class="post-thumb">
        <a href="#" class="hover-img">
          <img src="/images/post-item01.jpg" />
        </a>
        <span class="tag save">
          save 60%
        </span>
        <span class="tag promotion">
          promotion
        </span>
        <span class="tag bts">
          back to stock
        </span>
      </div>
      <div class="post-content">
        <h4 class="post-title">
          <a href="#">Extraordinary Ones</a>
        </h4>
        <div class="tags">
          <img src="/images/icon/tag.svg" />
          <span class="badge badge-primary">action</span>
          <span class="badge badge-primary">role-playing</span>
          <span class="badge badge-primary">moba</span>
        </div>
        <div class="d-flex justify-content-between align-items-center py-2">
          <div class="flex-fill value">
            <span class="num">6,000</span>
            <br />
            <span class="text">Gems</span>
          </div>
          <div class="flex-fill price">
            <strike>$100</strike> <span class="num">$36</span>
          </div>
        </div>
        <div class="d-flex justify-content-between align-items-center">
          <div class="flex-fill status">
            <hr>
            <img class="icon-fire" src="/images/icon/fire.svg" />
            <span class="text">
              in stock
            </span>
          </div>
          <div class="flex-fill">
            <a href="#" class="main-btn">
              <span>BUY NOW</span>
            </a>
          </div>
        </div>
      </div>
    </div><!-- END POST ITEM -->
    <div class="post-item card">
      <div class="post-thumb">
        <a href="#" class="hover-img">
          <img src="/images/post-item01.jpg" />
        </a>
        <span class="tag save">
          save 60%
        </span>
        <span class="tag promotion">
          promotion
        </span>
        <span class="tag bts">
          back to stock
        </span>
      </div>
      <div class="post-content">
        <h4 class="post-title">
          <a href="#">Extraordinary Ones</a>
        </h4>
        <div class="tags">
          <img src="/images/icon/tag.svg" />
          <span class="badge badge-primary">action</span>
          <span class="badge badge-primary">role-playing</span>
          <span class="badge badge-primary">moba</span>
        </div>
        <div class="d-flex justify-content-between align-items-center py-2">
          <div class="flex-fill value">
            <span class="num">6,000</span>
            <br />
            <span class="text">Gems</span>
          </div>
          <div class="flex-fill price">
            <strike>$100</strike> <span class="num">$36</span>
          </div>
        </div>
        <div class="d-flex justify-content-between align-items-center">
          <div class="flex-fill status">
            <hr>
            <img class="icon-fire" src="/images/icon/fire.svg" />
            <span class="text">
              in stock
            </span>
          </div>
          <div class="flex-fill">
            <a href="#" class="main-btn">
              <span>BUY NOW</span>
            </a>
          </div>
        </div>
      </div>
    </div><!-- END POST ITEM -->
    <div class="post-item card">
      <div class="post-thumb">
        <a href="#" class="hover-img">
          <img src="/images/post-item01.jpg" />
        </a>
        <span class="tag save">
          save 60%
        </span>
        <span class="tag promotion">
          promotion
        </span>
        <span class="tag bts">
          back to stock
        </span>
      </div>
      <div class="post-content">
        <h4 class="post-title">
          <a href="#">Extraordinary Ones</a>
        </h4>
        <div class="tags">
          <img src="/images/icon/tag.svg" />
          <span class="badge badge-primary">action</span>
          <span class="badge badge-primary">role-playing</span>
          <span class="badge badge-primary">moba</span>
        </div>
        <div class="d-flex justify-content-between align-items-center py-2">
          <div class="flex-fill value">
            <span class="num">6,000</span>
            <br />
            <span class="text">Gems</span>
          </div>
          <div class="flex-fill price">
            <strike>$100</strike> <span class="num">$36</span>
          </div>
        </div>
        <div class="d-flex justify-content-between align-items-center">
          <div class="flex-fill status">
            <hr>
            <img class="icon-fire" src="/images/icon/fire.svg" />
            <span class="text">
              in stock
            </span>
          </div>
          <div class="flex-fill">
            <a href="#" class="main-btn">
              <span>BUY NOW</span>
            </a>
          </div>
        </div>
      </div>
    </div><!-- END POST ITEM -->
  </div> <!-- END POST SLIDER -->
</div><!-- END container -->
<div class="container my-5">
  <div class="d-flex justify-content-between align-items-center mb-5">
    <h2 class="block-title text-uppercase mb-0 flex-fill">PROMOTIONS</h2>
    <div class="flex-fill">
      <a href="#" class="link-dark font-weight-bold">View all <img class="icon-sm" src="/images/icon/next.svg"/></a>
    </div>
  </div>

  <div class="post-wrapper post-slider">
    <div class="post-item card">
      <div class="post-thumb">
        <a href="#" class="hover-img">
          <img src="/images/post-item01.jpg" />
        </a>
        <span class="tag save">
          save 60%
        </span>
        <span class="tag promotion">
          promotion
        </span>
        <span class="tag bts">
          back to stock
        </span>
      </div>
      <div class="post-content">
        <h4 class="post-title">
          <a href="#">Extraordinary Ones</a>
        </h4>
        <div class="tags">
          <img src="/images/icon/tag.svg" />
          <span class="badge badge-primary">action</span>
          <span class="badge badge-primary">role-playing</span>
          <span class="badge badge-primary">moba</span>
        </div>
        <div class="d-flex justify-content-between align-items-center py-2">
          <div class="flex-fill value">
            <span class="num">6,000</span>
            <br />
            <span class="text">Gems</span>
          </div>
          <div class="flex-fill price">
            <strike>$100</strike> <span class="num">$36</span>
          </div>
        </div>
        <div class="d-flex justify-content-between align-items-center">
          <div class="flex-fill status">
            <hr>
            <img class="icon-fire" src="/images/icon/fire.svg" />
            <span class="text">
              in stock
            </span>
          </div>
          <div class="flex-fill">
            <a href="#" class="main-btn">
              <span>BUY NOW</span>
            </a>
          </div>
        </div>
      </div>
    </div><!-- END POST ITEM -->
    <div class="post-item card">
      <div class="post-thumb">
        <a href="#" class="hover-img">
          <img src="/images/post-item01.jpg" />
        </a>
        <span class="tag save">
          save 60%
        </span>
        <span class="tag promotion">
          promotion
        </span>
        <span class="tag bts">
          back to stock
        </span>
      </div>
      <div class="post-content">
        <h4 class="post-title">
          <a href="#">Extraordinary Ones</a>
        </h4>
        <div class="tags">
          <img src="/images/icon/tag.svg" />
          <span class="badge badge-primary">action</span>
          <span class="badge badge-primary">role-playing</span>
          <span class="badge badge-primary">moba</span>
        </div>
        <div class="d-flex justify-content-between align-items-center py-2">
          <div class="flex-fill value">
            <span class="num">6,000</span>
            <br />
            <span class="text">Gems</span>
          </div>
          <div class="flex-fill price">
            <strike>$100</strike> <span class="num">$36</span>
          </div>
        </div>
        <div class="d-flex justify-content-between align-items-center">
          <div class="flex-fill status">
            <hr>
            <img class="icon-fire" src="/images/icon/fire.svg" />
            <span class="text">
              in stock
            </span>
          </div>
          <div class="flex-fill">
            <a href="#" class="main-btn">
              <span>BUY NOW</span>
            </a>
          </div>
        </div>
      </div>
    </div><!-- END POST ITEM -->
    <div class="post-item card">
      <div class="post-thumb">
        <a href="#" class="hover-img">
          <img src="/images/post-item01.jpg" />
        </a>
        <span class="tag save">
          save 60%
        </span>
        <span class="tag promotion">
          promotion
        </span>
        <span class="tag bts">
          back to stock
        </span>
      </div>
      <div class="post-content">
        <h4 class="post-title">
          <a href="#">Extraordinary Ones</a>
        </h4>
        <div class="tags">
          <img src="/images/icon/tag.svg" />
          <span class="badge badge-primary">action</span>
          <span class="badge badge-primary">role-playing</span>
          <span class="badge badge-primary">moba</span>
        </div>
        <div class="d-flex justify-content-between align-items-center py-2">
          <div class="flex-fill value">
            <span class="num">6,000</span>
            <br />
            <span class="text">Gems</span>
          </div>
          <div class="flex-fill price">
            <strike>$100</strike> <span class="num">$36</span>
          </div>
        </div>
        <div class="d-flex justify-content-between align-items-center">
          <div class="flex-fill status">
            <hr>
            <img class="icon-fire" src="/images/icon/fire.svg" />
            <span class="text">
              in stock
            </span>
          </div>
          <div class="flex-fill">
            <a href="#" class="main-btn">
              <span>BUY NOW</span>
            </a>
          </div>
        </div>
      </div>
    </div><!-- END POST ITEM -->
    <div class="post-item card">
      <div class="post-thumb">
        <a href="#" class="hover-img">
          <img src="/images/post-item01.jpg" />
        </a>
        <span class="tag save">
          save 60%
        </span>
        <span class="tag promotion">
          promotion
        </span>
        <span class="tag bts">
          back to stock
        </span>
      </div>
      <div class="post-content">
        <h4 class="post-title">
          <a href="#">Extraordinary Ones</a>
        </h4>
        <div class="tags">
          <img src="/images/icon/tag.svg" />
          <span class="badge badge-primary">action</span>
          <span class="badge badge-primary">role-playing</span>
          <span class="badge badge-primary">moba</span>
        </div>
        <div class="d-flex justify-content-between align-items-center py-2">
          <div class="flex-fill value">
            <span class="num">6,000</span>
            <br />
            <span class="text">Gems</span>
          </div>
          <div class="flex-fill price">
            <strike>$100</strike> <span class="num">$36</span>
          </div>
        </div>
        <div class="d-flex justify-content-between align-items-center">
          <div class="flex-fill status">
            <hr>
            <img class="icon-fire" src="/images/icon/fire.svg" />
            <span class="text">
              in stock
            </span>
          </div>
          <div class="flex-fill">
            <a href="#" class="main-btn">
              <span>BUY NOW</span>
            </a>
          </div>
        </div>
      </div>
    </div><!-- END POST ITEM -->
    <div class="post-item card">
      <div class="post-thumb">
        <a href="#" class="hover-img">
          <img src="/images/post-item01.jpg" />
        </a>
        <span class="tag save">
          save 60%
        </span>
        <span class="tag promotion">
          promotion
        </span>
        <span class="tag bts">
          back to stock
        </span>
      </div>
      <div class="post-content">
        <h4 class="post-title">
          <a href="#">Extraordinary Ones</a>
        </h4>
        <div class="tags">
          <img src="/images/icon/tag.svg" />
          <span class="badge badge-primary">action</span>
          <span class="badge badge-primary">role-playing</span>
          <span class="badge badge-primary">moba</span>
        </div>
        <div class="d-flex justify-content-between align-items-center py-2">
          <div class="flex-fill value">
            <span class="num">6,000</span>
            <br />
            <span class="text">Gems</span>
          </div>
          <div class="flex-fill price">
            <strike>$100</strike> <span class="num">$36</span>
          </div>
        </div>
        <div class="d-flex justify-content-between align-items-center">
          <div class="flex-fill status">
            <hr>
            <img class="icon-fire" src="/images/icon/fire.svg" />
            <span class="text">
              in stock
            </span>
          </div>
          <div class="flex-fill">
            <a href="#" class="main-btn">
              <span>BUY NOW</span>
            </a>
          </div>
        </div>
      </div>
    </div><!-- END POST ITEM -->
  </div> <!-- END POST SLIDER -->
</div><!-- END container -->

<?php
$script = <<< JS
// Review Form
function calculateCart() {
  var form = $('form#add-cart-form');
  var calculateUrl = form.data('calculatecart-url');
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

JS;
$this->registerJs($script);
?>