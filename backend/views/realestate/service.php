<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use dosamigos\datepicker\DatePicker;
use dosamigos\datepicker\DateRangePicker;
use yii\web\JsExpression;
use common\models\Promotion;
use common\widgets\TinyMce;
?>
<!-- BEGIN PAGE BAR -->
<div class="page-bar">
  <ul class="page-breadcrumb">
    <li>
      <a href="/"><?=Yii::t('app', 'home')?></a>
      <i class="fa fa-circle"></i>
    </li>
    <li>
      <a href="<?=Url::to(['promotion/index'])?>">Quản lý khuyến mãi</a>
      <i class="fa fa-circle"></i>
    </li>
    <li>
      <span>Tạo khuyến mãi</span>
    </li>
  </ul>
</div>
<!-- END PAGE BAR -->
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title">Tạo khuyến mãi</h1>
<!-- END PAGE TITLE-->
<div class="row">
  <div class="col-md-12">
    <?php $form = ActiveForm::begin(['options' => ['class' => 'form-horizontal form-row-seperated']]);?>
      <div class="portlet">
        <div class="portlet-title">
          <div class="actions btn-set">
            <a href="{$back}" class="btn default">
            <i class="fa fa-angle-left"></i> <?=Yii::t('app', 'back')?></a>
            <button type="submit" class="btn btn-success">
            <i class="fa fa-check"></i> <?=Yii::t('app', 'save')?>
            </button>
          </div>
        </div>
        <div class="portlet-body">
          <div class="tabbable-bordered">
            <ul class="nav nav-tabs">
              <li class="active">
                <a href="#tab_general" data-toggle="tab"> <?=Yii::t('app', 'main_content')?></a>
              </li>
            </ul>
            <div class="tab-content">
              <div class="tab-pane active" id="tab_general">
                <div class="form-body">
                  <?=$form->field($realestate, 'title', [
                    'labelOptions' => ['class' => 'col-md-2 control-label'],
                    'inputOptions' => ['class' => 'form-control', 'disabled' => true, 'readonly' => true, 'name' => ''],
                    'template' => '{label}<div class="col-md-6">{input}{hint}{error}</div>'
                  ])->textInput()->label('Tên bất động sản')?>

                  <hr/>
                  <table class="table table-striped table-bordered table-hover table-checkable">
                    <thead>
                      <tr>
                        <th style="width: 10%;"> ID </th>
                        <th style="width: 60%;"> Tên dịch vụ </th>
                        <th style="width: 30%;"> Tác vụ </th>
                      </tr>
                    </thead>
                    <tbody>
                        <?php if (!$realestate->realestateServices) :?>
                        <tr><td colspan="4"><?=Yii::t('app', 'no_data_found');?></td></tr>
                        <?php endif;?>
                        <?php foreach ($realestate->realestateServices as $no => $realestateService) :?>
                        <?php $service = $realestateService->service;?>
                        <tr>
                          <td style="vertical-align: middle;"><?=$service->id;?></td>
                          <td style="vertical-align: middle;"><?=$service->title;?></td>
                          <td style="vertical-align: middle;"><?=number_format($realestateService->price);?></td>
                          <td style="vertical-align: middle;">
                            <a href='<?=Url::to(['service/edit', 'id' => $model->id]);?>' class="btn btn-xs grey-salsa tooltips" data-pjax="0" data-container="body" data-original-title="Chỉnh sửa"><i class="fa fa-pencil"></i></a>
                          </td>
                        </tr>
                        <?php endforeach;?>
                    </tbody>
                  </table>
                  <hr/>
                  <?=$form->field($model, 'service_id', [
                    'labelOptions' => ['class' => 'col-md-2 control-label'],
                    'inputOptions' => ['class' => 'form-control', 'id' => 'benefits'],
                    'template' => '{label}<div class="col-md-6">{input}{hint}{error}</div>',
                  ])->dropDownList(ArrayHelper::map($services, 'id', 'title'), ['prompt' => 'Chọn giá trị áp dụng'])->label('Giá trị áp dụng');?>

                  <?=$form->field($model, 'price', [
                    'labelOptions' => ['class' => 'col-md-2 control-label'],
                    'inputOptions' => ['class' => 'form-control', 'type' => 'number'],
                    'template' => '{label}<div class="col-md-6">{input}{hint}{error}</div>'
                  ])->textInput()->label('Giá dịch vụ')?>

                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    <?php ActiveForm::end()?>
  </div>
</div>