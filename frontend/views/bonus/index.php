<?php
use yii\helpers\Url;
use frontend\widgets\LinkPager;
use yii\bootstrap\ActiveForm;
?>
<main>
  <section class="section-module">
    <div class="container">
      <div class="heading-group">
        <h1 class="sec-title">LATEST BONUSES</h1>
      </div>
      <div class="sec-content">
        <div class="mod-column">
          <?php $form = ActiveForm::begin(['method' => 'get', 'id' => 'bonusTypeSearchForm', 'action' => Url::to(['bonus/index'])]); ?>
          <div class="widget-box bonus-total">
            <?= $form->field($search, 'bonus_type', [
              'options' => ['tag' => false],
              'template' => '{input}',
              'inputOptions' => ['name' => 'bonus_type', 'class' => 'form-control']
            ])->dropdownList($search->fetchType(), ['prompt' => 'Select bonus type'])->label(false);?>
            <div class="total-text text-right">TOTAL <?=number_format($total);?> ACTIVE BONUSES</div>
          </div>
          
          <?php ActiveForm::end();?>
          <div class="row">
            <?php foreach ($bonuses as $bonus) :?>
            <div class="col-12 col-sm-6 col-md-6 col-lg-4 col-xl-3">
              <div class="block-bonuses js-bonuses">
                <div class="bonuses-front">
                  <div class="bonuses-icon fas fa-exclamation-circle js-exclamation"></div>
                  <div class="bonuses-image"><img class="object-fit" src="<?=$bonus->getImageUrl('400x220');?>" alt="image"></div>
                  <div class="bonuses-body">
                    <h3 class="bonuses-title"><?=$bonus->title;?></h3>
                    <p class="bonuses-desc"><?=$bonus->getType();?></p>
                  </div><a class="btn btn-primary" href="<?=Url::to(['bonus/view', 'id' => $bonus->id]);?>">GET BONUS</a>
                </div>
                <div class="bonuses-back">
                  <div class="bonuses-icon fas fa-close js-close"></div>
                  <div class="bonuses-body">
                    <h3 class="bonuses-title"><?=$bonus->title;?></h3>
                    <p class="bonuses-desc">
                      Type: <?=$bonus->getType();?><br>
                      Bonus Value: $150<br>
                      Minimum Deposit: <?=$bonus->minimum_deposit;?><br>
                      Wagering Requirement: <?=$bonus->wagering_requirement;?>
                    </p>
                  </div><a class="btn btn-primary" href="<?=Url::to(['bonus/view', 'id' => $bonus->id]);?>">GET BONUS</a>
                </div>
              </div>
            </div>
            <?php endforeach;?>
          </div>
          <div class="pagination-wrap">
            <?=LinkPager::widget([
              'pagination' => $pages, 
              'maxButtonCount' => 1, 
              'hideOnSinglePage' => false,
              'linkOptions' => ['class' => 'page-link'],
              'pageCssClass' => 'page-item',
            ]);?>
          </div>
        </div>
        <aside class="mod-sidebar">
          <div class="sidebar-col">
            <div class="sidebar-category">
              <p class="category-title">FILTER BONUS</p>
              <?php $form = ActiveForm::begin(['method' => 'get', 'id' => 'commonSearchForm', 'action' => Url::to(['bonus/index'])]); ?>
              <?= $form->field($search, 'bonus_type', [
                'options' => ['tag' => false],
                'template' => '{input}',
                'inputOptions' => ['name' => 'bonus_type', 'class' => 'form-control']
              ])->hiddenInput()->label(false);?>
              <div class="category-inner">
                <ul class="category-list list-dropdown">
                  <li><a class="js-btn-dropdown" href="javascript:void(0)">W/R</a>
                    <div class="js-dropdown">
                      <?= $form->field($search, 'wagering_requirement', [
                        'options' => ['class' => 'category-text'],
                        'template' => '{input}',
                        'inputOptions' => ['name' => 'wagering_requirement', 'class' => 'form-control']
                      ])->textInput()->label(false);?>
                    </div>
                  </li>
                  <li><a class="js-btn-dropdown" href="javascript:void(0)">MIN DEPOSIT</a>
                    <div class="js-dropdown">
                      <?= $form->field($search, 'minimum_deposit_value', [
                        'options' => ['class' => 'category-text'],
                        'template' => '{input}',
                        'inputOptions' => ['name' => 'minimum_deposit_value', 'class' => 'form-control']
                      ])->textInput()->label(false);?>
                    </div>
                  </li>
                </ul>
              </div>
              <?php ActiveForm::end();?>
            </div>
          </div>
          <div class="sidebar-col">
            <?=\frontend\widgets\TopOperatorWidget::widget();?>
          </div>
        </aside>
      </div>
    </div>
  </section>
</main>
<?php 
$script = <<< JS
$("[name=bonus_type]").on('change', function(){
  $(this).closest('form').submit();
});
$("#commonSearchForm input").on('blur', function() {
  $(this).closest('form').submit();
})
JS;
$this->registerJs($script);
?>
