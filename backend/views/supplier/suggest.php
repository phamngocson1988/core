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
      <a href="<?=Url::to(['supplier/index']);?>">Nhà cung cấp</a>
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
                  <a href="#des<?=$model->id;?>" class="btn btn-sm yellow tooltips" data-container="body" data-original-title="Xem yêu cầu" data-toggle="modal"><i class="fa fa-eye"></i></a>
                  <a href="<?=Url::to(['supplier/delete-suggest', 'id' => $model->id]);?>" class="btn btn-sm red delete tooltips" data-container="body" data-original-title="Xóa yêu cầu"><i class="fa fa-times"></i></a>

                  <div class="modal fade" id="des<?=$model->id;?>" tabindex="-1" role="basic" aria-hidden="true">
                    <div class="modal-dialog">
                      <div class="modal-content">
                        <div class="modal-header">
                          <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                          <h4 class="modal-title">Mô tả</h4>
                        </div>
                        <div class="modal-body"> 
                          <div class="row">
                            <div class="col-md-12">
                              <?=$model->description;?>
                            </div>
                          </div>
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn dark btn-outline" data-dismiss="modal">Close</button>
                        </div>
                      </div>
                    </div>
                  </div>
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