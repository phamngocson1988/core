<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use dosamigos\datepicker\DatePicker;
use dosamigos\datepicker\DateRangePicker;
use yii\web\JsExpression;
use common\widgets\CheckboxInput;
use common\components\helpers\TimeElapsed;
use common\models\LeadTrackerSurvey;
use common\models\User;
$this->registerJsFile('https://cdn.jsdelivr.net/npm/vue@2/dist/vue.js', ['depends' => ['\yii\web\JqueryAsset']]);
$this->registerJsFile('https://unpkg.com/axios/dist/axios.min.js', ['depends' => ['\yii\web\JqueryAsset']]);

$salerTeamIds = Yii::$app->authManager->getUserIdsByRole('saler');
$salerTeamManagerIds = Yii::$app->authManager->getUserIdsByRole('sale_manager');
$salerTeamIds = array_merge($salerTeamIds, $salerTeamManagerIds);
$salerTeamIds = array_unique($salerTeamIds);
$salerTeamObjects = User::findAll($salerTeamIds);
$salerTeams = ArrayHelper::map($salerTeamObjects, 'id', 'name');
$salerTeamsJson = json_encode($salerTeams);
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
                  ])->dropDownList($model->fetchGames(), ['multiple' => 'multiple', 'prompt' => 'Chọn game'])->label('Game');?>
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
                      <li v-for="(questionSet, index) in tabs" :class="index || 'active'">
                        <a :href="`#tab_6_${index}`" data-toggle="tab"> {{ questionSet.label }} </a>
                      </li>
                    </ul>
                  </div>
                  <div class="col-md-9 col-sm-9 col-xs-9">
                    <div class="tab-content">
                      <div class="tab-pane" v-for="(questionSet, index) in tabs" :id="`tab_6_${index}`" :class="index || 'active'">
                        <div class="form-body">   
                          <template v-for="question in questionSet.questions">
                            <template v-if="question.type === 'text'">
                              <text-control :survey-id=question.survey_id :id="question.id" :question="question.question" :answer="question.answer" @onupdateanswer="onUpdateAnswer"/>
                            </template>    
                            <template v-if="question.type === 'date'">
                              <date-control :survey-id=question.survey_id :id="question.id" :question="question.question" :answer="question.answer" @onupdateanswer="onUpdateAnswer"/>
                            </template>    
                            <template v-else-if="question.type === 'select'">
                              <select-control :survey-id=question.survey_id :id="question.id" :question="question.question" :answer="question.answer" :options="question.options" @onupdateanswer="onUpdateAnswer"/>
                            </template>
                            <template v-else-if="question.type === 'select_am'">
                              <select-control :survey-id=question.survey_id :id="question.id" :question="question.question" :answer="question.answer" :options="amList" @onupdateanswer="onUpdateAnswer"/>
                            </template>
                            <template v-else-if="question.type === 'checkbox'">
                              <checkbox-control :survey-id=question.survey_id :id="question.id" :question="question.question" :answer="question.answer" :options="question.options" @onupdateanswer="onUpdateAnswer"/>
                            </template>
                            <template v-else-if="question.type === 'radio'">
                              <radio-control :survey-id=question.survey_id :id="question.id" :question="question.question" :answer="question.answer" :options="question.options" @onupdateanswer="onUpdateAnswer"/>
                            </template>                      
                          </template> 
                                 
                        </div>
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
$questions = [];
$answers = $model->fetchAllAnswers();
foreach (LeadTrackerSurvey::customerTypeLabels() as $type => $label) {
  $questionsByType = [
    'type' => $type,
    'label' => $label,
    'questions' => []
  ];
  foreach ($model->fetchSurveys($type) as $survey) {
    foreach ($survey->questions as $question) {
      $questionsByType['questions'][] = [
        'id' => $question->id,
        'type' => $question->type,
        'question' => $question->question,
        'answer' => isset($answers[$question->id]) && $answers[$question->id] ? $answers[$question->id]->answer : '',
        'options' => $question->getOptions(),
        'survey_id' => $question->survey_id,
      ];
    }
  }
  $questions[] = $questionsByType;
}

$questionsJson = json_encode($questions);

$updateSurveyAnswerUrl = Url::to(['customer-tracker/update-survey-answer', 'id' => $model->id], true);
$csrfTokenName = Yii::$app->request->csrfParam;
$csrfToken = Yii::$app->request->csrfToken;
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

