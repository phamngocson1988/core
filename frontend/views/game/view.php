<?php
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use frontend\components\cart\CartItem;

$this->registerMetaTag(['property' => 'og:image', 'content' => $game->getImageUrl('150x150')], 'og:image');
$this->registerMetaTag(['property' => 'og:title', 'content' => $game->getMetaTitle()], 'og:title');
$this->registerMetaTag(['property' => 'og:description', 'content' => $game->getMetaDescription()], 'og:description');
$gameId = $game->id;
$unit = $game->pack;
$gamePromotions = array_filter($promotions, function($promotion) use ($gameId) {
  return $promotion->canApplyForGame($gameId);
});
usort($gamePromotions, function($p1, $p2) use ($unit) {
  return ($p1->apply($unit) < $p2->apply($unit)) ? 1 : -1;
});
$gamePromotion = reset($gamePromotions);
?>
<section class="product-detail">
    <div class="container">
        <div class="small-container">
            <div class="row">
                <div class="col col-sm-12">
                    <div class="product-image">
                        <img src="<?=$game->getImageUrl('300x300');?>" alt="">
                    </div>
                    <div class="product-info">
                        <h2 class="product-name">
                            <a href="javascript:void;"><?=$game->title;?></a>
                        </h2>
                        <div class="product-detail-price">
                            <span><?=number_format($game->pack);?> <?=strtoupper($game->unit_name);?></span>
                        </div>
                        <?php if ($gamePromotion) :
                          $benefit = $gamePromotion->getBenefit();
                        ?>
                        <div class="product-detail-code">
                            <div class="prod-code-left">
                                <p>Nhập mã</p>
                                <p><?=$gamePromotion->code;?></p>
                            </div>
                            <div class="prod-code-right">
                                <p>+<?=$gamePromotion->apply($game->pack);?> <?=strtoupper($game->unit_name);?></p>
                                <p>cho HFKEJK</p>
                            </div>
                        </div>
                        <?php endif;?>
                        <?php $form = ActiveForm::begin(['id' => 'add-to-cart', 'class' => 'rd-mailform form-fix', 'options' => ['data-pjax' => 'true']]); ?>
                        <div class="product-temple-total">
                            <table>
                                <thead>
                                    <tr>
                                        <th>Unit</th>
                                        <th>Quantity</th>
                                        <th>Unit Total</th>
                                        <th>Price</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><?=number_format($game->pack);?> <?=strtoupper($game->unit_name);?></td>
                                        <td>
                                            <?= $form->field($game, 'quantity', [
                                            'options' => ['tag' => false],
                                            'inputOptions' => ['class' => 'form-control txt-qty', 'id' => 'quantity'],
                                            'template' => '{input}'
                                            ])->dropDownList(CartItem::$quantites);?>
                                        </td>
                                        <td><span id="unit"><?=number_format($game->getTotalUnit());?></span></td>
                                        <td>
                                            <?php $showOriginPrice = ($game->original_price && $game->original_price > $game->price);?>
                                            <?php if ($showOriginPrice) : ?>
                                            <div class="origin-price">
                                                $<span id="origin-price"><?=number_format($game->getOriginalPrice());?></span>
                                            </div>
                                            <?php endif;?>
                                            <div class="sale-price">
                                                $<span id="price"><?=number_format($game->getTotalPrice());?></span>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr class="instant-total">
                                        <td colspan="3">Instant Total:</td>
                                        <td class="instant-total-number">$<span id="instant-price"><?=number_format($game->getTotalPrice());?></span></td>
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="3">Subtotal:</td>
                                        <td>$<span class="subtotal-num" id="sub-price"><?=number_format($game->getTotalPrice());?></span></td>
                                    </tr>
                                    <?php if ($showOriginPrice) : ?>
                                    <tr>
                                        <td colspan="3">Saving:</td>
                                        <td><span class="saving-number"><?=number_format($game->getSavedPrice());?></span>%</td>
                                    </tr>
                                    <?php endif;?>
                                    <tr class="tr-grand-total">
                                        <td colspan="3">Grand Total:</td>
                                        <td>$<span id="total-price"><?=number_format($game->getTotalPrice());?></span></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        <?php if (!$game->isSoldout()) : ?>
                        <?= Html::submitButton('Add To Cart', ['class' => 'btn-product-detail-add-to-cart', 'data-pjax' => 'false', 'id' => 'add-cart-button']) ?>
                        <button class="btn-product-detail-add-to-cart" data-pjax="false" onclick="window.location='<?=Url::to(['reseller/import', 'id' => $game->id]);?>';">For Bulk of orders</button>
                        <?php endif;?>
                        <?php ActiveForm::end(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="product-description">
    <div class="container">
        <div class="small-container">
            <div class="row">
                <div class="col col-sm-12">
                    <div class="prod-des-content">
                        <h3>Description:</h3>
                        <?=$game->content;?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Product Page-->

<?php
$script = <<< JS
$('.top-header .right-box a.ico-user-login').click(function(){
    $(this).parent().toggleClass('active');
    $(this).toggleClass('active');
});

$('.mobile-nav a.mobile-nav-ico').click(function(){
    $(this).parent().toggleClass('active');
    $(this).toggleClass('active');
});

$('body').on('click', function(e) {
    if($(e.target).closest('#mobile-nav-wrapper').length == 0) {
        $('#mobile-nav-wrapper, .mobile-nav a.mobile-nav-ico').removeClass('active');
    }

    if($(e.target).closest('#login-box-wrapper').length == 0) {
        $('#login-box-wrapper, #login-box-wrapper a.ico-user-login').removeClass('active');
    }
});

$('.product-temple-total .quantity-box button.quantity-plus').click(function(){
    var _qty = $('.product-temple-total .quantity-box input').val();
    var step = 0.5;
    if (_qty < 10) {
        _qty = parseFloat(_qty) + step;
        $('.product-temple-total .quantity-box input').val(_qty);
        $("#quantity").trigger('change');
    }
});

$('.product-temple-total .quantity-box button.quantity-minus').click(function(){
    var _qty = $('.product-temple-total .quantity-box input').val();
    _qty = parseFloat(_qty);
    var step = 0.5;
    if (_qty > step) {
        _qty = _qty - step;
        $('.product-temple-total .quantity-box input').val(_qty);
        $("#quantity").trigger('change');
    } 
});

// For update quantity
$('body').on('change', "#quantity", function(){
  $('#action').val('change');
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
          $('#origin-price').html(result.data.origin);
          $('#price, #total-price, #sub-price, #instant-price').html(result.data.price);
          $('#unit').html(result.data.unit);
        }
      },
  });
});
$("#quantity").trigger('change');

JS;
$this->registerJs($script);
?>
