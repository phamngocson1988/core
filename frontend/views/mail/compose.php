<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
$this->title = 'Leave complain';
?>
<main>
  <section class="section-module">
    <div class="container">
      <h1 class="sec-title">Compose new email!</h1>
      <div class="sec-content">
        <div class="mod-column form-complaints">
          <?php $form = ActiveForm::begin(['action' => Url::to(['complain/create'])]); ?>
          <div class="widget-box mb-5 p-3 p-md-4">
            <div class="mb-5">
              <h2 class="sec-ttl mb-3">Fill in the complaint submission form</h2>
              <div class="row">
                <?= $form->field($model, 'title', [
                  'labelOptions' => ['class' => 'text-uppercase mb-2']
                ])->textInput();?>
              </div>
              <?= $form->field($model, 'description', [
                'inputOptions' => ['class' => 'form-control mb-1', 'cols' => '30', 'rows' => '10'],
                'options' => ['tag' => false],
                'template' => '{input}{hint}'
              ])->textArea();?>
            </div>
            <div class="text-center">
              <button class="btn btn-primary pl-3 pr-3" type="submit">SEND</button>
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