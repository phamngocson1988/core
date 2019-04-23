<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use common\models\User;
?>
<section class="section section-lg bg-default novi-background bg-cover text-center">
  <div class="container">
    <div class="row row-fix justify-content-sm-center">
      <div class="col-md-10 col-xl-8">
        <h3>Profile</h3>
        <!-- RD Mailform-->
        <?php $form = ActiveForm::begin(); ?>
          <div class="row row-fix row-20">
            <div class="col-md-6">
              <div class="form-wrap form-wrap-validation">
                <label class="form-label-outside" for="forms-3-name">Username</label>
                <input class="form-input" type="text" value="<?=$model->username;?>" readonly>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-wrap form-wrap-validation">
                <label class="form-label-outside" for="forms-3-last-name">Email</label>
                <input class="form-input" type="text" value="<?=$model->email;?>" readonly>
              </div>
            </div>
            <div class="col-sm-12">
              <?= $form->field($model, 'name', [
                'options' => ['class' => 'form-wrap form-wrap-inline'],
                'inputOptions' => ['class' => 'form-input'],
                'labelOptions' => ['class' => 'form-label-outside'],
                'errorOptions' => ['tag' => 'span', 'class' => 'form-validation'],
                'template' => '{label}{input}{hint}{error}'
              ])->textInput(['autofocus' => true]) ?>

              <?= $form->field($model, 'address', [
                'options' => ['class' => 'form-wrap form-wrap-inline'],
                'inputOptions' => ['class' => 'form-input'],
                'labelOptions' => ['class' => 'form-label-outside'],
                'errorOptions' => ['tag' => 'span', 'class' => 'form-validation'],
                'template' => '{label}{input}{hint}{error}'
              ])->textInput() ?>
            </div>
            <div class="col-md-6">
              <?= $form->field($model, 'country_code', [
                'options' => ['class' => 'form-wrap form-wrap-validation'],
                'inputOptions' => ['class' => 'form-input select-filter', 'data-placeholder' => 'Choose a country'],
                'labelOptions' => ['class' => 'form-label-outside'],
                'errorOptions' => ['tag' => 'span', 'class' => 'form-validation'],
                'template' => '{label}{input}{hint}{error}'
              ])->dropDownList(Yii::$app->params['country_code']) ?>
            </div>
            <div class="col-md-6">
              <?= $form->field($model, 'phone', [
                'options' => ['class' => 'form-wrap form-wrap-validation'],
                'inputOptions' => ['class' => 'form-input'],
                'labelOptions' => ['class' => 'form-label-outside'],
                'errorOptions' => ['tag' => 'span', 'class' => 'form-validation'],
                'template' => '{label}{input}{hint}{error}'
              ])->textInput() ?>
            </div>
            <div class="col-md-6">
              <?= $form->field($model, 'birthday', [
                'options' => ['class' => 'form-wrap form-wrap-validation'],
                'inputOptions' => ['class' => 'form-input', 'autocomplete' => false, 'readonly' => true],
                'labelOptions' => ['class' => 'form-label-outside'],
                'errorOptions' => ['tag' => 'span', 'class' => 'form-validation'],
                'template' => '{label}{input}{hint}{error}'
              ])->widget(\yii\jui\DatePicker::className(),['clientOptions' => ['changeMonth' => true, 'changeYear' => true], "dateFormat" => "yyyy-MM-dd"]) ?>
            </div>
            <div class="col-md-6">
              <?= $form->field($model, 'favorite', [
                'options' => ['class' => 'form-wrap form-wrap-validation'],
                'inputOptions' => ['class' => 'form-input select-filter', 'data-placeholder' => 'Choose favorite game'],
                'labelOptions' => ['class' => 'form-label-outside'],
                'errorOptions' => ['tag' => 'span', 'class' => 'form-validation'],
                'template' => '{label}{input}{hint}{error}'
              ])->dropDownList($model->fetchGames()) ?>
            </div>
            <?= Html::a('Go back', Url::to(['user/index']), ['class' => 'button button-primary button-nina']) ?>
            <?= Html::submitButton('Update', ['class' => 'button button-secondary button-nina']) ?>
          </div>
        <?php ActiveForm::end();?>
      </div>
    </div>
  </div>
</section>
<?php
$script = <<< JS
var cur= new Date();
$('.date-picker').bootstrapMaterialDatePicker({
  format: 'YYYY-MM-DD',
  maxDate: cur,
  time: false,
  year: false
});
JS;
$this->registerJs($script);
?>