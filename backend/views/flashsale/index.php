<?php
use yii\helpers\Url;
use yii\widgets\ActiveForm;
?>
<!-- BEGIN PAGE BAR -->
<div class="page-bar">
  <ul class="page-breadcrumb">
    <li>
      <a href="/"><?=Yii::t('app', 'home')?></a>
      <i class="fa fa-circle"></i>
    </li>
    <li>
      <span>Flash Sale</span>
    </li>
  </ul>
</div>
<!-- END PAGE BAR -->
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title">Flash Sale</h1>
<!-- END PAGE TITLE-->
<div class="row">
  <div class="col-md-12">
    <!-- BEGIN EXAMPLE TABLE PORTLET-->
    <div class="portlet light bordered">
      <div class="portlet-title">
        <div class="caption font-dark">
          <i class="icon-settings font-dark"></i>
          <span class="caption-subject bold uppercase"> Flash Sale</span>
        </div>
        <div class="actions">
          <div class="btn-group btn-group-devided">
            <a class="btn green" href="<?=Url::to(['flashsale/create']);?>"><?=Yii::t('app', 'add_new')?></a>
          </div>
        </div>
      </div>
      <div class="portlet-body">
        <table class="table table-striped table-bordered table-hover table-checkable">
          <thead>
            <tr>
              <th> ID </th>
              <th> Tiêu đề </th>
              <th> Thời gian </th>
              <th> </th>
            </tr>
          </thead>
          <tbody>
              <?php if (!$models) :?>
              <tr><td colspan="4"><?=Yii::t('app', 'no_data_found');?></td></tr>
              <?php endif;?>
              <?php foreach ($models as $model) :?>
              <tr>
                <td class="center"><a href="<?=Url::to(['flashsale/edit', 'id' => $model->id]);?>"><?=$model->id;?></a></td>
                <td class="left"><?=$model->title;?></td>
                <td class="left"><?=sprintf("%s - %s", $model->start_from, $model->start_to);?></td>
                <td class="left">
                  <a href='<?=Url::to(['flashsale/edit', 'id' => $model->id]);?>' class="btn btn-xs grey-salsa tooltips" data-pjax="0" data-container="body" data-original-title="Chỉnh sửa"><i class="fa fa-pencil"></i></a>
                  <a href='<?=Url::to(['flashsale/delete', 'id' => $model->id]);?>' class="btn btn-xs grey-salsa tooltips delete" data-pjax="0" data-container="body" data-original-title="Xoá"><i class="fa fa-close"></i></a>
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
<?php
$script = <<< JS
// delete
$('.delete').ajax_action({
  method: 'DELETE',
  confirm: true,
  confirm_text: 'Bạn có muốn xóa flash sale này không?',
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