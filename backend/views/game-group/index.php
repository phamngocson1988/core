<?php
use yii\widgets\LinkPager;
use yii\widgets\Pjax;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use yii\web\JsExpression;
?>
<!-- BEGIN PAGE BAR -->
<div class="page-bar">
  <ul class="page-breadcrumb">
    <li>
      <a href="/"><?=Yii::t('app', 'home')?></a>
      <i class="fa fa-circle"></i>
    </li>
    <li>
      <span>Nhóm game</span>
    </li>
  </ul>
</div>
<!-- END PAGE BAR -->
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title">Nhóm game</h1>
<!-- END PAGE TITLE-->
<div class="row">
  <div class="col-md-12">
    <!-- BEGIN EXAMPLE TABLE PORTLET-->
    <div class="portlet light bordered">
      <div class="portlet-title">
        <div class="caption font-dark">
          <i class="icon-settings font-dark"></i>
          <span class="caption-subject bold uppercase"> Nhóm game</span>
        </div>
        <div class="actions">
          <div class="btn-group btn-group-devided">
            <a class="btn green" href="<?=Url::to(['game-group/create'])?>"><?=Yii::t('app', 'add_new')?></a>
          </div>
        </div>
      </div>
      <div class="portlet-body">
        <div class="row margin-bottom-10">
          <?php $form = ActiveForm::begin(['method' => 'GET', 'action' => ['game-group/index']]);?>     
            <?=$form->field($search, 'q', [
              'options' => ['class' => 'form-group col-md-4 col-lg-3'],
              'inputOptions' => ['class' => 'form-control', 'name' => 'q']
            ])->textInput()->label('Mã đơn hàng');?>

            <div class="form-group col-md-2">
              <button type="submit" class="btn btn-success table-group-action-submit" style="margin-top: 25px;">
                <i class="fa fa-check"></i> <?=Yii::t('app', 'search')?>
              </button>
            </div>
          <?php ActiveForm::end()?>
        </div>
        <?php Pjax::begin(); ?>
        <table class="table table-striped table-bordered table-hover table-checkable">
          <thead>
            <tr>
              <th> ID </th>
              <th> <?=Yii::t('app', 'title')?> </th>
              <th> Phương thức </th>
              <th> Phiên bản </th>
              <th> Loại gói </th>
              <th></th>
            </tr>
          </thead>
          <tbody>
              <?php if (!$models) :?>
              <tr><td colspan="6"><?=Yii::t('app', 'no_data_found')?></td></tr>
              <?php endif;?>
              <?php foreach ($models as $key => $model) :?>
              <tr>
                <td><a href="<?=Url::to(['game-group/edit', 'id' => $model->id])?>"><?=$model->id;?></a></td>
                <td><?=$model->title;?></td>
                <td>
                  <?php 
                  $methods = $model->getMethods();
                  $methodTitles = ArrayHelper::getColumn($methods, 'title');
                  echo implode(",", $methodTitles);
                  ?>
                </td>
                <td>
                  <?php 
                  $versions = $model->getVersions();
                  $versionTitles = ArrayHelper::getColumn($versions, 'title');
                  echo implode(",", $versionTitles);
                  ?>
                </td>
                <td>
                  <?php 
                  $packages = $model->packages;
                  $packageTitles = ArrayHelper::getColumn($packages, 'title');
                  echo implode(",", $packageTitles);
                  ?>
                </td>
                <td class="left">
                  <a href='<?=Url::to(['game-group/edit', 'id' => $model->id]);?>' class="btn btn-xs grey-salsa tooltips" data-pjax="0" data-container="body" data-original-title="Chỉnh sửa"><i class="fa fa-pencil"></i></a>
                  <a href='<?=Url::to(['game-group/delete', 'id' => $model->id]);?>' class="btn btn-xs grey-salsa tooltips delete" data-pjax="0" data-container="body" data-original-title="Xoá"><i class="fa fa-close"></i></a>
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
  confirm_text: 'Bạn có muốn xóa nhóm game này không?',
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