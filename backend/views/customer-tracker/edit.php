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
      <a href="<?=Url::to(['customer-tracker/index'])?>">Quản lý customer tracker</a>
      <i class="fa fa-circle"></i>
    </li>
    <li>
      <span>Tạo / chỉnh sửa customer tracker</span>
    </li>
  </ul>
</div>
<!-- END PAGE BAR -->
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title">Tạo / chỉnh sửa customer tracker</h1>
<!-- END PAGE TITLE-->
<div class="row">
  <div class="col-md-12">
    <?php $form = ActiveForm::begin(['options' => ['class' => 'form-horizontal form-row-seperated']]);?>
      <div class="portlet">
        <div class="portlet-title">
          <div class="actions btn-set">
            <a href="<?=Url::to(['customer-tracker/view', 'id' => $model->id]);?>" class="btn default">
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
                <a href="#tab_comment" data-toggle="tab"> Ghi chú</a>
              </li>
              <li>
                <a href="#tab_survey" data-toggle="tab"> Khảo sát</a>
              </li>
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
                  <?=$form->field($model, 'sale_target', [
                    'labelOptions' => ['class' => 'col-md-2 control-label'],
                    'inputOptions' => ['class' => 'form-control'],
                    'template' => '{label}<div class="col-md-6">{input}{hint}{error}</div>'
                  ])->textInput()->label('Monthly Sale Target');?>
                  <?=$form->field($model, 'customer_tracker_status', [
                    'labelOptions' => ['class' => 'col-md-2 control-label'],
                    'inputOptions' => ['class' => 'bs-select form-control'],
                    'template' => '{label}<div class="col-md-6">{input}{hint}{error}</div>'
                  ])->dropDownList($model->fetchCustomerStatus(), ['prompt' => '--Chọn--'])->label('Status');?>

                </div>
              </div>
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
              <div class="tab-pane" id="tab_survey">
                <div class="row">
                  <div class="col-md-3 col-sm-3 col-xs-3">
                    <ul class="nav nav-tabs tabs-left">
                      <li class="active">
                        <a href="#tab_6_1" data-toggle="tab"> Home </a>
                      </li>
                      <li>
                        <a href="#tab_6_2" data-toggle="tab"> Profile </a>
                      </li>
                    </ul>
                  </div>
                  <div class="col-md-9 col-sm-9 col-xs-9">
                    <div class="tab-content">
                      <div class="tab-pane active" id="tab_6_1">
                        <div class="form-body">
                          <div class="form-group">
                            <label class="control-label col-md-6">First Name</label>
                            <div class="col-md-6">
                              <input type="text" placeholder="small" class="form-control">
                              <span class="help-block"> This is inline help </span>
                            </div>
                          </div>
                          <div class="form-group">
                            <label class="control-label col-md-6">Last Name</label>
                            <div class="col-md-6">
                              <input type="text" placeholder="medium" class="form-control">
                              <span class="help-block"> This is inline help </span>
                            </div>
                          </div>
                          <div class="form-group">
                            <label class="control-label col-md-6">Gender</label>
                            <div class="col-md-6">
                              <select class="form-control">
                                <option value="">Male</option>
                                <option value="">Female</option>
                              </select>
                              <span class="help-block"> Select your gender. </span>
                            </div>
                          </div>
                          <div class="form-group">
                            <label class="control-label col-md-6">Date of Birth</label>
                            <div class="col-md-6">
                              <input type="text" class="form-control" placeholder="dd/mm/yyyy"> 
                            </div>
                          </div>
                          <div class="form-group">
                            <label class="control-label col-md-6">Category</label>
                            <div class="col-md-6">
                              <select class="form-control">
                                <option value="Category 1">Category 1</option>
                                <option value="Category 2">Category 2</option>
                                <option value="Category 3">Category 5</option>
                                <option value="Category 4">Category 4</option>
                              </select>
                            </div>
                          </div>
                          <div class="form-group">
                            <label class="control-label col-md-6">Multi-Value Select</label>
                            <div class="col-md-6">
                              <select class="form-control" multiple="">
                                <optgroup label="NFC EAST">
                                  <option>Dallas Cowboys</option>
                                  <option>New York Giants</option>
                                  <option>Philadelphia Eagles</option>
                                  <option>Washington Redskins</option>
                                </optgroup>
                                <optgroup label="NFC NORTH">
                                  <option>Chicago Bears</option>
                                  <option>Detroit Lions</option>
                                  <option>Green Bay Packers</option>
                                  <option>Minnesota Vikings</option>
                                </optgroup>
                                <optgroup label="NFC SOUTH">
                                  <option>Atlanta Falcons</option>
                                  <option>Carolina Panthers</option>
                                  <option>New Orleans Saints</option>
                                  <option>Tampa Bay Buccaneers</option>
                                </optgroup>
                                <optgroup label="NFC WEST">
                                  <option>Arizona Cardinals</option>
                                  <option>St. Louis Rams</option>
                                  <option>San Francisco 49ers</option>
                                  <option>Seattle Seahawks</option>
                                </optgroup>
                                <optgroup label="AFC EAST">
                                  <option>Buffalo Bills</option>
                                  <option>Miami Dolphins</option>
                                  <option>New England Patriots</option>
                                  <option>New York Jets</option>
                                </optgroup>
                                <optgroup label="AFC NORTH">
                                  <option>Baltimore Ravens</option>
                                  <option>Cincinnati Bengals</option>
                                  <option>Cleveland Browns</option>
                                  <option>Pittsburgh Steelers</option>
                                </optgroup>
                                <optgroup label="AFC SOUTH">
                                  <option>Houston Texans</option>
                                  <option>Indianapolis Colts</option>
                                  <option>Jacksonville Jaguars</option>
                                  <option>Tennessee Titans</option>
                                </optgroup>
                                <optgroup label="AFC WEST">
                                  <option>Denver Broncos</option>
                                  <option>Kansas City Chiefs</option>
                                  <option>Oakland Raiders</option>
                                  <option>San Diego Chargers</option>
                                </optgroup>
                              </select>
                            </div>
                          </div>
                          <div class="form-group">
                            <label class="control-label col-md-6">Membership Membership Membership Membership Membership Membership Membership</label>
                            <div class="col-md-6">
                              <div class="radio-list">
                                <label>
                                <input type="radio" name="optionsRadios2" value="option1"> Free </label>
                                <label>
                                <input type="radio" name="optionsRadios2" value="option2" checked=""> Professional </label>
                              </div>
                            </div>
                          </div>
                          <div class="form-group">
                            <label class="control-label col-md-6">Street</label>
                            <div class="col-md-6">
                              <input type="text" class="form-control"> 
                            </div>
                          </div>
                          <div class="form-group">
                            <label class="control-label col-md-6">City</label>
                            <div class="col-md-6">
                              <input type="text" class="form-control"> 
                            </div>
                          </div>
                          <div class="form-group">
                            <label class="control-label col-md-6">State</label>
                            <div class="col-md-6">
                              <input type="text" class="form-control"> 
                            </div>
                          </div>
                          <div class="form-group">
                            <label class="control-label col-md-6">Post Code</label>
                            <div class="col-md-6">
                              <input type="text" class="form-control"> 
                            </div>
                          </div>
                          <div class="form-group last">
                            <label class="control-label col-md-6">Country</label>
                            <div class="col-md-6">
                              <select class="form-control"> </select>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="tab-pane fade" id="tab_6_2">
                        form 2
                      </div>
                    </div>
                  </div>
                </div>
              </div>
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