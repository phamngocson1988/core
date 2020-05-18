<?php
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
      <span><?=Yii::t('app', 'manage_roles');?></span>
    </li>
  </ul>
</div>
<!-- END PAGE BAR -->
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title"><?=Yii::t('app', 'manage_roles');?></h1>
<!-- END PAGE TITLE-->
<div class="row">
  <div class="col-md-12">
    <!-- BEGIN EXAMPLE TABLE PORTLET-->
    <div class="portlet light bordered">
      <div class="portlet-title">
        <div class="caption font-dark">
          <i class="icon-settings font-dark"></i>
          <span class="caption-subject bold uppercase"> <?=Yii::t('app', 'manage_roles');?></span>
        </div>
      </div>
      <div class="portlet-body">
        <table class="table table-striped table-bordered table-hover table-checkable">
          <thead>
            <tr>
              <th style="width: 50%;"> <?=Yii::t('app', 'description');?> </th>
              <th style="width: 20%;"> <?=Yii::t('app', 'count');?> </th>
              <th style="width: 20%;" class="dt-center"> <?=Yii::t('app', 'actions');?> </th>
            </tr>
          </thead>
          <tbody>
            <?php if (!$models) : ?>
            <tr>
              <td colspan="3"><?=Yii::t('app', 'no_data_found');?></td>
            </tr>
            <?php else : ?>
            <?php foreach ($models as $model) :?>
            <tr>
              <td><?=$model->description;?></td>
              <td><?=count(Yii::$app->authManager->getUserIdsByRole($model->name));?></td>
              <td>
                <a class="btn btn-xs grey-salsa tooltips" href="<?=Url::to(['rbac/index', 'role' => $model->name]);?>" data-container="body" data-original-title="<?=Yii::t('app', 'list_user');?>"><i class="fa fa-list"></i></a>
                <a class="btn btn-xs grey-salsa tooltips" href="<?=Url::to(['rbac/assign', 'role' => $model->name]);?>" data-container="body" data-original-title="<?=Yii::t('app', 'add_new');?>"><i class="fa fa-plus"></i></a>
              </td>
            </tr>
            <?php endforeach;?>
            <?php endif;?>
          </tbody>
        </table>
      </div>
    </div>
    <!-- END EXAMPLE TABLE PORTLET-->
  </div>
</div>