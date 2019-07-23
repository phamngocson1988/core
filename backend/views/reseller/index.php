<?php
use yii\widgets\LinkPager;
use yii\widgets\Pjax;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
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
      <span>Nhân viên bán lẻ</span>
    </li>
  </ul>
</div>
<!-- END PAGE BAR -->
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title">Nhân viên bán lẻ</h1>
<!-- END PAGE TITLE-->
<div class="row">
  <div class="col-md-12">
    <!-- BEGIN EXAMPLE TABLE PORTLET-->
    <div class="portlet light bordered">
      <div class="portlet-title">
        <div class="caption font-dark">
          <i class="icon-settings font-dark"></i>
          <span class="caption-subject bold uppercase"> Nhân viên bán lẻ</span>
        </div>
        <div class="actions">
          <div class="btn-group btn-group-devided">
            <a class="btn green" href="#new" data-toggle="modal"><?=Yii::t('app', 'add_new')?></a>
          </div>
        </div>
      </div>
      <div class="portlet-body">
        <?php Pjax::begin(); ?>
        <table class="table table-striped table-bordered table-hover table-checkable">
          <thead>
            <tr>
              <th style="width: 5%;"> STT </th>
              <th style="width: 15%;"> Tên </th>
              <th style="width: 15%;"> Tên đăng nhập </th>
              <th style="width: 20%;"> Email </th>
              <th style="width: 15%;"> Phone </th>
              <th style="width: 15%;">  </th>
              <th style="width: 15%;" class="dt-center"> {Yii::t('app', 'actions')} </th>
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
                <td style="vertical-align: middle;"></td>
                <td style="vertical-align: middle;">
                  <a href='<?=Url::to(['reseller/price', 'id' => $model->id]);?>' class="btn btn-xs grey-salsa tooltips" data-pjax="0" data-container="body" data-original-title="Giá game"><i class="fa fa-dollar"></i></a>
                  <a href='<?=Url::to(['reseller/delete', 'id' => $model->id]);?>' class="btn btn-xs grey-salsa tooltips delete" data-pjax="0" data-container="body" data-original-title="Gỡ bỏ nhà bán lẻ"><i class="fa fa-trash"></i></a>
                </td>
              </tr>
              <?php endforeach;?>
          </tbody>
        </table>
        <?=LinkPager::widget(['pagination' => $pages])?>
        <?php Pjax::end(); ?>
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
  confirm_text: 'Bạn có muốn gỡ tính năng nhà bán lẻ của người dùng này không?',
  callback: function(data) {
    location.reload();
  },
});
JS;
$this->registerJs($script);
?>