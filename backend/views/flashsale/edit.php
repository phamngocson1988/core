<?php
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use backend\components\datetimepicker\DateTimePicker;
?>
<!-- BEGIN PAGE BAR -->
<div class="page-bar">
  <ul class="page-breadcrumb">
    <li>
      <a href="/"><?=Yii::t('app', 'home')?></a>
      <i class="fa fa-circle"></i>
    </li>
    <li>
      <a href="<?=Url::to(['flashsale/index'])?>">Flashsale</a>
      <i class="fa fa-circle"></i>
    </li>
    <li>
      <span>Chỉnh sửa chương trình khuyến mãi</span>
    </li>
  </ul>
</div>
<!-- END PAGE BAR -->
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title">Chỉnh sửa chương trình khuyến mãi</h1>
<!-- END PAGE TITLE-->
<div class="row">
  <div class="col-md-12">
    <?php $form = ActiveForm::begin(['options' => ['class' => 'form-horizontal form-row-seperated']]);?>
      <div class="portlet">
        <div class="portlet-title">
          <div class="actions btn-set">
            <button type="submit" class="btn btn-success">
            <i class="fa fa-check"></i> <?=Yii::t('app', 'save')?>
            </button>

            <a href='<?=Url::to(['flashsale/add-game', 'id' => $model->id]);?>' data-target="#add-game-modal" class="btn btn-xs grey-salsa tooltips" data-pjax="0" data-container="body" data-original-title="Thêm game" data-toggle="modal" ><i class="fa fa-plus"></i> Thêm game</a>
          </div>
        </div>
        <div class="portlet-body">
          <div class="tabbable-bordered">
            <ul class="nav nav-tabs">
              <li class="active">
                <a href="#tab_general" data-toggle="tab"> <?=Yii::t('app', 'main_content')?></a>
              </li>
            </ul>
            <div class="tab-content">
              <div class="tab-pane active" id="tab_general">
                <div class="form-body">
                  <?=$form->field($model, 'title', [
                    'labelOptions' => ['class' => 'col-md-2 control-label'],
                    'inputOptions' => ['class' => 'slug form-control'],
                    'template' => '{label}<div class="col-md-6">{input}{hint}{error}</div>'
                  ])->textInput();?>

                  <?= $form->field($model, 'start_from', [
                    'labelOptions' => ['class' => 'col-md-2 control-label'],
                    'inputOptions' => ['class' => 'slug form-control'],
                    'template' => '{label}<div class="col-md-6">{input}{hint}{error}</div>'
                  ])->widget(DateTimePicker::className(), [
                    'clientOptions' => [
                      'autoclose' => true,
                      'format' => 'yyyy-mm-dd hh:00',
                      'minuteStep' => 1,
                      'minView' => '1'
                    ],
                  ])->label('Từ ngày');?>

                  <?=$form->field($model, 'start_to', [
                    'labelOptions' => ['class' => 'col-md-2 control-label'],
                    'inputOptions' => ['class' => 'slug form-control'],
                    'template' => '{label}<div class="col-md-6">{input}{hint}{error}</div>'
                  ])->widget(DateTimePicker::className(), [
                      'clientOptions' => [
                        'autoclose' => true,
                        'format' => 'yyyy-mm-dd hh:59',
                        'minuteStep' => 1,
                        'minView' => '1'
                      ],
                  ])->label('Đến ngày');?>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <?php ActiveForm::end()?>
  </div>
</div>
<div class="row">
  <div class="col-md-12">
    <div class="portlet">
      <div class="portlet-body">
        <div class="tabbable-bordered">
          <ul class="nav nav-tabs">
            <li class="active">
              <a href="#tab_game" data-toggle="tab"> Các game</a>
            </li>
          </ul>
          <div class="tab-content">
            <div class="tab-pane active" id="tab_game">
              <div class="form-body">
                <table class="table table-striped table-bordered table-hover table-checkable">
                  <thead>
                    <tr>
                      <th> ID </th>
                      <th> Tên game </th>
                      <th> Số tiền </th>
                      <th> Số lượng </th>
                      <th> Trạng thái </th>
                      <th> Còn lại </th>
                      <th>  </th>
                    </tr>
                  </thead>
                  <tbody>
                      <?php $games = $model->getFlashSale()->games;?>
                      <?php if (!count($games)) : ?>
                      <tr><td colspan="7"><?=Yii::t('app', 'no_data_found');?></td></tr>
                      <?php endif; ?>
                      <?php foreach ($games as $saleGame) :?>
                      <?php $game = $saleGame->game;?>
                      <tr>
                        <td><?=$game->id;?></td>
                        <td><?=$game->title;?></td>
                        <td><?=$saleGame->price;?></td>
                        <td><?=$saleGame->limit;?></td>
                        <td><?=$game->isVisible() ? 'Đang hiển thị' : 'Đã ẩn';?></td>
                        <td><?=$saleGame->remain;?></td>
                        <td>
                          <a href='<?=Url::to(['flashsale/edit-game', 'id' => $saleGame->id]);?>' data-target="#edit-game-modal" class="btn btn-xs grey-salsa tooltips" data-pjax="0" data-container="body" data-original-title="Thêm game" data-toggle="modal" ><i class="fa fa-pencil"></i></a>
                        </td>
                      </tr>
                      <?php endforeach;?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="add-game-modal" tabindex="-1" role="basic" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>

<div class="modal fade" id="edit-game-modal" tabindex="-1" role="basic" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>

<?php
$script = <<< JS
$(document).on('submit', 'body #add-game-form', function(e) {
  e.preventDefault();
  e.stopImmediatePropagation();
  var form = $(this);
  form.unbind('submit');
  $.ajax({
    url: form.attr('action'),
    type: form.attr('method'),
    dataType : 'json',
    data: form.serialize(),
    success: function (result, textStatus, jqXHR) {
      if (!result.status)
       alert(result.error);
      else 
        location.reload();
    },
  });
  return false;
});

$(document).on('submit', 'body #edit-game-form', function(e) {
  e.preventDefault();
  e.stopImmediatePropagation();
  var form = $(this);
  form.unbind('submit');
  $.ajax({
    url: form.attr('action'),
    type: form.attr('method'),
    dataType : 'json',
    data: form.serialize(),
    success: function (result, textStatus, jqXHR) {
      if (!result.status)
       alert(result.error);
      else 
        location.reload();
    },
  });
  return false;
});
JS;
$this->registerJs($script);
?>