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
          <?php $form = ActiveForm::begin(['action' => Url::to(['complain/create'])]); ?>
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
                    <input id="inputGroupFile01" type="file">
                    <label for="inputGroupFile01"><i class="fas fa-paperclip"></i><span>ATTACH FILES</span></label>
                  </div>
                </div>
                <div class="col-lg-8 text-right custom-agree">
                  <label>
                    <input class="mr-1" type="checkbox"><span>I agree to the Terms &amp; Conditions and Privacy Policy</span>
                  </label>
                </div>
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
          <div class="complaints-row mb-5">
            <div class="text-center"><a class="btn btn-primary text-uppercase" href="#">BACK TO HENDERSON &amp; BENCH</a></div>
            <h2 class="sec-title text-center mb-2 mt-3">Henderson &amp; bench complaints</h2>
            <div class="row justify-content-center text-uppercase mb-5">
              <div class="col-auto">total 995 cases</div>
              <div class="col-auto">700/995 cases resolved(90%)</div>
              <div class="col-auto">5 hours average response</div>
            </div>
            <div class="row">
              <div class="col-12 col-sm-6 col-md-6 col-lg-4 col-xl-3">
                <div class="block-complaint">
                  <div class="complaint-image"><img src="../img/top/img_02.jpg" alt="image"></div>
                  <div class="complaint-heading">
                    <p class="complaint-ttl">OPEN CASE</p>
                    <p>An Hour Ago</p>
                  </div>
                  <div class="complaint-desc">Henderson &amp; Ben - Slow approval of withdrawal</div><a class="btn btn-primary" href="#">READ MORE</a>
                </div>
              </div>
              <div class="col-12 col-sm-6 col-md-6 col-lg-4 col-xl-3">
                <div class="block-complaint">
                  <div class="complaint-image"><img src="../img/top/img_02.jpg" alt="image"></div>
                  <div class="complaint-heading">
                    <p class="complaint-ttl">RESOLVED</p>
                    <p>An Hour Ago</p>
                  </div>
                  <div class="complaint-desc">Henderson &amp; Ben - Slow approval of withdrawal</div><a class="btn btn-primary" href="#">READ MORE</a>
                </div>
              </div>
              <div class="col-12 col-sm-6 col-md-6 col-lg-4 col-xl-3">
                <div class="block-complaint">
                  <div class="complaint-image"><img src="../img/top/img_02.jpg" alt="image"></div>
                  <div class="complaint-heading">
                    <p class="complaint-ttl">REJECTED</p>
                    <p>An Hour Ago</p>
                  </div>
                  <div class="complaint-desc">Henderson &amp; Ben - Slow approval of withdrawal</div><a class="btn btn-primary" href="#">READ MORE</a>
                </div>
              </div>
              <div class="col-12 col-sm-6 col-md-6 col-lg-4 col-xl-3">
                <div class="block-complaint">
                  <div class="complaint-image"><img src="../img/top/img_02.jpg" alt="image"></div>
                  <div class="complaint-heading">
                    <p class="complaint-ttl">REJECTED</p>
                    <p>An Hour Ago</p>
                  </div>
                  <div class="complaint-desc">Henderson &amp; Ben - Slow approval of withdrawal</div><a class="btn btn-primary" href="#">READ MORE</a>
                </div>
              </div>
            </div>
            <div class="text-center"><a class="btn btn-primary text-uppercase pl-5 pr-5" href="#">SEE ALL</a></div>
          </div>
          <div class="widget-box p-3 p-md-4">
            <div class="row align-items-center">
              <div class="col-xl-6 mb-xl-0 mb-2">
                <p class="mb-0">HAVE TROUBLE WITH HENDERSON &amp; BENCH?</p>
              </div>
              <div class="col-xl-6">
                <div class="row">
                  <div class="col-lg-6 mb-lg-0 mb-2"><a class="btn btn-primary btn-block" href="#">SUBMIT COMPAINT</a></div>
                  <div class="col-lg-6"><a class="btn btn-info btn-block" href="#">LEARN MORE</a></div>
                </div>
              </div>
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