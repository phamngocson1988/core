<?php
use yii\widgets\LinkPager;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use backend\models\User;
?>

<style>
.hide-text {
    white-space: nowrap;
    width: 100%;
    max-width: 500px;
    text-overflow: ellipsis;
    overflow: hidden;
}
</style>
<!-- BEGIN PAGE BAR -->
<div class="page-bar">
  <ul class="page-breadcrumb">
    <li>
      <a href="/"><?=Yii::t('app', 'home')?></a>
      <i class="fa fa-circle"></i>
    </li>
    <li>
      <span>Reseller</span>
    </li>
  </ul>
</div>
<!-- END PAGE BAR -->
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title">Reseller</h1>
<!-- END PAGE TITLE-->
<div class="row">
  <div class="col-md-12">
    <!-- BEGIN EXAMPLE TABLE PORTLET-->
    <div class="portlet light bordered">
      <div class="portlet-title">
        <div class="caption font-dark">
          <i class="icon-settings font-dark"></i>
          <span class="caption-subject bold uppercase"> Reseller</span>
        </div>
        <div class="actions">
          <div class="btn-group btn-group-devided">
            <a class="btn green" href="<?=Url::to(['reseller/create']);?>" data-toggle="modal"><?=Yii::t('app', 'add_new')?></a>
          </div>
        </div>
      </div>
      <div class="portlet-body">
        <table class="table table-striped table-bordered table-hover table-checkable">
          <thead>
            <tr>
              <th> STT </th>
              <th> Tên </th>
              <th> Tên đăng nhập </th>
              <th> Email </th>
              <th> Phone </th>
              <th> Level </th>
              <th class="dt-center"> Tác vụ </th>
            </tr>
          </thead>
          <tbody>
              <?php if (!$models) :?>
              <tr><td colspan="7"><?=Yii::t('app', 'no_data_found');?></td></tr>
              <?php endif;?>
              <?php foreach ($models as $key => $model) :?>
              <tr>
                <td><?=$key + $pages->offset + 1;?></td>
                <td style="vertical-align: middle;"><?=$model->name;?></td>
                <td style="vertical-align: middle;"><?=$model->username;?></td>
                <td style="vertical-align: middle;"><?=$model->email;?></td>
                <td style="vertical-align: middle;"><?=$model->phone;?></td>
                <td style="vertical-align: middle;"><?=$model->getResellerLabel();?></td>
                <td style="vertical-align: middle;">
                  <a href="<?=Url::to(['reseller/delete', 'id' => $model->id]);?>" class="btn btn-sm purple link-action tooltips action-link" data-container="body" data-original-title="Bỏ tư cách nhà bán lẻ"><i class="fa fa-times"></i> Remove </a>
                  <?php if ($model->reseller_level != User::RESELLER_LEVEL_3) : ?>
                  <a href="<?=Url::to(['reseller/upgrade', 'id' => $model->id]);?>" class="btn btn-sm red link-action tooltips action-link" data-container="body" data-original-title="Nâng cấp nhà bán lẻ này"><i class="fa fa-arrow-up"></i> Nâng cấp </a>
                  <?php endif; ?>
                  <?php if ($model->reseller_level != User::RESELLER_LEVEL_1) : ?>
                  <a href="<?=Url::to(['reseller/downgrade', 'id' => $model->id]);?>" class="btn btn-sm default link-action tooltips action-link" data-container="body" data-original-title="Giảm cấp nhà bán lẻ này"><i class="fa fa-arrow-down"></i> Giảm cấp </a>
                  <?php endif; ?>
                </td>
              </tr>
              <?php endforeach;?>
          </tbody>
        </table>
        <?=LinkPager::widget(['pagination' => $pages])?>
      </div>
    </div>
    <!-- END EXAMPLE TABLE PORTLET-->
  </div>
</div>
<?php
$script = <<< JS

// delete
$('.action-link').ajax_action({
  method: 'POST',
  confirm: true,
  confirm_text: 'Bạn có muốn thực hiện hành động này?',
  callback: function(data) {
    location.reload();
  },
});
JS;
$this->registerJs($script);
?>