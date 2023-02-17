<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use dosamigos\datepicker\DatePicker;
use dosamigos\datepicker\DateRangePicker;
use yii\web\JsExpression;
use common\widgets\CheckboxInput;
use common\components\helpers\TimeElapsed;
?>
<!-- BEGIN PAGE BAR -->
<div class="page-bar">
  <ul class="page-breadcrumb">
    <li>
      <a href="/"><?=Yii::t('app', 'home')?></a>
      <i class="fa fa-circle"></i>
    </li>
    <li>
      <a href="<?=Url::to(['lead-tracker/index'])?>">Quản lý lead tracker</a>
      <i class="fa fa-circle"></i>
    </li>
    <li>
      <span>Tạo / chỉnh sửa lead tracker</span>
    </li>
  </ul>
</div>
<!-- END PAGE BAR -->
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title">Tạo / chỉnh sửa lead tracker</h1>
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
              <li>
                <a href="#tab_question" data-toggle="tab"> Đánh giá</a>
              </li>
              <?php if ($comments): ?>
              <li>
                <a href="#tab_comment" data-toggle="tab"> Ghi chú</a>
              </li>
              <?php endif;?>
            </ul>
            <div class="tab-content">
              <div class="tab-pane active" id="tab_general">
                <div class="form-body">
                  <?=$form->field($model, 'name', [
                    'labelOptions' => ['class' => 'col-md-2 control-label'],
                    'inputOptions' => ['class' => 'form-control'],
                    'template' => '{label}<div class="col-md-6">{input}{hint}{error}</div>'
                  ])->textInput()->label('Lead Name');?>
                  <?=$form->field($model, 'link', [
                    'labelOptions' => ['class' => 'col-md-2 control-label'],
                    'inputOptions' => ['class' => 'form-control'],
                    'template' => '{label}<div class="col-md-6">{input}{hint}{error}</div>'
                  ])->textArea()->label('Link Account');?>
                  <?=$form->field($model, 'country_code', [
                    'labelOptions' => ['class' => 'col-md-2 control-label'],
                    'template' => '{label}<div class="col-md-6">{input}{hint}{error}</div>',
                    'inputOptions' => ['id' => 'country_code', 'class' => 'form-control phone-code']
                  ])->dropDownList($model->listCountries(), ['prompt' =>'Chọn quốc gia', 'options' => $model->listCountryAttributes()]);?>
                  <?=$form->field($model, 'phone', [
                    'labelOptions' => ['class' => 'col-md-2 control-label'],
                    'template' => '{label}<div class="col-md-6">{input}{hint}{error}</div>',
                    'inputOptions' => ['class' => 'form-control phone-number', 'id' => 'phone']
                  ])->textInput()->label('Điện thoại');?>
                  <?=$form->field($model, 'email', [
                    'labelOptions' => ['class' => 'col-md-2 control-label'],
                    'inputOptions' => ['class' => 'form-control'],
                    'template' => '{label}<div class="col-md-6">{input}{hint}{error}</div>'
                  ])->textInput()->label('Email');?>
                  <?=$form->field($model, 'channels', [
                    'labelOptions' => ['class' => 'col-md-2 control-label'],
                    'inputOptions' => ['class' => 'bs-select form-control'],
                    'template' => '{label}<div class="col-md-6">{input}{hint}{error}</div>'
                  ])->dropDownList($model->fetchChannels(), ['multiple' => 'multiple', 'prompt' => 'Chọn channel'])->label('Channel');?>
                  <?=$form->field($model, 'contacts', [
                    'labelOptions' => ['class' => 'col-md-2 control-label'],
                    'inputOptions' => ['class' => 'bs-select form-control'],
                    'template' => '{label}<div class="col-md-6">{input}{hint}{error}</div>'
                  ])->dropDownList($model->fetchContacts(), ['multiple' => 'multiple', 'prompt' => 'Chọn contact'])->label('Contact');?>
                  <?=$form->field($model, 'game_id', [
                    'labelOptions' => ['class' => 'col-md-2 control-label'],
                    'inputOptions' => ['class' => 'bs-select form-control'],
                    'template' => '{label}<div class="col-md-6">{input}{hint}{error}</div>'
                  ])->dropDownList($model->fetchGames(), ['prompt' => 'Chọn game'])->label('Game');?>
                  <?=$form->field($model, 'saler_id', [
                    'labelOptions' => ['class' => 'col-md-2 control-label'],
                    'inputOptions' => ['class' => 'bs-select form-control'],
                    'template' => '{label}<div class="col-md-6">{input}{hint}{error}</div>'
                  ])->dropDownList($model->fetchSalers(), ['prompt' => 'Chọn nhân viên sale'])->label('Nhân viên sale');?>

                </div>
              </div>
              <div class="tab-pane" id="tab_question">
                <div class="form-body">
                  <h3>Potential Lead</h3>
                  <?php foreach ($model->listPotentialLeadQuestions() as $id => $question) : ?>
                    <?=$form->field($model, "questions[$id]", [
                    'labelOptions' => ['class' => 'col-md-2 control-label'],
                    'template' => '{label}<div class="col-md-10">{input}{hint}{error}</div>'
                    ])->widget(CheckboxInput::className(), ['options' => ['label' => $question, 'checked' => in_array($id, $model->questions)]])->label('');?>
                  <?php endforeach ;?>
                  <hr/>
                  <h3>Target Lead</h3>
                  <?php foreach ($model->listTargetLeadQuestions() as $id => $question) : ?>
                    <?=$form->field($model, "questions[$id]", [
                    'labelOptions' => ['class' => 'col-md-2 control-label'],
                    'template' => '{label}<div class="col-md-10">{input}{hint}{error}</div>'
                    ])->widget(CheckboxInput::className(), ['options' => ['label' => $question, 'checked' => in_array($id, $model->questions)]])->label('');?>
                  <?php endforeach ;?>
                </div>
              </div>
              <?php if ($comments): ?>
              <div class="tab-pane" id="tab_comment">
                <div class="row">
                  <div class="col-md-12">
                    <div class="portlet light portlet-fit bordered">
                      <div class="portlet-body">
                        <div class="timeline" id="comment-list" style="max-height: 500px; overflow-y: scroll;">
                          <?php foreach ($comments as $comment) :?>
                            <div class="timeline-item" data-id="<?=$comment->id;?>">
                              <div class="timeline-badge">
                                <?php if ($comment->creator->avatarImage) :?>
                                <img class="timeline-badge-userpic" src="<?=$comment->creator->getAvatarUrl();?>"> 
                                <?php else : ?>
                                  <div class="timeline-icon">
                                    <i class="icon-user-following font-green-haze"></i>
                                  </div>
                                <?php endif; ?>
                              </div>
                              <div class="timeline-body">
                                <div class="timeline-body-arrow"> </div>
                                <div class="timeline-body-head">
                                  <div class="timeline-body-head-caption">
                                    <a href="javascript:;" class="timeline-body-title font-blue-madison"><?=$comment->creator->getName();?></a>
                                    <span class="timeline-body-time font-grey-cascade"><?=TimeElapsed::timeElapsed($comment->created_at);?></span>
                                  </div>
                                </div>
                                <div class="timeline-body-content">
                                  <span class="font-grey-cascade"><?=$comment->content;?></span>
                                </div>
                              </div>
                            </div>
                          <?php endforeach;?>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <?php endif;?>
            </div>
          </div>
        </div>
      </div>
      <?php ActiveForm::end()?>
  </div>
</div>

<?php
$script = <<< JS
$('#country_code').on('change', function(){
  $('#phone').val($(this).find('option:selected').attr('data-dialling'));
});
if (!$('#phone').val()) {
  $('#country_code').trigger('change');
}
JS;
$this->registerJs($script);
?>