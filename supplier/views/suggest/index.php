<?php
use yii\helpers\Url;
use yii\widgets\LinkPager;

?>

<!-- BEGIN PAGE BAR -->
<div class="page-bar">
  <ul class="page-breadcrumb">
    <li>
      <a href="/"><?=Yii::t('app', 'home')?></a>
      <i class="fa fa-circle"></i>
    </li>
    <li>
      <a href="<?=Url::to(['game/index']);?>">Danh sách game</a>
      <i class="fa fa-circle"></i>
    </li>
    <li>
      <span>Yêu cầu game mới</span>
    </li>
  </ul>
</div>
<!-- END PAGE BAR -->
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title">Yêu cầu game mới</h1>
<!-- END PAGE TITLE-->
<div class="row">
  <div class="col-md-12">
    <!-- BEGIN EXAMPLE TABLE PORTLET-->
    <div class="portlet light bordered">
      <div class="portlet-title">
        <div class="caption font-dark">
          <i class="icon-settings font-dark"></i>
          <span class="caption-subject bold uppercase"> Yêu cầu game mới</span>
        </div>
        <div class="actions">
          <div class="btn-group btn-group-devided">
            <a class="btn green" href="<?=Url::to(['suggest/create']);?>"><?=Yii::t('app', 'add_new');?></a>
          </div>
        </div>
      </div>
      <div class="portlet-body">
        <div class="table-responsive">
          <table class="table table-striped table-bordered table-hover table-checkable">
            <thead>
              <tr>
                <th>Mã yêu cầu</th>
                <th>Hình ảnh</th>
                <th>Tên game</th>
                <th>Ngày tạo</th>
                <th>Trạng thái</th>
                <th>Tác vụ</th>
              </tr>
            </thead>
            <tbody>
              <?php if (!$models) :?>
              <tr><td colspan="6">No data found</td></tr>
              <?php endif;?>
              <?php foreach ($models as $model) :?>
              <tr>
                <td><?=$model->id;?></td>
                <td><img src="<?=$model->getImageUrl('50x50');?>" width="50px;" /></td>
                <td><?=$model->title;?></td>
                <td><?=$model->getCreatedAt();?></td>
                <td><?=$model->getStatusLabel();?></td>
                <td>
                  <a href="<?=Url::to(['suggest/delete', 'id' => $model->id]);?>" class="btn btn-sm red delete tooltips" data-container="body" data-original-title="Xóa yêu cầu"><i class="fa fa-times"></i></a>
                  <a href="<?=Url::to(['suggest/edit', 'id' => $model->id]);?>" class="btn btn-sm purple tooltips" data-container="body" data-original-title="Chỉnh sửa"><i class="fa fa-pencil"></i></a>
                </td>
              </tr>
              <?php endforeach;?>
            </tbody>
          </table>
        </div>
        <?=LinkPager::widget(['pagination' => $pages])?>
      </div>
    </div>
    <!-- END EXAMPLE TABLE PORTLET-->
  </div>
</div>
<?php
$script = <<< JS
$(".delete").ajax_action({
  confirm: true,
  confirm_text: 'Bạn có chắc muốn thực hiện xóa yêu cầu này?',
  callback: function(eletement, data) {
    location.reload();
  }
});
JS;
$this->registerJs($script);
?>