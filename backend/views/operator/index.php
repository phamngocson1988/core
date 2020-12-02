<?php 
use yii\widgets\LinkPager;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use backend\models\Operator;

?>

<!-- BEGIN PAGE BAR -->
<div class="page-bar">
  <ul class="page-breadcrumb">
    <li>
      <a href="/"><?=Yii::t('app', 'home');?></a>
      <i class="fa fa-circle"></i>
    </li>
    <li>
      <span><?=Yii::t('app', 'manage_operator');?></span>
    </li>
  </ul>
</div>
<!-- END PAGE BAR -->
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title"><?=Yii::t('app', 'manage_operator');?></h1>
<!-- END PAGE TITLE-->
<div class="row">
  <div class="col-md-12">
    <!-- BEGIN EXAMPLE TABLE PORTLET-->
    <div class="portlet light bordered">
      <div class="portlet-title">
        <div class="caption font-dark">
          <i class="icon-settings font-dark"></i>
          <span class="caption-subject bold uppercase"> <?=Yii::t('app', 'manage_operator');?></span>
        </div>
        <div class="actions">
          <div class="btn-group btn-group-devided">
            <a class="btn green" href="#choose-language" data-toggle="modal"><?=Yii::t('app', 'add_new');?></a>
          </div>
        </div>
      </div>
      <div class="portlet-body">
        <div class="row margin-bottom-10">
          <?php $form = ActiveForm::begin(['method' => 'GET', 'action' => ['operator/index']]);?>
            <?=$form->field($search, 'q', [
              'options' => ['class' => 'form-group col-md-4 col-lg-3'],
              'inputOptions' => ['class' => 'form-control', 'name' => 'q']
            ])->textInput();?>

            <?=$form->field($search, 'status', [
              'options' => ['class' => 'form-group col-md-4 col-lg-3'],
              'inputOptions' => ['class' => 'form-control', 'name' => 'status']
            ])->dropDownList($search->fetchStatus(),  ['prompt' => Yii::t('app', 'choose_status')]);?>

            <?=$form->field($search, 'language', [
              'options' => ['class' => 'form-group col-md-4 col-lg-3'],
              'inputOptions' => ['class' => 'form-control', 'name' => 'language']
            ])->dropDownList($search->fetchLanguages(),  ['prompt' => Yii::t('app', 'choose_language')]);?>

            <div class="form-group col-md-4 col-lg-3">
              <button type="submit" class="btn btn-success table-group-action-submit"
                style="margin-top:
                25px;">
              <i class="fa fa-check"></i> <?=Yii::t('app', 'search');?>
              </button>
            </div>
          <?php ActiveForm::end()?>
        </div>
        <div class="table-responsive">
          <table class="table table-bordered">
            <thead>
              <tr>
                <th> <?=Yii::t('app', 'id');?> </th>
                <th> <?=Yii::t('app', 'name');?> </th>
                <th> <?=Yii::t('app', 'main_url');?> </th>
                <th> <?=Yii::t('app', 'Admin');?> </th>
                <th> <?=Yii::t('app', 'status');?> </th>
                <th> <?=Yii::t('app', 'actions');?> </th>
              </tr>
            </thead>
            <tbody>
              <?php if ($models) : ?>
              <?php foreach ($models as $model) : ?>
              <tr>
                <td class="center"><?=$model->id;?></td>
                <td class="left">
                  <img class="img-thumbnail" width="50px" height="50px" src="<?=$model->getImageUrl('50x50');?>">
                  <?=$model->name;?>
                </td>
                <td class="left"><?=$model->main_url;?></td>
                <td class="left">
                  <?php
                  $staff = ArrayHelper::getValue($managers, $model->id);
                  echo $staff ? $staff->getName() : '';
                  ?>
                </td>
                <td class="center">
                  <?php switch ($model->status) {
                    case Operator::STATUS_ACTIVE:
                      echo '<span class="label label-success">Active</span>';
                      break;
                    case Operator::STATUS_INACTIVE:
                      echo '<span class="label label-warning">Inactive</span>';
                      break;
                    case Operator::STATUS_DELETED:
                      echo '<span class="label label-danger">Deleted</span>';
                      break;
                  }?>
                </td>
                <td class="center">
                  <a class="btn btn-xs default tooltips" href="<?=Url::to(['operator/edit', 'id' => $model->id]);?>" data-container="body" data-original-title="<?=Yii::t('app', 'edit_operator');?>"><i class="fa fa-pencil"></i></a>
                </td>
              </tr>
              <?php endforeach;?>
              <?php else : ?>
              <tr>
                <td colspan="5"><?=Yii::t('app', 'no_data_found');?></td>
              </tr>
              <?php endif;?>
            </tbody>
          </table>
        </div>
        <?=LinkPager::widget(['pagination' => $pages]);?>
      </div>
    </div>
    <!-- END EXAMPLE TABLE PORTLET-->
  </div>
</div>
<?=\backend\widgets\LanguageModalWidget::widget(['url' => Url::to(['operator/create'])]);?>
