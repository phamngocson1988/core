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
      <span>Quản lý customer tracker</span>
    </li>
  </ul>
</div>
<!-- END PAGE BAR -->
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title">Quản lý customer tracker</h1>
<!-- END PAGE TITLE-->
<div class="row">
  <div class="col-md-12">
    <!-- BEGIN EXAMPLE TABLE PORTLET-->
    <div class="portlet light bordered">
      <div class="portlet-title">
        <div class="caption font-dark">
          <i class="icon-settings font-dark"></i>
          <span class="caption-subject bold uppercase"> Quản lý customer tracker</span>
        </div>
        <div class="actions">
          <div class="btn-group btn-group-devided">
            <?php if (Yii::$app->user->can('admin')) : ?>
            <a role="button" class="btn btn-warning" href="<?=Url::current(['mode' => 'export'])?>"><i class="fa fa-file-excel-o"></i> Export</a>
            <?php endif;?>
          </div>
        </div>
      </div>
      <div class="portlet-body">
      <?php $form = ActiveForm::begin(['method' => 'GET', 'action' => ['customer-tracker/index']]);?>
        <div class="row margin-bottom-10">
            <?=$form->field($search, 'name', [
              'options' => ['class' => 'form-group col-md-4 col-lg-3'],
              'inputOptions' => ['class' => 'form-control', 'name' => 'name']
            ])->textInput()->label('Name');?>
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
            <?=$form->field($search, 'game', [
              'options' => ['class' => 'form-group col-md-4 col-lg-3'],
              'inputOptions' => ['class' => 'form-control', 'name' => 'game']
            ])->textInput()->label('Game');?>
            <?=$form->field($search, 'saler_id', [
              'options' => ['class' => 'form-group col-md-4 col-lg-3'],
            ])->widget(kartik\select2\Select2::classname(), [
              'options' => ['class' => 'form-control', 'name' => 'saler_id', 'prompt' => 'Select Account Manager'],
              'data' => $search->fetchSalers(),
              'pluginOptions' => [
                'allowClear' => true
              ],
            ])->label('Account Manager')?>
            <?=$form->field($search, 'sale_growth', [
              'options' => ['class' => 'form-group col-md-4 col-lg-3'],
              'inputOptions' => ['class' => 'form-control', 'name' => 'sale_growth']
            ])->dropDownList($search->getBooleanList(), ['prompt' => '--Select--'])->label('Sales Growth');?>
            <?=$form->field($search, 'product_growth', [
              'options' => ['class' => 'form-group col-md-4 col-lg-3'],
              'inputOptions' => ['class' => 'form-control', 'name' => 'product_growth']
            ])->dropDownList($search->getBooleanList(), ['prompt' => '--Select--'])->label('Product Growth');?>

            <?=$form->field($search, 'is_loyalty', [
              'options' => ['class' => 'form-group col-md-4 col-lg-3'],
              'inputOptions' => ['class' => 'form-control', 'name' => 'is_loyalty']
            ])->dropDownList($search->getBooleanList(), ['prompt' => '--Select--'])->label('Loyalty customer');?>

            <?=$form->field($search, 'is_dangerous', [
              'options' => ['class' => 'form-group col-md-4 col-lg-3'],
              'inputOptions' => ['class' => 'form-control', 'name' => 'is_dangerous']
            ])->dropDownList($search->getBooleanList(), ['prompt' => '--Select--'])->label('Customer "in dangerous"');?>
			

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
              <th rowspan="3" class="center">No.</th>
              <th></th>
              <th></th>
              <th rowspan="2" colspan="7" class="center">General Information</th>
              <th colspan="11" class="center">Sales Performance</th>
              <th colspan="2" class="center">Potential Customer</th>
              <th colspan="2" class="center">Key Customer</th>
              <th class="center">Loyalty customer</th>
              <th class="center">Customer in dangerous</th>
              <th rowspan="3" class="center">Actions</th>
            </tr>
            <tr>
              <th></th>
              <th></th>
              <th colspan="5" class="center">Normal Customer</th>
              <th colspan="2" class="center">Growth rate</th>
              <th class="center">Growth speed</th>
              <th colspan="3" class="center">Development</th>
              <th rowspan="2" class="center">Evaluation (1st date)</th>
              <th rowspan="2" class="center">Date (1st date)</th>
              <th rowspan="2" class="center">Evaluation (1st date)</th>
              <th rowspan="2" class="center">Date (1st date)</th>              								
              <th rowspan="2" class="center">Active 6months continously</th>              								
              <th rowspan="2" class="center">(G1, G2<0)</th>              								
            </tr>
            <tr>
              <th>Status</th>
              <th>Monthly Status Customer</th>
              <th>Name</th>
              <th>Nationality</th>
              <th>Phone</th>
              <th>Email</th>
              <th>Channel</th>
              <th>Game chủ đạo</th>
              <th>Account Manager</th>
              <th>1st order date</th>
              <th>1st month</th>
              <th>2nd month</th>
              <th>3rd month</th>
              <th>Monthly Sales Target</th>
              <th>G1</th>
              <th>G2</th>
              <th>G2 - G1</th>
              <th>Sales Growth</th>
              <th>Product Growth</th>
              <th>% Result/ KPI</th>
            </tr>
          </thead>
          <tbody>
              <?php if (!$models) : ?>
              <tr><td colspan="28"><?=Yii::t('app', 'no_data_found');?></td></tr>
              <?php endif;?>
              <?php foreach ($models as $no => $model) : ?>
              <tr>
                <td class="center"><a href='<?=Url::to(['customer-tracker/view', 'id' => $model->id]);?>'>#<?=$model->id;?></a></td>
                <td class="center">
                  <?php if ($model->is_key_customer) :?>
                  <?php if ($model->customer_tracker_status) :?>
                  <span class="label label-success">YES</span>
                  <?php else : ?>
                  <span class="label label-default">NO</span>
                  <?php endif;?>
                  <?php else : ?>
                    -
                  <?php endif;?>
                </td>
                <td class="center">
                  <?php if ($model->is_key_customer) :?>
                  Key Customer
                  <?php elseif ($model->is_potential_customer) : ?>
                  Potential Customer
                  <?php else : ?>
                  Normal Custormer
                  <?php endif;?>
                </td>
                <td><a href="<?=$model->data;?>" target="_blank"><?=$model->name;?></a></td>
                <td><?=$model->getCountryName();?></td>
                <td><?=$model->phone;?></td>
                <td><?=$model->email;?></td>
                <td><?=$model->channel;?></td>
                <td><?=$model->game;?></td>
                <td><?=$model->saler ? $model->saler->getName() : '-';?></td>

                <td><?=$model->first_order_at;?></td>
                <td><?=$model->sale_month_1;?></td>
                <td><?=$model->sale_month_2;?></td>
                <td><?=$model->sale_month_3;?></td>
                <td><?=$model->sale_target;?></td>
                <td><?=$model->growth_rate_1;?></td>
                <td><?=$model->growth_rate_2;?></td>
                <td><?=$model->growth_speed;?></td>
                <td><?=$model->sale_growth;?></td>
                <td><?=$model->product_growth;?></td>
                <td><?=$model->kpi_growth;?></td>
                <td class="center">
                  <?php if ($model->is_potential_customer) :?>
                  <span class="label label-success">YES</span>
                  <?php else : ?>
                  <span class="label label-default">NO</span>
                  <?php endif;?>
                </td>
                <td><?=$model->potential_customer_at;?></td>
                <td class="center">
                  <?php if ($model->is_key_customer) :?>
                  <span class="label label-success">YES</span>
                  <?php else : ?>
                  <span class="label label-default">NO</span>
                  <?php endif;?>
                </td>
                <td><?=$model->key_customer_at;?></td>
                <td class="center">
                  <?php if ($model->is_loyalty) :?>
                  <span class="label label-success">YES</span>
                  <?php else : ?>
                  <span class="label label-default">NO</span>
                  <?php endif;?>
                </td>
                <td class="center">
                  <?php if ($model->is_dangerous) :?>
                  <span class="label label-success">YES</span>
                  <?php else : ?>
                  <span class="label label-default">NO</span>
                  <?php endif;?>
                </td>
                <td class="center">
                  <a href="<?=Url::to(['customer-tracker/edit', 'id' => $model->id]);?>" class="btn btn-sm green btn-outline filter-submit margin-bottom">Edit</a>
                  <a href="<?=Url::to(['customer-tracker/calculate', 'id' => $model->id]);?>" class="btn btn-sm blue btn-outline filter-submit margin-bottom ajax-link">Calculate</a>
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

$(".ajax-link").ajax_action({
  method: 'GET',
  callback: function(eletement, data) {
    location.reload();
  },
  error: function(element, errors) {
    console.log(errors);
    alert(errors);
  }
});
JS;
$this->registerJs($script);
?>