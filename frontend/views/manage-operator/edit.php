<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\helpers\Html;
$this->title = 'Update Operator';
?>
<main>
  <section class="section-profile-user">
    <div class="container">
      <?php echo $this->render('@frontend/views/manage/header.php', ['operator' => $operator]);?>
      <div class="sec-content">
        <div class="mod-column">
          <div class="widget-box timeline-post">
            <div class="section-operator-update">
              <?php $form = ActiveForm::begin(); ?>
                <div class="row">
                  <div class="col-12 col-lg-6">
                    <?= $form->field($model, 'name', [
                      'labelOptions' => ['class' => 'fm-label'],
                      'inputOptions' => ['class' => 'form-control', 'readonly' => true]
                    ])->textInput();?>
                  </div>
                  <div class="col-12 col-lg-6">
                    <?= $form->field($model, 'main_url', [
                      'labelOptions' => ['class' => 'fm-label'],
                    ])->textInput();?>
                  </div>
                </div>
                <div class="row">
                  <div class="col-12 col-lg-6">
                    <?= $form->field($model, 'backup_url', [
                      'labelOptions' => ['class' => 'fm-label'],
                      'template' => '{label}<div>{input}</div>',
                      'inputOptions' => ['class' => 'form-control', 'data-role' => 'tagsinput']
                    ])->textInput();?>
                  </div>
                  <div class="col-12 col-lg-6">
                    <?= $form->field($model, 'rebate', [
                      'labelOptions' => ['class' => 'fm-label'],
                      'inputOptions' => ['class' => 'form-control', 'type' => 'number']
                    ])->textInput()->label('Rebate (%)');?>
                  </div>
                </div>
                <div class="row">
                  <div class="col-12 col-lg-6">
                    <?= $form->field($model, 'withdrawal_limit', [
                      'labelOptions' => ['class' => 'fm-label'],
                      'inputOptions' => ['class' => 'form-control', 'type' => 'number']
                    ])->textInput();?>
                  </div>
                  <div class="col-12 col-lg-6">
                    <?= $form->field($model, 'withdrawal_currency', [
                      'labelOptions' => ['class' => 'fm-label'],
                      'inputOptions' => ['class' => 'form-control']
                    ])->dropdownList($model->fetchCurrency());?>
                  </div>
                </div>
                <div class="row">
                  <div class="col-12 col-lg-6">
                    <?= $form->field($model, 'withdrawal_time', [
                      'labelOptions' => ['class' => 'fm-label'],
                      'inputOptions' => ['class' => 'form-control']
                    ])->dropdownList($model->fetchWithdrawTime());?>
                  </div>
                  <div class="col-12 col-lg-6">
                    <?= $form->field($model, 'withdrawal_method', [
                      'labelOptions' => ['class' => 'fm-label'],
                      'inputOptions' => ['class' => 'form-control']
                    ])->checkboxList($model->fetchWithdrawMethod(), [
                      'item' => function($index, $label, $name, $checked, $value) {
                        $checkbox = Html::checkbox($name, $checked, ['class' => 'form-check-input', 'value' => $value]);
                        return Html::tag('label', sprintf('%s<span class="form-check-label">%s</span>',$checkbox, $label), ['class' => 'form-check form-check-inline']);
                      }
                    ]);?>
                  </div>
                </div>
                <div class="row">
                  <div class="col-12 col-lg-6">
                    <?= $form->field($model, 'owner', [
                      'labelOptions' => ['class' => 'fm-label'],
                      'inputOptions' => ['class' => 'form-control']
                    ])->textInput();?>
                  </div>
                  <div class="col-12 col-lg-6">
                    <?= $form->field($model, 'established', [
                      'labelOptions' => ['class' => 'fm-label'],
                      'inputOptions' => ['class' => 'form-control']
                    ])->dropdownList($model->fetchEstablishedYear());?>
                  </div>
                </div>
                <div class="row">
                  <div class="col-12 col-lg-6">
                    <?= $form->field($model, 'support_email', [
                      'labelOptions' => ['class' => 'fm-label'],
                      'inputOptions' => ['class' => 'form-control']
                    ])->textInput();?>
                  </div>
                  <div class="col-12 col-lg-6">
                    <?= $form->field($model, 'support_phone', [
                      'labelOptions' => ['class' => 'fm-label'],
                      'inputOptions' => ['class' => 'form-control']
                    ])->textInput();?>
                  </div>
                </div>
                <div class="row">
                  <div class="col-12 col-lg-6">
                    <?= $form->field($model, 'livechat_support', [
                      'labelOptions' => ['class' => 'fm-label'],
                      'inputOptions' => ['class' => 'form-control']
                    ])->dropdownList($model->fetchLiveChat());?>
                  </div>
                  <div class="col-12 col-lg-6">
                    <?= $form->field($model, 'license', [
                      'labelOptions' => ['class' => 'fm-label'],
                      'inputOptions' => ['class' => 'form-control']
                    ])->dropdownList($model->fetchLicense(), ['prompt' => Yii::t('app', 'Select license')]);?>
                  </div>
                </div>
                <div class="row">
                  <div class="col-12 col-lg-6">
                    <?= $form->field($model, 'support_currency', [
                      'labelOptions' => ['class' => 'fm-label'],
                      'inputOptions' => ['class' => 'form-control']
                    ])->checkboxList($model->fetchCurrency(), [
                      'item' => function($index, $label, $name, $checked, $value) {
                        $checkbox = Html::checkbox($name, $checked, ['class' => 'form-check-input', 'value' => $value]);
                        return Html::tag('label', sprintf('%s<span class="form-check-label">%s</span>',$checkbox, $label), ['class' => 'form-check form-check-inline']);
                      }
                    ]);?>
                  </div>
                  <div class="col-12 col-lg-6">
                    <?= $form->field($model, 'support_language', [
                      'labelOptions' => ['class' => 'fm-label'],
                      'inputOptions' => ['class' => 'form-control']
                    ])->checkboxList($model->fetchLanguage(), [
                      'item' => function($index, $label, $name, $checked, $value) {
                        $checkbox = Html::checkbox($name, $checked, ['class' => 'form-check-input', 'value' => $value]);
                        return Html::tag('label', sprintf('%s<span class="form-check-label">%s</span>',$checkbox, $label), ['class' => 'form-check form-check-inline']);
                      }
                    ]);?>
                  </div>
                </div>
                <div class="row">
                  <div class="col-12 col-lg-6">
                    <?= $form->field($model, 'product', [
                      'labelOptions' => ['class' => 'fm-label'],
                      'inputOptions' => ['class' => 'form-control']
                    ])->checkboxList($model->fetchProduct(), [
                      'item' => function($index, $label, $name, $checked, $value) {
                        $checkbox = Html::checkbox($name, $checked, ['class' => 'form-check-input', 'value' => $value]);
                        return Html::tag('label', sprintf('%s<span class="form-check-label">%s</span>',$checkbox, $label), ['class' => 'form-check form-check-inline']);
                      }
                    ]);?>
                  </div>
                  <div class="col-12 col-lg-6">
                    <?= $form->field($model, 'deposit_method', [
                      'labelOptions' => ['class' => 'fm-label'],
                      'inputOptions' => ['class' => 'form-control']
                    ])->checkboxList($model->fetchDepositMethod(), [
                      'item' => function($index, $label, $name, $checked, $value) {
                        $checkbox = Html::checkbox($name, $checked, ['class' => 'form-check-input', 'value' => $value]);
                        return Html::tag('label', sprintf('%s<span class="form-check-label">%s</span>',$checkbox, $label), ['class' => 'form-check form-check-inline']);
                      }
                    ]);?>
                  </div>
                </div>
                
                <?= $form->field($model, 'overview', [
                  'labelOptions' => ['class' => 'fm-label'],
                  'inputOptions' => ['class' => 'form-control', 'rows' => 8]
                ])->textArea();?>
                <div class="form-group form-buttons">
                  <button class="btn btn-primary" type="submit">Update</button>
                  <button class="btn btn-secondary" type="reset">Reset</button>
                </div>
              <?php ActiveForm::end();?>
            </div>
          </div>
        </div>
        <div class="mod-sidebar">
          <div class="sidebar-col sidebar-category">
            <?=\frontend\widgets\ReviewStatByOperatorWidget::widget(['operator_id' => $model->id]);?>
            <?=\frontend\widgets\ComplainStatByOperatorWidget::widget(['operator_id' => $model->id]);?>
            
            <div class="category-row">
              <p class="category-title"><a class="trans" href="#"><i class="fas fa-users"></i>Manage users</a></p>
              <div class="category-inner">
                <ul class="category-list">
                  <li><a class="trans" href="#">Page admins (0)</a></li>
                  <li><a class="trans" href="#">Forum representtatives (0)</a></li>
                </ul>
              </div>
            </div>
          </div>
          <div class="sidebar-col widget-box">
            <div class="widget-title">PAGE'S STATS</div>
            <div class="widget-body">
              <ul class="stats-list">
                <li>Create since
                  <p class="text">March 29,2019</p>
                </li>
                <li>Total Visits
                  <p class="text">1</p>
                </li>
                <li>Bonus Claims
                  <p class="text">0</p>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</main>