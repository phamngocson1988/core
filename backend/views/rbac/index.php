<?php
use yii\widgets\LinkPager;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
?>
<!-- BEGIN PAGE BAR -->
<div class="page-bar">
  <ul class="page-breadcrumb">
    <li>
      <a href="/"><?=Yii::t('app', 'home');?></a>
      <i class="fa fa-circle"></i>
    </li>
    <li>
      <span><?=Yii::t('app', 'manage_staff');?></span>
    </li>
  </ul>
</div>
<!-- END PAGE BAR -->
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title"><?=Yii::t('app', 'manage_staff');?></h1>
<!-- END PAGE TITLE-->
<div class="row">
  <div class="col-md-12">
    <!-- BEGIN EXAMPLE TABLE PORTLET-->
    <div class="portlet light bordered">
      <div class="portlet-title">
        <div class="caption font-dark">
          <i class="icon-settings font-dark"></i>
          <span class="caption-subject bold uppercase"> <?=Yii::t('app', 'manage_staff');?></span>
        </div>
      </div>
      <div class="portlet-body">
        <?php $form = ActiveForm::begin(['method' => 'GET', 'action' => ['rbac/index']]);?>
        <div class="row margin-bottom-10">
          <?=$form->field($search, 'q', [
            'options' => ['class' => 'form-group col-md-4 col-lg-3'],
            'inputOptions' => ['class' => 'form-control', 'name' => 'q']
          ])->textInput();?>

          <?=$form->field($search, 'role', [
            'options' => ['class' => 'form-group col-md-4 col-lg-3'],
            'inputOptions' => ['class' => 'bs-select form-control', 'name' => 'role']
          ])->dropDownList($search->getUserStatus());?>
        </div>
        <?php ActiveForm::end()?>
        <table class="table table-striped table-bordered table-hover table-checkable">
          <thead>
            <tr>
              <th> <?=Yii::t('app', 'id');?> </th>
              <th> <?=Yii::t('app', 'name');?> </th>
              <th> <?=Yii::t('app', 'username');?> </th>
              <th> <?=Yii::t('app', 'email');?> </th>
              <th> <?=Yii::t('app', 'role');?> </th>
              <th> <?=Yii::t('app', 'actions');?> </th>
            </tr>
          </thead>
          <tbody>
            <?php if (!$models) : ?>
            <tr>
              <td colspan="6"><?=Yii::t('app', 'no_data_found');?></td>
            </tr>
            <?php else : ?>
            <?php foreach ($models as $model) :?>
            <tr>
              <td><?=$model->id;?></td>
              <td><?=$model->name;?></td>
              <td><?=$model->username;?></td>
              <td><?=$model->email;?></td>
              <td>
                <?php
                $auth = Yii::$app->authManager;
                $roles = $auth->getRolesByUser($model->id);
                $roleNames = ArrayHelper::map($roles, 'name', 'description');
                ?>
                <?php foreach ($roleNames as $roleName) : ?>
                <span class="label label-info label-many"><?=$roleName;?></span>
                <?php endforeach;?>
              </td>
              <td>
              </td>
            </tr>
            <?php endforeach;?>
            <?php endif;?>
          </tbody>
        </table>
        <?=LinkPager::widget(['pagination' => $pages]);?>
      </div>
    </div>
    <!-- END EXAMPLE TABLE PORTLET-->
  </div>
</div>