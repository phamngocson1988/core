<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
$this->title = 'New topic';
?>
<main>
  <section class="section-module">
    <div class="container">
      <h1 class="sec-title">Create new topic</h1>
      <div class="sec-content">
        <div class="mod-column form-complaints">
          <?php $form = ActiveForm::begin(); ?>
          <div class="widget-box mb-5 p-3 p-md-4">
            <div class="mb-5">
              <h2 class="sec-ttl mb-3">Fill in the topic form</h2>
              <div class="row mb-3">
                <?= $form->field($model, 'subject', [
                  'options' => ['class' => 'col-sm-6 mb-sm-0 mb-3'],
                  'labelOptions' => ['class' => 'text-uppercase mb-2']
                ])->textInput();?>
                <?= $form->field($model, 'category_id', [
                  'options' => ['class' => 'col-sm-6'],
                  'labelOptions' => ['class' => 'text-uppercase mb-2']
                ])->dropdownList($model->fetchCategory());?>
              </div>
              <?= $form->field($model, 'content', [
                'inputOptions' => ['class' => 'form-control mb-1', 'cols' => '30', 'rows' => '10'],
                'options' => ['tag' => false],
                'template' => '{input}{hint}'
              ])->textArea();?>
            </div>
            <div class="text-center">
              <button class="btn btn-primary pl-3 pr-3" type="submit">CREATE NEW TOPIC</button>
            </div>
          </div>
          <?php ActiveForm::end();?>
        </div>
        <aside class="mod-sidebar">
          <div class="sidebar-col">
            <?=\frontend\widgets\TopOperatorWidget::widget();?>
          </div>
          <div class="sidebar-delineation"><a class="trans" href="#"><img src="../img/operators/img_01.jpg" alt="image"></a></div>
        </aside>
      </div>
    </div>
  </section>
</main>