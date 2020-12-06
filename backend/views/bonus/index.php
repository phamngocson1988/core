<?php 
use yii\widgets\LinkPager;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\components\helpers\LanguageHelper;
?>
<!-- BEGIN PAGE BAR -->
<div class="page-bar">
  <ul class="page-breadcrumb">
    <li>
      <a href="/"><?=Yii::t('app', 'home');?></a>
      <i class="fa fa-circle"></i>
    </li>
    <li>
      <span><?=Yii::t('app', 'manage_bonus');?></span>
    </li>
  </ul>
</div>
<!-- END PAGE BAR -->
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title"><?=Yii::t('app', 'manage_bonus');?></h1>
<!-- END PAGE TITLE-->
<div class="row">
  <div class="col-md-12">
    <!-- BEGIN EXAMPLE TABLE PORTLET-->
    <div class="portlet light bordered">
      <div class="portlet-title">
        <div class="caption font-dark">
          <i class="icon-settings font-dark"></i>
          <span class="caption-subject bold uppercase"> <?=Yii::t('app', 'manage_bonus');?></span>
        </div>
        <div class="actions">
          <div class="btn-group btn-group-devided">
            <a class="btn green" href="#choose-language" data-toggle="modal"><?=Yii::t('app', 'add_new');?></a>
          </div>
        </div>
      </div>
      <div class="portlet-body">
        <?php $form = ActiveForm::begin(['method' => 'GET', 'action' => ['bonus/index']]);?>
        <div class="row margin-bottom-10">
            <?=$form->field($search, 'q', [
              'options' => ['class' => 'form-group col-md-4 col-lg-3'],
              'inputOptions' => ['class' => 'form-control', 'name' => 'q']
            ])->textInput();?>

            <?=$form->field($search, 'operator_id', [
              'options' => ['class' => 'form-group col-md-4 col-lg-3'],
              'inputOptions' => ['class' => 'bs-select form-control', 'name' => 'operator_id']
            ])->dropDownList($search->fetchOperator(), ['prompt' => Yii::t('app', 'choose_operator')]);?>

            <?=$form->field($search, 'language', [
              'options' => ['class' => 'form-group col-md-4 col-lg-3'],
              'inputOptions' => ['class' => 'bs-select form-control', 'name' => 'language']
            ])->dropDownList($search->fetchLanguages(), ['prompt' => Yii::t('app', 'choose_language')]);?>

            <div class="form-group col-md-4 col-lg-3">
              <button type="submit" class="btn btn-success table-group-action-submit" style="margin-top: 25px;">
                <i class="fa fa-check"></i> <?=Yii::t('app', 'search')?>
              </button>
            </div>
        </div>
        <?php ActiveForm::end()?>
        <div class="table-responsive">
          <table class="table table-striped table-bordered table-hover table-checkable">
            <thead>
              <tr>
                <th> <?=Yii::t('app', 'id');?> </th>
                <th> <?=Yii::t('app', 'title');?> </th>
                <th> <?=Yii::t('app', 'operator');?> </th>
                <th> <?=Yii::t('app', 'created_date');?> </th>
                <th> <?=Yii::t('app', 'language');?> </th>
                <th> <?=Yii::t('app', 'actions');?> </th>
              </tr>
            </thead>
            <tbody>
                <?php if (!$models) : ?>
                <tr><td colspan="6"><?=Yii::t('app', 'no_data_found');?></td></tr>
                <?php endif; ?>
                <?php foreach ($models as $model) : ?>
                <tr>
                  <td class="center"><?=$model->id;?></td>
                  <td class="left"><img class="img-thumbnail" width="50px" height="50px" src="<?=$model->getImageUrl('50x50');?>">
                    <?=$model->title;?></td>
                  <td class="left"><?=$model->operator_id ? $model->operator->name : '';?></td>
                  <td class="left"><?=$model->created_at;?></td>
                  <td class="left"><?=LanguageHelper::getLanguageName($model->language);?></td>
                  <td class="left">
                    <a href='<?=Url::to(['bonus/edit', 'id' => $model->id]);?>' class="btn btn-xs grey-salsa"><i class="fa fa-pencil"></i></a>
                  </td>
                </tr>
                <?php endforeach;?>
            </tbody>
          </table>
        </div>
        <?=LinkPager::widget(['pagination' => $pages]);?>
      </div>
    </div>
    <!-- END EXAMPLE TABLE PORTLET-->
  </div>
</div>
<?=\backend\widgets\LanguageModalWidget::widget(['url' => Url::to(['bonus/create'])]);?>