<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

?>
<!-- Product Page-->
<section class="section section-lg bg-default">
  <!-- section wave-->
  <div class="container container-bigger product-single">
    <div class="row row-fix justify-content-sm-center justify-content-lg-between row-30 align-items-lg-center">
      <div class="col-lg-5 col-xl-6 col-xxl-5">
        <div class="product-single-preview">
          <div class="unit flex-column flex-md-row align-items-md-center unit-spacing-md-midle unit--inverse unit-sm">
            <div class="unit-body">
              <ul class="product-thumbnails">
                <li class="active" data-large-image="<?=$game->getImageUrl('420x550');?>"><img src="<?=$game->getImageUrl('100x100');?>" alt="" width="95" height="95"></li>
                <?php foreach ($game->images as $image) :?>
                <li class="active" data-large-image="<?=$image->getImageUrl('420x550');?>"><img src="<?=$image->getImageUrl('100x100');?>" alt="" width="95" height="95"></li>
                <?php endforeach ;?>
              </ul>
            </div>
            <div class="unit-right product-single-image">
              <div class="product-single-image-element"><img class="product-image-area animateImageIn" src="<?=$game->getImageUrl('420x550');?>" alt=""></div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-lg-7 col-xl-6 col-xxl-6 text-center text-lg-left">
        <?php $form = ActiveForm::begin(['id' => 'add-to-cart', 'class' => 'rd-mailform form-fix', 'options' => ['data-pjax' => 'true']]); ?>
        <h3><?=$game->title;?></h3>
        <div class="divider divider-default"></div>
        <p class="text-spacing-sm"><?=$game->excerpt;?></p>
        <ul class="inline-list">
          <li class="text-center"><span class="icon novi-icon icon-md mdi mdi-coin text-secondary-3"></span>
            <p class="text-spacing-sm offset-0">Price<br><h4 id="price"><?=$game->getTotalPrice();?></h4></p>
          </li>
          <li class="text-center"><span class="icon novi-icon icon-md mdi mdi-trophy text-secondary-3"></span>
            <p class="text-spacing-sm offset-0"><?=ucfirst($game->unit_name);?><br><h4 id="unit"><?=$game->getTotalUnit();?></h4></p>
          </li>
        </ul>
        <ul class="inline-list">
          <li class="text-middle">
            <?= $form->field($game, 'quantity', [
              'options' => ['class' => 'form-wrap box-width-1 shop-input'],
              'inputOptions' => ['class' => 'form-input input-append', 'type' => 'number', 'min' => 0.5, 'step' => 0.5, 'id' => 'quantity'],
              'template' => '{input}{error}'
            ])->textInput() ?>
          </li>
          <li class="text-middle">
            <?= Html::hiddenInput('action', null, ['id' => 'action']);?>
            <?= Html::submitButton('Add to cart', ['class' => 'button button-sm button-secondary button-nina', 'data-pjax' => 'false', 'id' => 'add-cart-button']) ?>
          </li>
        </ul>
        <?php ActiveForm::end(); ?>
      </div>
    </div>
  </div>
</section>
<?php
$script = <<< JS
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
          $('#price').html(result.data.price);
          $('#unit').html(result.data.unit);
        }
      },
  });
});
JS;
$this->registerJs($script);
?>
