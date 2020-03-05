<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\widgets\TinyMce;
use yii\widgets\Pjax;
use yii\helpers\Url;
use unclead\multipleinput\MultipleInput;
use common\widgets\ImageInputWidget;
use common\widgets\RadioListInput;
use common\widgets\CheckboxInput;
use common\widgets\MultipleImageInputWidget;

$this->registerCssFile('@web/vendor/assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.css', ['depends' => [\backend\assets\AppAsset::className()]]);
$this->registerCssFile('@web/vendor/assets/pages/css/profile.min.css', ['depends' => [\backend\assets\AppAsset::className()]]);
$this->registerJsFile('@web/vendor/assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile('@web/vendor/assets/global/plugins/jquery.sparkline.min.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile('@web/vendor/assets/pages/scripts/profile.min.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile('@web/js/jquery.number.min.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
?>

<!-- BEGIN PAGE BAR -->
<div class="page-bar">
  <ul class="page-breadcrumb">
    <li>
      <a href="<?=Url::to(['game/edit', 'id' => $id]);?>">Cập nhật game</a>
      <i class="fa fa-circle"></i>
    </li>
    <li>
      <a href="<?=Url::to(['game/suppliers', 'id' => $id]);?>">Giá nhà cung cấp</a>
    </li>
  </ul>
</div>
<!-- END PAGE BAR -->
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title"> Chỉnh sửa game </h1>
<!-- END PAGE TITLE-->
<?php $form = ActiveForm::begin(['action' => ['game/edit', 'id' => $id], 'options' => ['class' => 'form-horizontal form-row-seperated form']]);?>
<div class="row">
  <div class="col-md-12">
    <!-- BEGIN PROFILE SIDEBAR -->
    <div class="profile-sidebar">
      <!-- PORTLET MAIN -->
      <div class="portlet light">
        <!-- SIDEBAR USERPIC -->
        <?=$form->field($model, 'image_id', [
          'options' => ['tag' => false, 'class' => 'profile-userpic'],
          'template' => '{input}{hint}{error}'
        ])->widget(ImageInputWidget::className(), [
          'template' => '<div class="">{image}{input}</div><div class="profile-userbuttons list-separated profile-stat">{choose_button}{cancel_button}</div>',
          'imageOptions' => ['class' => 'img-responsive', 'size' => '300x300'],
          'chooseButtonOptions' => ['tag' => 'span', 'options' => ['class' => 'btn btn-circle green btn-sm']],
          'cancelButtonOptions' => ['tag' => 'button', 'options' => ['class' => 'btn btn-circle red btn-sm']]
        ])->label(false);?>

        <?=$form->field($model, 'status', [
          'options' => ['class' => 'list-separated profile-stat']
        ])->widget(RadioListInput::className(), [
          'items' => $model->getStatusList(),
          'options' => ['class' => 'mt-radio-list']
        ]);?>

        <?=$form->field($model, 'pin', [
          'options' => ['class' => 'list-separated profile-stat']
        ])->widget(CheckboxInput::className())->label(false);?>

        <?=$form->field($model, 'soldout', [
          'options' => ['class' => 'list-separated profile-stat']
        ])->widget(CheckboxInput::className())->label(false);?>

        <?=$form->field($model, 'average_speed', [
          'options' => ['class' => 'list-separated profile-stat']
        ])->textInput();?>

        <?=$form->field($model, 'number_supplier', [
          'options' => ['class' => 'list-separated profile-stat']
        ])->textInput()->label(sprintf('Số nhà cung cấp (Hiện có %s)', $numSupplier));?>

        <?=$form->field($model, 'remark', [
          'options' => ['class' => 'list-separated profile-stat']
        ])->textArea();?>

        <?=Html::submitButton(Yii::t('app', 'save'), ['class' => 'btn green']);?>
        <?=Html::resetButton(Yii::t('app', 'cancel'), ['class' => 'btn default']);?>
        <!-- END MENU -->
      </div>
      <!-- END PORTLET MAIN -->
    </div>
    <!-- END BEGIN PROFILE SIDEBAR -->
    <!-- BEGIN PROFILE CONTENT -->
    <div class="profile-content">
      <div class="row">
        <div class="col-md-12">
          <div class="portlet light ">
            <div class="portlet-title tabbable-line">
              <ul class="nav nav-tabs">
                <li class="active">
                  <a href="#main" data-toggle="tab"><?=Yii::t('app', 'main_content');?></a>
                </li>
                <li>
                  <a href="#tab_1_2" data-toggle="tab">Meta data</a>
                </li>
              </ul>
            </div>
            <div class="portlet-body">
              <div class="tab-content">
                <div class="tab-pane active" id="main">
                  <?=$form->field($model, 'title')->textInput();?>
                  <?=$form->field($model, 'original_price')->textInput();?>
                  <?=$form->field($model, 'pack')->textInput();?>
                  <?=$form->field($model, 'unit_name')->textInput();?>
                  <?=$form->field($model, 'content')->widget(TinyMce::className(), ['options' => ['rows' => 30]]);?>
                  <?=$form->field($model, 'google_ads')->widget(TinyMce::className(), ['options' => ['rows' => 30]]);?>
                </div>
                <div class="tab-pane" id="tab_1_2">
                  <?=$form->field($model, 'meta_title')->textInput();?>
                  <?=$form->field($model, 'meta_keyword')->textInput();?>
                  <?=$form->field($model, 'meta_description')->textInput();?>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- END PROFILE CONTENT -->
    </div>
  </div>
</div>
<?php ActiveForm::end()?>