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
use common\widgets\MultipleImageInputWidget;
use common\models\Realestate;

$this->registerCssFile('@web/vendor/assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.css', ['depends' => [\backend\assets\AppAsset::className()]]);
$this->registerCssFile('@web/vendor/assets/pages/css/profile.min.css', ['depends' => [\backend\assets\AppAsset::className()]]);
$this->registerJsFile('@web/vendor/assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile('@web/vendor/assets/global/plugins/jquery.sparkline.min.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile('@web/vendor/assets/pages/scripts/profile.min.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile('@web/js/jquery.number.min.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
?>
<style>
.form-horizontal .form-group {
    margin-left: 0px;
    margin-right: 0px;
}
</style>
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
      <span>Tạo mới</span>
    </li>
  </ul>
</div>
<!-- END PAGE BAR -->
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title"> Tạo mới </h1>
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
                  <a href="#tab_1_2" data-toggle="tab">Dịch vụ</a>
                </li>
                <li>
                  <a href="#tab_1_3" data-toggle="tab"><?= Yii::t('app', 'gallery');?></a>
                </li>
              </ul>
            </div>
            <div class="portlet-body">
              <div class="tab-content">
                <div class="tab-pane active form-horizontal form-row-seperated" id="tab_1_1">
                  <?=$form->field($model, 'title')->textInput();?>
                  <?=$form->field($model, 'address')->textInput();?>
                  <?=$form->field($model, 'content')->widget(TinyMce::className(), ['options' => ['rows' => 10]]);?>

                  <?php 
                  /*$coord = new LatLng(['lat' => 10.7795, 'lng' => 106.692]);
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
                  $map->setName('map');
                  $map->addOverlay($marker);
                  $map->addEvent($event);
                  echo $map->display();*/
                  ?>

                  <hr><!-- Electric -->
                  <?php 
                  $electrics = Realestate::getElectricHandlers();
                  $electricList = [];
                  foreach ($electrics as $identifier => $params) {
                    $electricList[$identifier] = $params['title'];
                  }
                  ?>
                  <?=$form->field($model, 'electric_name', [
                    'labelOptions' => ['class' => 'col-md-2 control-label'],
                    'inputOptions' => ['class' => 'form-control', 'id' => 'electrics'],
                    'template' => '{label}<div class="col-md-6">{input}{hint}{error}</div>',
                  ])->dropDownList($electricList, ['prompt' => 'Chọn cách tính giá điện'])->label('Giá điện');?>
                  
                  <div id="electric-container">
                  <?php foreach ($electrics as $identifier => $params) {
                    $electric = Yii::createObject($params);
                    $content = '';
                    foreach ($electric->safeAttributes() as $attr) {
                      $content .= $electric->render($form, $attr, [
                        'options' => ['class' => 'form-group electric', 'id' => $identifier],
                        'labelOptions' => ['class' => 'col-md-2 control-label'],
                        'template' => '{label}<div class="col-md-6">{input}{hint}{error}</div>'
                      ]);
                    }
                    echo Html::tag('div', $content, ['class' => 'electric-item', 'id' => $identifier]);
                  } ?>
                  </div>

                  <hr><!-- Water -->
                  <?php 
                  $waters = Realestate::getWaterHandlers();
                  $waterList = [];
                  foreach ($waters as $identifier => $params) {
                    $waterList[$identifier] = $params['title'];
                  }
                  ?>
                  <?=$form->field($model, 'water_name', [
                    'labelOptions' => ['class' => 'col-md-2 control-label'],
                    'inputOptions' => ['class' => 'form-control', 'id' => 'waters'],
                    'template' => '{label}<div class="col-md-6">{input}{hint}{error}</div>',
                  ])->dropDownList($waterList, ['prompt' => 'Chọn cách tính giá nước'])->label('Giá nước');?>
                  
                  <div id="water-container">
                  <?php foreach ($waters as $identifier => $params) {
                    $water = Yii::createObject($params);
                    $content = '';
                    foreach ($water->safeAttributes() as $attr) {
                      $content .= $water->render($form, $attr, [
                        'options' => ['class' => 'form-group water', 'id' => $identifier],
                        'labelOptions' => ['class' => 'col-md-2 control-label'],
                        'template' => '{label}<div class="col-md-6">{input}{hint}{error}</div>'
                      ]);
                    }
                    echo Html::tag('div', $content, ['class' => 'water-item', 'id' => $identifier]);
                  } ?>
                  </div>
                </div>
                <div class="tab-pane" id="tab_1_2">
                <?=$form->field($model, 'realestate_services')->widget(MultipleInput::className(), [
                    'columns' => [
                        [
                            'name'  => 'service_id',
                            'type'  => 'dropDownList',
                            'title' => 'Dịch vụ hiện có',
                            'items' => $services,
                        ],
                        [
                            'name'  => 'price',
                            'title' => 'Giá tiền',
                            'enableError' => true,
                            'options' => [
                                'type' => 'number',
                                'class' => 'input-priority',
                            ]
                        ]
                    ]
                ])->label(false);?>
                </div>
                <div class="tab-pane" id="tab_1_3">
                  <?=$form->field($model, 'gallery', [
                    'template' => '{input}{hint}{error}'
                  ])->widget(MultipleImageInputWidget::className(), [
                  ])->label(false);?>
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
<template id="electric-template" style="display: none">
</template>
<template id="water-template" style="display: none">
</template>
<?php
$script = <<< JS
hideAllElectricItems();
function hideAllElectricItems() {
  $( "#electric-container .electric-item" ).appendTo( $( "#electric-template" ) );
}
$('#electrics').on('change', function(){
  var val = $(this).val();
  hideAllElectricItems();
  if (!val) return;
  $('#' + val).appendTo( $( "#electric-container" ) );
});

hideAllWaterItems();
function hideAllWaterItems() {
  $( "#water-container .water-item" ).appendTo( $( "#water-template" ) );
}
$('#waters').on('change', function(){
  var val = $(this).val();
  hideAllWaterItems();
  if (!val) return;
  $('#' + val).appendTo( $( "#water-container" ) );
});
JS;
$this->registerJs($script);
?>