<?php
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

$this->registerCssFile('@web/vendor/assets/global/plugins/datatables/datatables.min.css', ['depends' => [\backend\assets\AppAsset::className()]]);
$this->registerCssFile('@web/vendor/assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.css', ['depends' => [\backend\assets\AppAsset::className()]]);
$this->registerJsFile('@web/vendor/assets/global/plugins/datatables/datatables.min.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile('@web/vendor/assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
?>
<!-- BEGIN PAGE BAR -->
<div class="page-bar">
  <ul class="page-breadcrumb">
    <li>
      <a href="/"><?=Yii::t('app', 'home');?></a>
      <i class="fa fa-circle"></i>
    </li>
    <li>
      <span>Quản lý lead tracker</span>
    </li>
  </ul>
</div>
<!-- END PAGE BAR -->
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title">Quản lý lead tracker</h1>
<!-- END PAGE TITLE-->
<div class="row">
  <div class="col-md-12">
    <!-- BEGIN EXAMPLE TABLE PORTLET-->
    <div class="portlet light bordered">
      <div class="portlet-title">
        <div class="caption font-dark">
          <i class="icon-settings font-dark"></i>
          <span class="caption-subject bold uppercase"> Quản lý lead tracker</span>
        </div>
        <div class="actions">
          <div class="btn-group btn-group-devided">
            <a class="btn green" href="<?=Url::to(['lead-tracker/create']);?>"><?=Yii::t('app', 'add_new');?></a>
            <?php if (Yii::$app->user->can('admin')) : ?>
            <a role="button" class="btn btn-warning" href="<?=Url::current(['mode' => 'export'])?>"><i class="fa fa-file-excel-o"></i> Export</a>
            <?php endif;?>
          </div>
        </div>
      </div>
      <div class="portlet-body">
      <?php $form = ActiveForm::begin(['method' => 'GET', 'action' => ['lead-tracker/index']]);?>
        <div class="row margin-bottom-10">
            <?=$form->field($search, 'id', [
              'options' => ['class' => 'form-group col-md-4 col-lg-3'],
              'inputOptions' => ['class' => 'form-control', 'name' => 'id']
            ])->textInput()->label('Index');?>
            <?php if (Yii::$app->user->cans(['admin', 'sale_manager'])):?>
            <?=$form->field($search, 'saler_id', [
              'options' => ['class' => 'form-group col-md-4 col-lg-3'],
            ])->widget(kartik\select2\Select2::classname(), [
              'options' => ['class' => 'form-control', 'name' => 'saler_id', 'prompt' => 'Select Account Manager'],
              'data' => $search->fetchSalers(),
              'pluginOptions' => [
                'allowClear' => true
              ],
            ])->label('Account Manager')?>
            <?php endif;?>
            <?=$form->field($search, 'country_code', [
              'options' => ['class' => 'form-group col-md-4 col-lg-3'],
            ])->widget(kartik\select2\Select2::classname(), [
              'options' => ['class' => 'form-control', 'name' => 'country_code', 'prompt' => 'Select Country'],
              'data' => $search->listCountries(),
              'pluginOptions' => [
                'allowClear' => true
              ],
            ])->label('Nationality')?>
            <?=$form->field($search, 'phone', [
              'options' => ['class' => 'form-group col-md-4 col-lg-3'],
              'inputOptions' => ['class' => 'form-control', 'name' => 'phone']
            ])->textInput()->label('Phone');?>
            <?=$form->field($search, 'email', [
              'options' => ['class' => 'form-group col-md-4 col-lg-3'],
              'inputOptions' => ['class' => 'form-control', 'name' => 'email']
            ])->textInput()->label('Email');?>
            <?=$form->field($search, 'game_id', [
              'options' => ['class' => 'form-group col-md-4 col-lg-3'],
              'inputOptions' => ['class' => 'form-control', 'name' => 'is_target']
            ])->dropDownList($search->fetchGames(), ['prompt' => '--Select--'])->label('Game');?>
            <?=$form->field($search, 'is_potential', [
              'options' => ['class' => 'form-group col-md-4 col-lg-3'],
              'inputOptions' => ['class' => 'form-control', 'name' => 'is_potential']
            ])->dropDownList($search->getBooleanList(), ['prompt' => '--Select--'])->label('Potential Lead');?>
            <?=$form->field($search, 'is_target', [
              'options' => ['class' => 'form-group col-md-4 col-lg-3'],
              'inputOptions' => ['class' => 'form-control', 'name' => 'is_target']
            ])->dropDownList($search->getBooleanList(), ['prompt' => '--Select--'])->label('Target Lead');?>

            <div class="form-group col-md-4 col-lg-3">
              <button type="submit" class="btn btn-success table-group-action-submit" style="margin-top: 25px;">
                <i class="fa fa-check"></i> <?=Yii::t('app', 'search')?>
              </button>
            </div>
        </div>
        <?php ActiveForm::end()?>
        <table class="table table-striped table-bordered table-hover table-checkable" id="myTable">
          <thead style="font-weight: bold; color: white; background-color: #36c5d3">
            <tr>
              <td rowspan="2" class="center">No.</td>
              <th colspan="3" class="center">General Information</th>
              <th colspan="5" class="center">Personal Information</th>
              <th rowspan="2" class="center">Potential Lead</th>
              <th rowspan="2" class="center">Targeted Lead</th>
              <th rowspan="2" class="center">Convert to Customer</th>
            </tr>
            <tr>
              <th>Index</th>
              <th>Lead Name & Link Account</th>
              <th>Account Manger</th>
              <th>Nationality</th>
              <th>Phone</th>
              <th>Email</th>
              <th>Channel</th>
              <th>Game</th>
            </tr>
          </thead>
          <tbody>
              <?php if (!$models) : ?>
              <tr><td colspan="12"><?=Yii::t('app', 'no_data_found');?></td></tr>
              <?php endif;?>
              <?php foreach ($models as $no => $model) : ?>
              <tr>
                <td class="center"><?=$no + 1;?></td>
                <td class="center"><a href='<?=Url::to(['lead-tracker/edit', 'id' => $model->id]);?>'>#<?=$model->id;?></a></td>
                <td><a href="<?=$model->link;?>" target="_blank"><?=$model->name;?></a></td>
                <td><?=$model->saler ? $model->saler->getName() : '-';?></td>
                <td><?=$model->getCountryName();?></td>
                <td><?=$model->phone;?></td>
                <td><?=$model->email;?></td>
                <td><?=$model->channel;?></td>
                <td><?=$model->game ? $model->game->title : '-';?></td>
                <td class="center">
                  <?php if ($model->is_potential) :?>
                  <span class="label label-success">YES</span>
                  <?php else : ?>
                  <span class="label label-default">NO</span>
                  <?php endif;?>
                </td>
                <td class="center">
                  <?php if ($model->is_target) :?>
                  <span class="label label-success">YES</span>
                  <?php else : ?>
                  <span class="label label-default">NO</span>
                  <?php endif;?>
                </td>
                <td class="center">
                  <?php if ($model->email) : ?>
                  <a href="<?=Url::to(['lead-tracker/convert', 'id' => $model->id]);?>" class="btn btn-sm green btn-outline filter-submit margin-bottom">Convert</a>
                  <?php else : ?>
                  <a href="#" class="btn btn-sm grey btn-outline filter-submit margin-bottom">Need email to convert</a>
                  <?php endif;?>
                  <a href='<?=Url::to(['lead-tracker/add-comment', 'id' => $model->id]);?>' data-target="#add-comment" class="btn btn-xs grey-salsa tooltips" data-pjax="0" data-container="body" data-original-title="Thêm ghi chú" data-toggle="modal" ><i class="fa fa-comment"></i></a>
                  <a href="<?=Url::to(['lead-tracker/delete', 'id' => $model->id]);?>" class="btn btn-sm red btn-outline delete margin-bottom">Delete</a>
                </td>
              </tr>
              <?php endforeach;?>
          </tbody>
        </table>
      </div>
    </div>
    <!-- END EXAMPLE TABLE PORTLET-->
  </div>
</div>
<div class="modal fade" id="add-comment" tabindex="-1" role="basic" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<?php
$script = <<< JS
$('#myTable').DataTable();

  // comment
$(document).on('submit', 'body #add-comment-form', function(e) {
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
      console.log(result);
      $('#add-comment').modal('hide');
    },
  });
  return false;
});

$(".delete").ajax_action({
  confirm: true,
  confirm_text: 'Bạn có muốn xoá lead tracker này không?',
  callback: function(eletement, data) {
    location.reload();
  }
});
JS;
$this->registerJs($script);
?>