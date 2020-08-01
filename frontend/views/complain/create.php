<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
$this->title = 'Leave complain';
?>
<main>
  <section class="section-module">
    <div class="container">
      <h1 class="sec-title">Having trouble with an operator? We’re here to help!</h1>
      <div class="sec-content">
        <div class="mod-column form-complaints">
          <?php $form = ActiveForm::begin(['action' => Url::to(['complain/create']), 'options' => ["enctype" => "multipart/form-data"]]); ?>
          <div class="widget-box mb-5 p-3 p-md-4">
            <p class="text-uppercase mb-2">SELECT AN OPTION THAT BEST DESCRIBES THE ISSUE</p>
            <?= $form->field($model, 'reason_id', [
              'options' => ['class' => 'col-sm-6 col-md-6 col-lg-5 p-0'],
            ])->dropdownList($model->fetchReason())->label(false);?>
          </div>
          <div class="widget-box mb-5 p-3 p-md-4">
            <div class="mb-5">
              <h2 class="sec-ttl mb-3">Fill in the complaint submission form</h2>
              <div class="row mb-3">
                <?= $form->field($model, 'title', [
                  'options' => ['class' => 'col-sm-6 mb-sm-0 mb-3'],
                  'labelOptions' => ['class' => 'text-uppercase mb-2']
                ])->textInput();?>
                <?= $form->field($model, 'operator_id', [
                  'options' => ['class' => 'col-sm-6'],
                  'labelOptions' => ['class' => 'text-uppercase mb-2']
                ])->dropdownList($model->fetchOperator());?>
              </div>
              <?= $form->field($model, 'description', [
                'inputOptions' => ['class' => 'form-control mb-1', 'cols' => '30', 'rows' => '10'],
                'options' => ['tag' => false],
                'template' => '{input}{hint}'
              ])->textArea();?>
              <div class="row">
                <div class="col-lg-4">
                  <div class="file-upload">
                    <input id="inputGroupFile01" name="attachFile" type="file">
                    <label for="inputGroupFile01"><i class="fas fa-paperclip"></i><span>ATTACH FILES</span></label>
                  </div>
                </div>
                <?= $form->field($model, 'agree', [
                  'inputOptions' => ['class' => 'mr-1'],
                  'options' => ['class' => 'col-lg-8 text-right custom-agree'],
                ])->checkbox()->label('<span>I agree to the Terms &amp; Conditions and Privacy Policy</span>');?>

              </div>
            </div>
            <div class="mb-4">
              <h2 class="sec-ttl mb-3">Following information will not be published</h2>
              <div class="row mb-3">
                <?= $form->field($model, 'account_name', [
                  'options' => ['class' => 'col-sm-6 mb-sm-0 mb-3'],
                  'labelOptions' => ['class' => 'text-uppercase mb-2']
                ])->textInput();?>
                <?= $form->field($model, 'account_email', [
                  'options' => ['class' => 'col-sm-6'],
                  'labelOptions' => ['class' => 'text-uppercase mb-2']
                ])->textInput();?>
              </div>
            </div>
            <div class="text-center">
              <button class="btn btn-primary pl-3 pr-3" type="submit">SUBMIT MY COMPLAINT</button>
            </div>
          </div>
          <?php ActiveForm::end();?>
        </div>
        <aside class="mod-sidebar">
          <div class="sidebar-col">
            <?=\frontend\widgets\TopOperatorWidget::widget();?>
          </div>
          <?=\frontend\widgets\AdsWidget::widget(['position' => \frontend\models\Ads::POSITION_SIDEBAR]);?>
        </aside>
      </div>
    </div>
  </section>
</main>