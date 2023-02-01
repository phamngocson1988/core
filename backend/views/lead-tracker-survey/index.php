<?php
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use common\components\helpers\FormatConverter;

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
      <span>Quản lý lead tracker survey</span>
    </li>
  </ul>
</div>
<!-- END PAGE BAR -->
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title">Quản lý lead tracker survey</h1>
<!-- END PAGE TITLE-->
<div class="row">
  <div class="col-md-12">
    <!-- BEGIN EXAMPLE TABLE PORTLET-->
    <div class="portlet light bordered">
      <div class="portlet-title">
        <div class="caption font-dark">
          <i class="icon-settings font-dark"></i>
          <span class="caption-subject bold uppercase"> Quản lý lead tracker survey</span>
        </div>
        <div class="actions">
          <div class="btn-group btn-group-devided">
            <a class="btn green" href="<?=Url::to(['lead-tracker-survey/create']);?>"><?=Yii::t('app', 'add_new');?></a>
          </div>
        </div>
      </div>
      <div class="portlet-body">
        <table class="table table-striped table-bordered table-hover table-checkable" id="myTable">
          <thead style="font-weight: bold; color: white; background-color: #36c5d3">
            <tr>
              <td class="center">ID</td>
              <th class="center">Type</th>
              <th class="center">Question</th>
              <th class="center">Point for YES</th>
              <th class="center">Point for NO</th>
              <th class="center">Date</th>
            </tr>
          </thead>
          <tbody>
              <?php if (!$models) : ?>
              <tr><td colspan="6"><?=Yii::t('app', 'no_data_found');?></td></tr>
              <?php endif;?>
              <?php foreach ($models as $no => $model) : ?>
              <tr>
                <td class="center"><a href='<?=Url::to(['lead-tracker-survey/edit', 'id' => $model->id]);?>'>#<?=$model->id;?></a></td>
                <td class="center"><?=$model->getTypeLabel();?></td>
                <td><?=$model->question;?></td>
                <td class="center"><?=$model->point_yes;?></td>
                <td class="center"><?=$model->point_no;?></td>
                <td class="center"><?=FormatConverter::convertToDate(strtotime($model->updated_at), 'd-m-Y H:i');?></td>
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
$('#myTable').DataTable();
JS;
$this->registerJs($script);
?>