<?php
$script = <<< JS
// Renderer for each to do item (accepts one item as props)
Vue.component("textControl", {
  props: ["id", "question", "answer", "surveyId"],
  data() {
    return {
      value: this.answer
    }
  },
  methods: {
    onChange (event) {
      this.\$emit('onupdateanswer', { surveyId: this.surveyId, id: this.id, key: this.value, value: this.value });
    }
  },
  template: `<div class="form-group">
              <label class="control-label col-md-6">{{question}}</label>
              <div class="col-md-6">
                <input type="text" @blur="onChange" class="form-control" v-model="value">
              </div>
            </div>`,
});
Vue.component("dateControl", {
  props: ["id", "question", "answer", "surveyId"],
  data() {
    return {
      value: this.answer
    }
  },
  methods: {
    onChange (event) {
      this.\$emit('onupdateanswer', { surveyId: this.surveyId, id: this.id, key: this.value, value: this.value });
    }
  },
  template: `<div class="form-group">
              <label class="control-label col-md-6">{{question}}</label>
              <div class="col-md-6">
                <input type="date" @blur="onChange" class="form-control" v-model="value">
              </div>
            </div>`,
});
Vue.component("selectControl", {
  props: ["id", "question", "answer", "options", "surveyId"],
  data() {
    return {
      value: this.answer
    }
  },
  methods: {
    onChange (event) {
      this.\$emit('onupdateanswer', { surveyId: this.surveyId, id: this.id, key: this.value, value: this.options[this.value] });
    }
  },
  template: `<div class="form-group">
              <label class="control-label col-md-6">{{question}}</label>
              <div class="col-md-6">
                <select class="form-control" v-model="value" @change="onChange">
                  <option value="">---Select---</option>
                  <option v-for="optionKey in Object.keys(options)" v-bind:value="optionKey">{{options[optionKey]}}</option>
                </select>
              </div>
            </div>`,
});
Vue.component("checkboxControl", {
  props: ["id", "question", "answer", "options", "surveyId"],
  data() {
    return {
      value: this.answer ? this.answer.split(',') : []
    }
  },
  methods: {
    onChange (event) {
      if (event.target.checked) {
        if (!this.value.includes(event.target.value)) {
          this.value.push(event.target.value);
        }
      } else {
        this.value = this.value.filter(x => x && x !== event.target.value);
      }
      const textValues = this.value.map(key => this.options[key]);
      this.\$emit('onupdateanswer', { surveyId: this.surveyId, id: this.id, key: this.value.join(','), value: textValues.join(', ') });
    }
  },
  template: `<div class="form-group">
              <label class="col-md-6 control-label">{{question}}</label>
              <div class="col-md-6">
                <div class="mt-checkbox-list">
                  <label class="mt-checkbox" v-for="optionKey in Object.keys(options)">
                    <input type="checkbox" :checked="value.includes(optionKey)" :value="optionKey" @change="onChange"> {{ options[optionKey] }}
                    <span></span>
                  </label>
                </div>
              </div>
            </div>`,
});
Vue.component("radioControl", {
  props: ["id", "question", "answer", "options", "surveyId"],
  data() {
    return {
      value: this.answer
    }
  },
  methods: {
    onChange (event) {
      this.\$emit('onupdateanswer', { surveyId: this.surveyId, id: this.id, key: event.target.value, value: this.options[event.target.value] });
    }
  },
  template: `<div class="form-group">
              <label class="col-md-6 control-label">{{ question }}</label>
              <div class="col-md-6">
                <div class="mt-radio-list">
                  <label class="mt-radio" v-for="optionKey in Object.keys(options)">
                    <input type="radio" :name="id" :value="optionKey" :checked="value === optionKey" @change="onChange"> {{ options[optionKey]}}
                    <span></span>
                  </label>
                </div>
              </div>
          </div>`,
});

var app = new Vue({
  el: '#tab_survey',
  data: {
    tabs: $questionsJson,
    amList: $salerTeamsJson
  },
  methods: {
    onUpdateAnswer(data) {
      console.log('onUpdateAnswer', data);
      axios.post('$updateSurveyAnswerUrl', { 
        lead_tracker_id: $model->id,
        survey_id: data.surveyId, 
        question_id: data.id, 
        answer: data.key, 
        value: data.value, 
        '$csrfTokenName': '$csrfToken' 
      }, {
        headers: {
          'Content-Type': 'application/json'
        }
      });
    }
  }
});
JS;
$this->registerJs($script);
?>