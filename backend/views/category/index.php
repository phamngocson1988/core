<?php 
use yii\widgets\LinkPager;
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
      <span><?=Yii::t('app', 'manage_category');?></span>
    </li>
  </ul>
</div>
<!-- END PAGE BAR -->
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title"><?=Yii::t('app', 'manage_category');?></h1>
<!-- END PAGE TITLE-->
<div class="row">
  <div class="col-md-12">
    <!-- BEGIN EXAMPLE TABLE PORTLET-->
    <div class="portlet light bordered">
      <div class="portlet-title">
        <div class="caption font-dark">
          <i class="icon-settings font-dark"></i>
          <span class="caption-subject bold uppercase"> <?=Yii::t('app', 'manage_category');?></span>
        </div>
        <div class="actions">
          <div class="btn-group btn-group-devided">
            <a class="btn green" href="#choose-language" data-toggle="modal"><?=Yii::t('app', 'add_new');?></a>
          </div>
        </div>
      </div>
      <div class="table-responsive">
        <table class="table table-striped table-bordered table-hover table-checkable">
          <thead>
            <tr>
              <th> <?=Yii::t('app', 'id');?> </th>
              <th> <?=Yii::t('app', 'title');?> </th>
              <th> <?=Yii::t('app', 'number_of_post');?> </th>
              <th> <?=Yii::t('app', 'actions');?> </th>
            </tr>
          </thead>
          <tbody>
              <?php if (!$models) : ?>
              <tr><td colspan="4"><?=Yii::t('app', 'no_data_found');?></td></tr>
              <?php endif; ?>
              <?php foreach ($models as $model) : ?>
              <tr>
                <td class="center"><?=$model->id;?></td>
                <td class="left"><img class="img-thumbnail" width="50px" height="50px" src="<?=$model->getImageUrl('50x50');?>">
                  <?=$model->title;?></td>
                <td class="left"></td>
                <td class="left">
                  <a href='<?=Url::to(['category/edit', 'id' => $model->id]);?>' class="btn btn-xs grey-salsa"><i class="fa fa-pencil"></i></a>
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
<?=\backend\widgets\LanguageModalWidget::widget(['url' => Url::to(['category/create'])]);?>
