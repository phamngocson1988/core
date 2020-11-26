<?php 
use yii\widgets\LinkPager;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
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
      <span><?=Yii::t('app', 'manage_post');?></span>
    </li>
  </ul>
</div>
<!-- END PAGE BAR -->
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title"><?=Yii::t('app', 'manage_post');?></h1>
<!-- END PAGE TITLE-->
<div class="row">
  <div class="col-md-12">
    <!-- BEGIN EXAMPLE TABLE PORTLET-->
    <div class="portlet light bordered">
      <div class="portlet-title">
        <div class="caption font-dark">
          <i class="icon-settings font-dark"></i>
          <span class="caption-subject bold uppercase"> <?=Yii::t('app', 'manage_post');?></span>
        </div>
        <div class="actions">
          <div class="btn-group btn-group-devided">
            <a class="btn green" href="#choose-language" data-toggle="modal"><?=Yii::t('app', 'add_new');?></a>
          </div>
        </div>
      </div>
      <div class="portlet-body">
        <?php $form = ActiveForm::begin(['method' => 'GET', 'action' => ['post/index']]);?>
        <div class="row margin-bottom-10">
            <?=$form->field($search, 'q', [
              'options' => ['class' => 'form-group col-md-4 col-lg-3'],
              'inputOptions' => ['class' => 'form-control', 'name' => 'q']
            ])->textInput();?>

            <?=$form->field($search, 'operator_id', [
              'options' => ['class' => 'form-group col-md-4 col-lg-3'],
              'inputOptions' => ['class' => 'bs-select form-control', 'name' => 'operator_id']
            ])->dropDownList($search->fetchOperator(), ['prompt' => Yii::t('app', 'choose_operator')]);?>

            <?=$form->field($search, 'category_id', [
              'options' => ['class' => 'form-group col-md-4 col-lg-3'],
              'inputOptions' => ['class' => 'bs-select form-control', 'name' => 'category_id']
            ])->dropDownList($search->fetchCategory(), ['prompt' => Yii::t('app', 'choose_category')]);?>

            <?=$form->field($search, 'status', [
              'options' => ['class' => 'form-group col-md-4 col-lg-3'],
              'inputOptions' => ['class' => 'bs-select form-control', 'name' => 'status']
            ])->dropDownList($search->fetchStatus(), ['prompt' => Yii::t('app', 'choose_status')]);?>

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
                <th> <?=Yii::t('app', 'category');?> </th>
                <th> <?=Yii::t('app', 'operator');?> </th>
                <th> <?=Yii::t('app', 'created_date');?> </th>
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
                  <td class="left">
                    <?php 
                    $categories = ArrayHelper::getColumn((array)$model->categories, 'title');
                    echo implode(", ", $categories);
                    ?>
                  </td>
                  <td class="left"><?=$model->operator_id ? $model->operator->name : '';?></td>
                  <td class="left"><?=$model->created_at;?></td>
                  <td class="left">
                    <a href='<?=Url::to(['post/edit', 'id' => $model->id]);?>' class="btn btn-xs grey-salsa"><i class="fa fa-pencil"></i></a>
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
<?=\backend\widgets\LanguageModalWidget::widget(['url' => Url::to(['post/create'])]);?>
