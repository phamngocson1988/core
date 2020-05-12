<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\bootstrap\ActiveForm;
use yii\widgets\Pjax;
?>
<section class="page-title">
  <div class="container">
    <div class="row">
      <div class="col col-sm-12">
        <div class="page-title-content text-center">
          <img src="/images/text-top-up.png" alt="">
        </div>
        <div class="page-title-sub">
          <p>Simple, Convenient and Get extra bonus UP TO 5%</p>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="topup-page">
  <div class="container">
    <div class="row">
      <div class="col col-lg-12 col-md-12 col-sm-12 col-12">
        <div class="row">
          <?php foreach ($items as $item) :?>
          <div class="col col-12 col-lg-4 col-md-4 col-sm-12 pack-item">
            <div class="topup-pack">
              <div class="pack-name"><?=$item->title;?></div>
              <div class="pack-info">
                <?php $form = ActiveForm::begin([
                  'action' => Url::to(['topup/add', 'id' => $item->id]),
                  'options' => ['class' => 'add-to-cart', 'id' => "package-$item->id"]
                ]); ?>
                <div class="pack-info-line">
                  <span class="price"><sup>$</sup><span class="usd-price"><?=number_format($item->amount);?></span></span>
                </div>
                <div class="pack-info-line">
                  <div class="pack-king-price">
                    <span class="ico-king-coin"></span><span class="king-coin-price"><?=number_format($item->getCoin());?></span><span class="kc-text">King Coins</span>
                  </div>
                </div>
                
                <div class="pack-info-line">
                  <div class="pack-qty">
                    <button type="button" class="minus">-</button>
                    <?= $form->field($item, 'quantity', [
                      'options' => ['tag' => false],
                      'inputOptions' => ['class' => 'pack-qty-txt quantity-control', 'type' => 'number', 'min' => 1, 'id' => "item" . $item->id, 'value' => 1],
                      'template' => '{input}'
                    ])->textInput() ?>
                    <button type="button" class="plus">+</button>
                  </div>
                </div>
                <div class="pack-info-line">
                  <?= Html::submitButton('Buy now', ['class' => 'cus-btn yellow', 'onclick' => 'showLoader()']) ?>
                </div>
                <?php ActiveForm::end();?>
              </div>
            </div>
          </div>
          <?php endforeach;?>
        </div>
      </div>
    </div>
  </div>
</section>
<?php
$script = <<< JS
$('body').on('change', ".quantity-control", function() {
  // $(this).closest('form').submit();
  var form = $(this).closest('form');
  $.ajax({
      url: form.attr('action'),
      type: form.attr('method'),
      dataType : 'json',
      data: form.serialize(),
      success: function (result, textStatus, jqXHR) {
        if (!result.status) {
          alert(result.error);
        } else {
          form.find('.usd-price').html(result.data.price);
          form.find('.king-coin-price').html(result.data.coin);
        }
      },
  });
});

$('.minus').click(function(){
    var input = $(this).closest('.pack-qty').find('input.pack-qty-txt');
    var _qty = input.val();
    _qty = parseInt(_qty)-1;
    input.val(Math.max(_qty, 1));
    input.trigger('change');
});

$('.plus').click(function(){
    var input = $(this).closest('.pack-qty').find('input.pack-qty-txt');
    var _qty = input.val();
    _qty = parseInt(_qty)+1;
    input.val(_qty);
    input.trigger('change');
});
JS;
$this->registerJs($script);
?>
