<?php 
use yii\widgets\LinkPager;
use yii\widgets\Pjax;
?>
<!-- BEGIN PAGE BAR -->
<div class="page-bar">
  <ul class="page-breadcrumb">
    <li>
      <a href="/"><?=Yii::t('app', 'home');?></a>
      <i class="fa fa-circle"></i>
    </li>
    <li>
      <span><?=Yii::t('app', 'manage_games');?></span>
    </li>
  </ul>
</div>
<!-- END PAGE BAR -->
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title"><?=Yii::t('app', 'manage_games');?></h1>
<!-- END PAGE TITLE-->
<div class="row">
  <div class="col-md-12">
    <!-- BEGIN EXAMPLE TABLE PORTLET-->
    <div class="portlet light bordered">
      <div class="portlet-title">
        <div class="caption font-dark">
          <i class="icon-settings font-dark"></i>
          <span class="caption-subject bold uppercase"> <?=Yii::t('app', 'manage_games');?></span>
        </div>
      </div>
      <div class="portlet-body">
        <div class="row margin-bottom-10">
          <form method="GET">
            <div class="form-group col-md-4">
              <label><?=Yii::t('app', 'keyword');?>: </label> <input type="search" class="form-control" placeholder="<?=Yii::t('app', 'keyword');?>" name="q" value="<?=$form->q;?>">
            </div>
            <div class="form-group col-md-3">
              <button type="submit" class="btn btn-success table-group-action-submit" style="margin-top: 25px;">
                <i class="fa fa-check"></i> <?=Yii::t('app', 'search');?>
              </button>
            </div>
          </form>
        </div>
        <table class="table table-striped table-bordered table-hover table-checkable">
          <thead>
            <tr>
              <th style="width: 5%;"> <?=Yii::t('app', 'no');?> </th>
              <th style="width: 10%;"> <?=Yii::t('app', 'image');?> </th>
              <th style="width: 25%;"> <?=Yii::t('app', 'title');?> </th>
              <th style="width: 15%;"> <?=Yii::t('app', 'status');?> </th>
              <th style="width: 10%;" class="dt-center"> <?=Yii::t('app', 'actions');?> </th>
            </tr>
          </thead>
          <tbody>
              <?php if (!$models) : ?>
              <tr><td colspan="6"><?=Yii::t('app', 'no_data_found');?></td></tr>
              <?php endif;?>
              <?php foreach ($models as $model) : ?>
              <tr>
                <td style="vertical-align: middle;"><?=$model->id;?></td>
                <td style="vertical-align: middle;"><img src="<?=$model->getImageUrl('50x50');?>" width="50px;" /></td>
                <td style="vertical-align: middle;"><?=$model->title;?></td>
                <td style="vertical-align: middle;">
                  <?php if ($model->status == 'Y') : ?>
                  <span class="label label-success"><?=Yii::t('app', 'visible');?></span>
                  <?php elseif ($model->status == 'N') : ?>
                  <span class="label label-warning"><?=Yii::t('app', 'disable');?></span>
                  <?php elseif ($model->status == 'D') : ?>
                  <span class="label label-default"><?=Yii::t('app', 'deleted');?></span>
                  <?php endif;?>
                  
                </td>
                <td style="vertical-align: middle;">
                    <a href='{url route="game/edit" id=$model->id ref=$ref}' class="btn btn-xs grey-salsa tooltips" data-container="body" data-original-title="<?=Yii::t('app', 'edit');?>" data-pjax="0"><i class="fa fa-pencil"></i></a>
                    <a href='{url route="game/delete" id=$model->id ref=$ref}' class="btn btn-xs grey-salsa delete-action tooltips" data-container="body" data-original-title="<?=Yii::t('app', 'delete');?>" data-pjax="0"><i class="fa fa-trash-o"></i></a>
                </td>
              </tr>
              <?php endforeach;?>
          </tbody>
        </table>
        <?=LinkPager::widget(['pagination' => $pages]);?>
      </div>
    </div>
    <!-- END EXAMPLE TABLE PORTLET-->
  </div>
</div>
