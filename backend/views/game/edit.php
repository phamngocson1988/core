<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\widgets\TinyMce;
use yii\widgets\Pjax;
use yii\helpers\Url;
use unclead\multipleinput\MultipleInput;
use backend\models\Game;
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

$user = Yii::$app->user;
$canShowPrice = $user->can('orderteam');
?>

<!-- BEGIN PAGE BAR -->
<div class="page-bar">
  <ul class="page-breadcrumb">
    <li>
      <a href="<?=Url::to(['game/edit', 'id' => $id]);?>">Cập nhật game</a>
      <i class="fa fa-circle"></i>
    </li>
    <?php if ($canShowPrice) :?>
    <li>
      <a href="<?=Url::to(['game/suppliers', 'id' => $id]);?>">Giá nhà cung cấp</a>
    </li>
    <?php endif;?>
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
          'items' => [Game::STATUS_INVISIBLE => 'Tạm ẩn', Game::STATUS_VISIBLE => 'Hiển thị'],
          'options' => ['class' => 'mt-radio-list']
        ]);?>

        <?=$form->field($model, 'pin', [
          'options' => ['class' => 'list-separated profile-stat']
        ])->widget(CheckboxInput::className())->label(false);?>

        <?=$form->field($model, 'soldout', [
          'options' => ['class' => 'list-separated profile-stat']
        ])->widget(CheckboxInput::className())->label(false);?>

        <?=$form->field($model, 'promotion_info', [
          'options' => ['class' => 'list-separated profile-stat']
        ])->textInput()->label('Thông tin khuyến mãi');?>

        <?=$form->field($model, 'event_info', [
          'options' => ['class' => 'list-separated profile-stat']
        ])->textInput()->label('Thông tin sự kiện');?>

        <?php if (Yii::$app->user->can('orderteam')) : ?>
        <?=$form->field($model, 'average_speed', [
          'options' => ['class' => 'list-separated profile-stat']
        ])->textInput();?>

        <?=$form->field($model, 'number_supplier', [
          'options' => ['class' => 'list-separated profile-stat']
        ])->textInput()->label(sprintf('Số nhà cung cấp (Hiện có %s)', $numSupplier));?>

        <?=$form->field($model, 'remark', [
          'options' => ['class' => 'list-separated profile-stat'],
          'inputOptions' => ['style' => 'resize: vertical', 'class' => 'form-control']
        ])->textArea();?>
        <?php endif;?>

        <?=Html::submitButton(Yii::t('app', 'save'), ['class' => 'btn green']);?>
        <?=Html::a(Yii::t('app', 'cancel'), $back, ['class' => 'btn default']);?>
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
                <li>
                  <a href="#tab_1_3" data-toggle="tab">Danh mục</a>
                </li>
                <li>
                  <a href="#tab_1_4" data-toggle="tab">Đặc tính</a>
                </li>
                <li>
                  <a href="#tab_1_5" data-toggle="tab">Nhóm game</a>
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
                <div class="tab-pane" id="tab_1_3">
                  <?=$form->field($model, 'categories', [
                    'labelOptions' => ['class' => 'col-md-2 control-label'],
                    'template' => '{label}<div class="col-md-10">{input}{hint}{error}</div>'
                  ])->checkboxList($model->getCategories('%s<span></span>'), [
                    'class' => 'md-checkbox-list', 
                    'encode' => false , 
                    'itemOptions' => ['labelOptions' => ['class'=>'mt-checkbox', 'style' => 'display: block']]
                  ])->label('Categories');?>
                </div>
                <div class="tab-pane" id="tab_1_4">
                  <?=$form->field($model, 'hot_deal', [
                    'labelOptions' => ['class' => 'col-md-2 control-label'],
                    'template' => '{label}<div class="col-md-10">{input}{hint}{error}</div>'
                  ])->widget(CheckboxInput::className())->label('');?>

                  <?=$form->field($model, 'new_trending', [
                    'labelOptions' => ['class' => 'col-md-2 control-label'],
                    'template' => '{label}<div class="col-md-10">{input}{hint}{error}</div>'
                  ])->widget(CheckboxInput::className())->label('');?>

                  <?=$form->field($model, 'top_grossing', [
                    'labelOptions' => ['class' => 'col-md-2 control-label'],
                    'template' => '{label}<div class="col-md-10">{input}{hint}{error}</div>'
                  ])->widget(CheckboxInput::className())->label('');?>

                  <?=$form->field($model, 'back_to_stock', [
                    'labelOptions' => ['class' => 'col-md-2 control-label'],
                    'template' => '{label}<div class="col-md-10">{input}{hint}{error}</div>'
                  ])->widget(CheckboxInput::className())->label('');?>
                </div>
                <div class="tab-pane" id="tab_1_5">
                  <?=$form->field($model, 'group_id', [
                    'labelOptions' => ['class' => 'col-md-2 control-label'],
                    'template' => '{label}<div class="col-md-10">{input}{hint}{error}</div>',
                    'inputOptions' => ['class' => 'form-control', 'id' => 'group_id']
                  ])->dropdownList($model->getGroups(), [
                    'prompt' => 'Chọn nhóm game',
                    'options'=> $model->getGroupData()
                  ])->label('Nhóm game');?>

                  <?=$form->field($model, 'method', [
                    'labelOptions' => ['class' => 'col-md-2 control-label'],
                    'template' => '{label}<div class="col-md-10">{input}{hint}{error}</div>',
                    'inputOptions' => ['class' => 'form-control', 'id' => 'method']
                  ])->dropdownList($model->getMethods(), ['prompt' => 'Chọn phương thức'])->label('Phương thức nạp');?>

                  <?=$form->field($model, 'version', [
                    'labelOptions' => ['class' => 'col-md-2 control-label'],
                    'template' => '{label}<div class="col-md-10">{input}{hint}{error}</div>',
                    'inputOptions' => ['class' => 'form-control', 'id' => 'version']
                  ])->dropdownList($model->getVersions(), ['prompt' => 'Chọn version'])->label('Version');?>

                  <?=$form->field($model, 'package', [
                    'labelOptions' => ['class' => 'col-md-2 control-label'],
                    'template' => '{label}<div class="col-md-10">{input}{hint}{error}</div>',
                    'inputOptions' => ['class' => 'form-control', 'id' => 'package']
                  ])->dropdownList($model->getPackages(), ['prompt' => 'Chọn loại gói'])->label('Loại gói');?>

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

<?php
$script = <<< JS
$("#group_id").on('change', function(){
  var method = $(this).find('option:selected').data('method');
  var package = $(this).find('option:selected').data('package');
  var version = $(this).find('option:selected').data('version');
  $('#method').html(buildOptions(method));
  $('#package').html(buildOptions(package));
  $('#version').html(buildOptions(version));
});

function buildOptions(obj, sel) {
  console.log('buildOptions', obj);
  html = '';
  for (var index in obj) {
    var item = obj[index];
    var selected = sel == index ? 'selected' : '';
    html += '<option value="'+index+'" '+selected+'>'+item+'</option>';
  };
  return html;
}
JS;
$this->registerJs($script);
?>