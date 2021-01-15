<?php 
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
?>
<?php $form = ActiveForm::begin(['action' => Url::to(['order/survey', 'id' => $model->id]), 'id' => 'survey-form']);?>
<?=$form->field($model, 'rating', [
  'options' => ['tag' => false],          
  'template' => '{input}',
])->hiddenInput()->label(false) ?>
<div class="modal-header">
  <h5 class="modal-title">RATE ORDER</h5>
  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
    <span aria-hidden="true">&times;</span>
  </button>
</div>
<div class="modal-body">
  <p class="text-center">Thank you for using our services !! Please advise what we need to improve your experience : <small>(you can choose multiple options)</small>
  </p>
  <?= $form->field($model, 'comment_rating', [
    'options' => ['tag' => false]
  ])->widget(\frontend\widgets\RatingCheckListInput::className(), [
    'items' => $model->fetchCommentRating(),
    'options' => ['tag' => false]
  ])->label(false);?>
  <?=$form->field($model, 'other')->textInput(['placeholder' => 'Others'])->label('Others');?>
</div>
<div class="modal-footer">
  <button type="button" id="rating-order-button" class="btn btn-red">Submit</button>
</div>
<?php ActiveForm::end(); ?>
