<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use dosamigos\datepicker\DatePicker;
use dosamigos\datepicker\DateRangePicker;
use yii\web\JsExpression;
use common\widgets\CheckboxInput;
use common\components\helpers\TimeElapsed;
$this->registerJsFile('https://cdn.jsdelivr.net/npm/vue@2/dist/vue.js', ['depends' => ['\yii\web\JqueryAsset']]);
$this->registerJsFile('https://unpkg.com/axios/dist/axios.min.js', ['depends' => ['\yii\web\JqueryAsset']]);
?>
<!-- BEGIN PAGE BAR -->
<div class="page-bar">
  <ul class="page-breadcrumb">
    <li>
      <a href="/"><?=Yii::t('app', 'home')?></a>
      <i class="fa fa-circle"></i>
    </li>
    <li>
      <a href="<?=Url::to(['lead-tracker-question/index'])?>">Quản lý lead tracker question</a>
      <i class="fa fa-circle"></i>
    </li>
    <li>
      <span>Tạo / chỉnh sửa lead tracker question</span>
    </li>
  </ul>
</div>
<!-- END PAGE BAR -->
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title">Tạo / chỉnh sửa lead tracker question</h1>
<!-- END PAGE TITLE-->
<div class="row">
  <div class="col-md-12">
    <?php $form = ActiveForm::begin(['options' => ['class' => 'form-horizontal form-row-seperated']]);?>
      <div class="portlet">
        <div class="portlet-title">
          <div class="actions btn-set">
            <a href="<?=Url::to(['lead-tracker-question/index']);?>" class="btn default">
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
                <div class="form-body" id="vue-app">
                  <?=$form->field($model, 'question', [
                    'labelOptions' => ['class' => 'col-md-2 control-label'],
                    'inputOptions' => ['class' => 'form-control', 'v-model' => 'question'],
                    'template' => '{label}<div class="col-md-6">{input}{hint}{error}</div>'
                  ])->textInput()->label('Câu hỏi');?>
                  <?=$form->field($model, 'type', [
                    'labelOptions' => ['class' => 'col-md-2 control-label'],
                    'template' => '{label}<div class="col-md-6">{input}{hint}{error}</div>',
                    'inputOptions' => ['class' => 'form-control', 'v-model' => 'type']
                  ])->dropDownList($model->fetchTypes());?>
                  <div class="form-group" v-if="this.isShowOptions()">
                    <label class="col-md-2 control-label">Options</label>
                    <div class="col-md-6">
                      <div class="input-group" style="margin-bottom: 10px" v-for="op in options" :key="op.id">
                          <input type="text" class="form-control" :blur="addOptionValue(op.id)">
                          <span class="input-group-btn">
                              <button class="btn red" :disabled="isDisableDeleteOption()  ? '' : disabled" type="button" @click="removeOption(op.id)">Delete</button>
                          </span>
                      </div>
                      <a href="javascript:;" class="btn btn-info" @click="addOption()"><i class="fa fa-plus"></i> Add</a>
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
var app = new Vue({
  el: '#vue-app',
  data: {
      question: '$model->question',
      type: '$model->type' || 'text',
      options: [{ id: '1', value: 'Option 1'}]
  },
  watch: {
    type() {
      console.log('change type', this.type);
    },
    question() {
      console.log('change question', this.question);
    },
    options() {
      console.log('change options', this.options);
    }
  },
  computed: {
  },
  methods: {
    isShowOptions() {
      return this.type && !['text', 'textarea'].includes(this.type);
    },
    isDisableDeleteOption() {
      return this.options.length <= 1;
    },
    removeOption(oid) {
      this.options = this.option.filter(({ id }) => id !== oid);
    },
    addOption() {
      if (this.validateOptions()) {
        this.options = [...this.options, {id: this.uuidv4(), value: ''} ];
      }
      console.log(this.options);
    },
    addOptionValue(id, event) {

    },
    validateOptions() {
      if (this.options.some(item => !item.value.trim())) {
        this.showAlert('Option must not be empty');
        return false;
      }
      return true;
    },
    showAlert(str) {
      alert(str);
    },
    uuidv4() {
      return ([1e7]+-1e3+-4e3+-8e3+-1e11).replace(/[018]/g, c =>
        (c ^ crypto.getRandomValues(new Uint8Array(1))[0] & 15 >> c / 4).toString(16)
      );
    }
  }
});
JS;
$this->registerJs($script);
?>