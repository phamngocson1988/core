<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\bootstrap\ActiveForm;
use unclead\multipleinput\MultipleInput;
use unclead\multipleinput\TabularInput;
?>
<section class="topup-page">
  <div class="container">
    <div class="small-container">
      <div class="row">
        <div class="col col-lg-12 col-md-12 col-sm-12 col-12">
          <div class="affiliate-top no-mar-bot">
            <div class="has-left-border has-shadow no-mar-top">
              Import your list
            </div>
          </div>
          
        </div>
      </div>
    </div>
  </div>
</section>

<?php $form = ActiveForm::begin(); ?>
<section class="topup-page">
  <div class="container">
    <div class="small-container">
      <div class="row">
        <div class="col col-lg-12 col-md-12 col-sm-12 col-12">
          <?= TabularInput::widget([
          'models' => $models,
          'cloneButton' => true,
          'cloneButtonOptions' => [
            
          ],
          'columns' => [
              [
                  'name'  => 'game_title',
                  'title' => 'Raw account data',
                  'type'  => \unclead\multipleinput\MultipleInputColumn::TYPE_TEXT_INPUT,
              ],
              [
                  'name'  => 'quantity',
                  'title' => 'Quantity',
                  'type'  => \unclead\multipleinput\MultipleInputColumn::TYPE_TEXT_INPUT,
              ],
          ],
          ]); ?>
        </div>
      </div>
    </div>
  </div>
</section>
<?php ActiveForm::end(); ?>
