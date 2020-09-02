<?php 
use yii\widgets\LinkPager;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
?>
<!-- BEGIN PAGE BAR -->
<div class="page-bar">
  <ul class="page-breadcrumb">
    <li>
      <a href="/"><?=Yii::t('app', 'home');?></a>
      <i class="fa fa-circle"></i>
    </li>
    <li>
      <a href="<?=Url::to(['topic/index']);?>"><?=Yii::t('app', 'manage_topic');?></a>
      <i class="fa fa-circle"></i>
    </li>
    <li>
      <span><?=Yii::t('app', 'manage_forum_post');?></span>
    </li>
  </ul>
</div>
<!-- END PAGE BAR -->
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title"><?=Yii::t('app', 'manage_topic');?></h1>
<!-- END PAGE TITLE-->
<div class="row">
  <div class="col-md-12">
    <!-- BEGIN EXAMPLE TABLE PORTLET-->
    <div class="portlet light bordered">
      <div class="portlet-title">
        <div class="caption font-dark">
          <i class="icon-settings font-dark"></i>
          <span class="caption-subject bold uppercase"> <?=$topic->subject;?></span>
        </div>
      </div>
      <div class="row margin-bottom-10">
      <?php $form = ActiveForm::begin(['method' => 'GET', 'action' => ['topic/list', 'id' => $topic->id]]);?>
          <?=$form->field($search, 'q', [
            'options' => ['class' => 'form-group col-md-4 col-lg-3'],
            'inputOptions' => ['class' => 'form-control', 'name' => 'q']
          ])->textInput();?>

          <?=$form->field($search, 'created_by', [
            'options' => ['class' => 'form-group col-md-4 col-lg-3'],
            'inputOptions' => ['class' => 'form-control', 'name' => 'created_by']
          ])->dropDownList($search->fetchUser(),  ['prompt' => Yii::t('app', 'choose_user')]);?>

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
        <table class="table table-striped table-bordered table-hover table-checkable">
          <thead>
            <tr>
              <th> <?=Yii::t('app', 'id');?> </th>
              <th> <?=Yii::t('app', 'content');?> </th>
              <th> <?=Yii::t('app', 'created_by');?> </th>
              <th> <?=Yii::t('app', 'created_at');?> </th>
              <th> <?=Yii::t('app', 'approved');?> </th>
              <th> <?=Yii::t('app', 'status');?> </th>
              <th> <?=Yii::t('app', 'actions');?> </th>
            </tr>
          </thead>
          <tbody>
              <?php if (!$models) : ?>
              <tr><td colspan="7"><?=Yii::t('app', 'no_data_found');?></td></tr>
              <?php endif; ?>
              <?php foreach ($models as $model) : ?>
              <tr>
                <td class="center"><?=$model->id;?></td>
                <td class="left"><?=\yii\helpers\StringHelper::truncateWords($model->content, 100);?></td>
                <td class="left"><?=$model->sender->getName();?></td>
                <td class="left"><?=$model->created_at;?></td>
                <td class="left"><?=$model->is_approved ? 'YES' : 'NO';?></td>
                <td class="left"><?=$model->getStatusLabel();?></td>
                <td class="left">
                  <a href='<?=Url::to(['topic/edit-post', 'id' => $model->id]);?>' class="btn btn-xs grey-salsa"><i class="fa fa-pencil"></i></a>
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
