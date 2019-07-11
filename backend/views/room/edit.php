<?php 
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use common\widgets\TinyMce;
use unclead\multipleinput\MultipleInput;
use common\widgets\ImageInputWidget;
use common\widgets\RadioListInput;
use common\widgets\MultipleImageInputWidget;
use common\models\Room;

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
      <a href="/"><?= Yii::t('app', 'home');?></a>
      <i class="fa fa-circle"></i>
    </li>
    <li>
      <a href="<?=Url::to(['realestate/index']);?>">Quản lý nhà cho thuê</a>
      <i class="fa fa-circle"></i>
    </li>
    <li>
      <a href="<?=Url::to(['room/index', 'id' => $realestate->id]);?>"><?=$realestate->title;?></a>
      <i class="fa fa-circle"></i>
    </li>
    <li>
      <span>Tạo phòng</span>
    </li>
  </ul>
</div>
<!-- END PAGE BAR -->
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title"> Tạo phòng </h1>
<!-- END PAGE TITLE-->
<?php $form = ActiveForm::begin(['class' => 'form-horizontal form-row-seperated form']);?>
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
          'template' => '<div class="profile-userpic">{image}{input}</div><div class="profile-userbuttons list-separated profile-stat">{choose_button}{cancel_button}</div>',
          'imageOptions' => ['class' => 'img-responsive'],
          'chooseButtonOptions' => ['tag' => 'span', 'options' => ['class' => 'btn btn-circle green btn-sm']],
          'cancelButtonOptions' => ['tag' => 'button', 'options' => ['class' => 'btn btn-circle red btn-sm']]
        ])->label(false);?>

        <?=$form->field($model, 'status', [
          'options' => ['class' => 'list-separated profile-stat']
        ])->widget(RadioListInput::className(), [
          'items' => Room::getStatusList(),
          'options' => ['class' => 'mt-radio-list']
        ]);?>

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
                  <a href="#tab_1_1" data-toggle="tab"><?= Yii::t('app', 'main_content');?></a>
                </li>
              </ul>
            </div>
            <div class="portlet-body">
              <div class="tab-content">
                <div class="tab-pane active" id="tab_1_1">
                  <?=$form->field($model, 'title')->textInput();?>
                  <?=$form->field($model, 'code')->textInput();?>
                  <?=$form->field($model, 'content')->widget(TinyMce::className(), ['options' => ['rows' => 10]]);?>
                  <?=$form->field($model, 'price')->textInput();?>
                  <hr/>
                  <div class="row">
                    <?php foreach ($roomServices as $roomService) : ?>
                      <?php $service = ArrayHelper::getValue($services, $roomService->realestate_service_id);?>
                      <?php if (!$service) continue;?>
                      <div class="col-lg-3">
                        <div class="input-group">
                          <span class="input-group-addon">
                            <input type="checkbox" value="1" <?=($roomService->isApply()) ? 'checked="checked"' : '';?> name="roomServices[<?=$roomService->realestate_service_id;?>][apply]"> <?=$service->title;?>
                          </span>
                          <input type="text" class="form-control" name="roomServices[<?=$roomService->realestate_service_id;?>][price]" value="<?=$roomService->price;?>">
                        </div><!-- /input-group -->
                      </div><!-- /.col-lg-3 -->
                    <?php endforeach;?>
                  </div><!-- /.row -->
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- END PROFILE CONTENT -->
  </div>
</div>
<?php ActiveForm::end() ?>
<?php
$script = <<< JS
JS;
$this->registerJs($script);
?>