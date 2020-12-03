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
      <span><?=Yii::t('app', 'manage_ads');?></span>
    </li>
  </ul>
</div>
<!-- END PAGE BAR -->
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title"><?=Yii::t('app', 'manage_ads');?></h1>
<!-- END PAGE TITLE-->
<div class="row">
  <div class="col-md-12">
    <!-- BEGIN EXAMPLE TABLE PORTLET-->
    <div class="portlet light bordered">
      <div class="portlet-title">
        <div class="caption font-dark">
          <i class="icon-settings font-dark"></i>
          <span class="caption-subject bold uppercase"> <?=Yii::t('app', 'manage_ads');?></span>
        </div>
        <div class="actions">
          <div class="btn-group btn-group-devided">
            <a class="btn green" href="#choose-language" data-toggle="modal"><?=Yii::t('app', 'add_new');?></a>
          </div>
        </div>
      </div>
      <div class="portlet-body">
        <?php $form = ActiveForm::begin(['method' => 'GET', 'action' => ['ads/index']]);?>
        <div class="row margin-bottom-10">
            <?=$form->field($search, 'position', [
              'options' => ['class' => 'form-group col-md-4 col-lg-3'],
              'inputOptions' => ['class' => 'bs-select form-control', 'name' => 'position']
            ])->dropDownList($search->fetchPosition(), ['prompt' => Yii::t('app', 'choose_position')]);?>

            <?=$form->field($search, 'status', [
              'options' => ['class' => 'form-group col-md-4 col-lg-3'],
              'inputOptions' => ['class' => 'bs-select form-control', 'name' => 'status']
            ])->dropDownList($search->fetchStatus(), ['prompt' => Yii::t('app', 'choose_status')]);?>

            <?=$form->field($search, 'contact_email', [
              'options' => ['class' => 'form-group col-md-4 col-lg-3'],
              'inputOptions' => ['class' => 'form-control', 'name' => 'contact_email']
            ])->textInput();?>

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
                <th> <?=Yii::t('app', 'position');?> </th>
                <th> <?=Yii::t('app', 'time');?> </th>
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
                  <td class="left"><?=$model->getPosition();?></td>
                  <td class="left"><?=sprintf("%s - %s", $model->start_date, $model->end_date);?></td>
                  <td class="left"><?=$model->created_at;?></td>
                  <td class="left">
                    <a href='<?=Url::to(['ads/edit', 'id' => $model->id]);?>' class="btn btn-xs grey-salsa"><i class="fa fa-pencil"></i></a>
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
<?=\backend\widgets\LanguageModalWidget::widget(['url' => Url::to(['ads/create'])]);?>
