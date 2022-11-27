<?php
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
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
          </div>
        </div>
      </div>
      <div class="portlet-body">
        <table class="table table-striped table-bordered table-hover table-checkable">
          <thead>
            <tr>
              <td rowspan="2">No.</td>
              <th colspan="3">General Information</th>
              <th colspan="5">Personal Information</th>
              <th rowspan="2">Potential Lead</th>
              <th rowspan="2">Targeted Lead</th>
              <th rowspan="2">Convert to Customer</th>
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
                <td><?=$no + 1;?></td>
                <td><a href='<?=Url::to(['lead-tracker/edit', 'id' => $model->id]);?>'>#<?=$model->id;?></a></td>
                <td><?=$model->name;?></td>
                <td><?=$model->saler_id;?></td>
                <td><?=$model->country_code;?></td>
                <td><?=$model->phone;?></td>
                <td><?=$model->email;?></td>
                <td><?=$model->channel;?></td>
                <td><?=$model->game;?></td>
                <td><?=$model->is_potential;?></td>
                <td><?=$model->is_target;?></td>
                <td>Convert</td>
              </tr>
              <?php endforeach;?>
          </tbody>
        </table>
      </div>
    </div>
    <!-- END EXAMPLE TABLE PORTLET-->
  </div>
</div>
<?php
$script = <<< JS
// delete
$('.delete').ajax_action({
  method: 'DELETE',
  confirm: true,
  confirm_text: 'Bạn có muốn xóa danh mục game này không?',
  callback: function(el, data) {
    location.reload();
    setTimeout(() => {  
        location.reload();
    }, 2000);
    toastr.success(data.message); 
  },
});
JS;
$this->registerJs($script);
?>