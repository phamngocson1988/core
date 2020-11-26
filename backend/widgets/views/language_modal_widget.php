<?php
use yii\helpers\Html;
?>
<div class="modal fade" id="<?=$modalId;?>" tabindex="-1" role="basic" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
        <h4 class="modal-title">Choose language</h4>
      </div>
      <?= Html::beginForm($url, 'GET'); ?>
      <div class="modal-body"> 
        <div class="row">
          <div class="col-md-12">
            <?= kartik\select2\Select2::widget([
              'name' => 'language',
              'data' => $languages,
            ]); ?>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-default" data-toggle="modal"><i class="fa fa-send"></i> Choose</button>
        <button type="button" class="btn dark btn-outline" data-dismiss="modal">Close</button>
      </div>
      <?= Html::endForm(); ?>
    </div>
  </div>
</div>