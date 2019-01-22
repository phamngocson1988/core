<?php 
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use common\widgets\TinyMce;
use unclead\multipleinput\MultipleInput;
use common\widgets\ImageInputWidget;
use common\widgets\RadioListInput;
use dosamigos\google\maps\LatLng;
use dosamigos\google\maps\Map;
use dosamigos\google\maps\overlays\InfoWindow;
use dosamigos\google\maps\overlays\Marker;
use dosamigos\google\maps\Event;

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
      <a href="<?=Url::to(['realestate/index']);?>"><?= Yii::t('app', 'manage_realestates');?></a>
      <i class="fa fa-circle"></i>
    </li>
    <li>
      <span><?= Yii::t('app', 'create_realestate');?></span>
    </li>
  </ul>
</div>
<!-- END PAGE BAR -->
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title"> <?= Yii::t('app', 'create_realestate');?> </h1>
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
          'items' => $model->getStatusList(),
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
                <li>
                  <a href="#tab_1_2" data-toggle="tab"><?= Yii::t('app', 'meta');?></a>
                </li>
                <li>
                  <a href="#tab_1_3" data-toggle="tab"><?= Yii::t('app', 'feature');?></a>
                </li>
                <li>
                  <a href="#tab_1_4" data-toggle="tab"><?= Yii::t('app', 'address');?></a>
                </li>
              </ul>
            </div>
            <div class="portlet-body">
              <div class="tab-content">
                <div class="tab-pane active" id="tab_1_1">
                  <?=$form->field($model, 'title')->textInput();?>
                  <?=$form->field($model, 'excerpt')->textarea();?>
                  <?=$form->field($model, 'content')->widget(TinyMce::className(), ['options' => ['rows' => 10]]);?>
                </div>
                <div class="tab-pane" id="tab_1_2">
                  <?=$form->field($model, 'meta_title')->textInput();?>
                  <?=$form->field($model, 'meta_keyword')->textInput();?>
                  <?=$form->field($model, 'meta_description')->textarea(['rows' => '5']);?>
                </div>
                <div class="tab-pane" id="tab_1_3">
                  <div class="row">
                    <div class="col-lg-3 col-md-3 col-sm-4 col-xs-12">
                      <?=$form->field($model, 'direction')->dropDownList($model->getDirectionList(), ['prompt' => Yii::t('app', 'choose')]);?>
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-4 col-xs-12">
                      <?=$form->field($model, 'area')->textInput();?>
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-4 col-xs-12">
                      <?=$form->field($model, 'price')->textInput();?>
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-4 col-xs-12">
                      <?=$form->field($model, 'num_bed')->textInput();?>
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-4 col-xs-12">
                      <?=$form->field($model, 'num_toilet')->textInput();?>
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-4 col-xs-12">
                      <?=$form->field($model, 'deposit')->textInput();?>
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-4 col-xs-12">
                      <?=$form->field($model, 'deposit_duration')->textInput();?>
                    </div>
                  </div>

                </div>
                <div class="tab-pane" id="tab_1_4">
                  <div class="col-lg-3 col-md-3 col-sm-4 col-xs-12">
                    <?=$form->field($model, 'address')->textInput();?>
                  </div>
                  <div class="col-lg-3 col-md-3 col-sm-4 col-xs-12">
                  <?=$form->field($model, 'latitude')->textInput(['id' => 'latitude', 'readonly' => true]);?>
                  </div>
                  <div class="col-lg-3 col-md-3 col-sm-4 col-xs-12">
                  <?=$form->field($model, 'longitude')->textInput(['id' => 'longitude', 'readonly' => true]);?>
                  </div>
                  <?php 
                  $coord = new LatLng(['lat' => 39.720089311812094, 'lng' => 2.91165944519042]);
                  $marker = new Marker(['position' => $coord]);
                  $marker->setName('marker');

                  $event = new Event([
                    'trigger' => 'click',
                    'js' => "$('#latitude').val(event.latLng.lat());
                      $('#longitude').val(event.latLng.lng());
                      marker.setPosition({lat: event.latLng.lat(), lng: event.latLng.lng()})
                    ",
                  ]);

                  $map = new Map([
                      'center' => $coord,
                      'zoom' => 14,
                      'width' => '100%',
                      'containerOptions' => ['id' => 'google-map']
                  ]);
                  $map->addOverlay($marker);
                  $map->addEvent($event);
                  echo $map->display();
                  ?>
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