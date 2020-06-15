<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
$this->title = 'Update Operator';
?>
<main>
  <section class="section-profile-user">
    <div class="container">
      <div class="sec-heading-profile widget-box mb-4">
        <div class="heading-banner"><img class="object-fit" src="../img/profile/profile_bnr.jpg" alt="image"></div>
        <div class="heading-body">
          <div class="heading-avatar col-avatar">
            <div class="heading-image"><img class="object-fit" src="../img/common/avatar_img_01.png" alt="image"><a class="edit-camera fas fa-camera trans" href="#"></a></div>
            <h1 class="heading-name">Henderson &amp; Bench</h1>
          </div>
        </div>
      </div>
      <div class="sec-content">
        <div class="mod-column">
          <div class="widget-box timeline-post">
            <div class="section-operator-update">
              <?php $form = ActiveForm::begin(); ?>
                <div class="row">
                  <div class="col-12 col-lg-6">
                    <?= $form->field($model, 'name', [
                      'labelOptions' => ['class' => 'fm-label'],
                      'inputOptions' => ['class' => 'form-control', 'disabled' => true, 'readonly' => true, 'name' => '']
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
                    <!-- <div class="form-group">
                      <label class="fm-label">Supported Languages</label>
                      <div>
                        <input class="form-control" type="text" id="js-tag-language" style="max-width: 300px">
                      </div>
                    </div> -->
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
                      'inputOptions' => ['class' => 'form-control', 'type' => 'number']
                    ])->dropdownList(['USD' => 'USD', 'VND' => 'VND']);?>
                  </div>
                </div>
                <div class="row">
                  <div class="col-12 col-lg-6">
                    <?= $form->field($model, 'rebate', [
                      'labelOptions' => ['class' => 'fm-label'],
                      'inputOptions' => ['class' => 'form-control', 'type' => 'number']
                    ])->textInput()->label('Rebate (%)');?>
                  </div>
                  <div class="col-12 col-lg-6">
                    <?= $form->field($model, 'withdrawal_currency', [
                      'labelOptions' => ['class' => 'fm-label'],
                      'inputOptions' => ['class' => 'form-control', 'type' => 'number']
                    ])->dropdownList(['USD' => 'USD', 'VND' => 'VND']);?>
                  </div>
                </div>
                <div class="row">
                  <div class="col-12 col-lg-6">
                    <div class="form-group">
                      <label class="fm-label">Supported Currencies</label>
                      <div>
                        <label class="form-check form-check-inline">
                          <input class="form-check-input" type="checkbox" value="option1" checked><span class="form-check-label">VND</span>
                        </label>
                        <label class="form-check form-check-inline">
                          <input class="form-check-input" type="checkbox" value="option2"><span class="form-check-label">THB</span>
                        </label>
                        <label class="form-check form-check-inline">
                          <input class="form-check-input" type="checkbox" value="option3"><span class="form-check-label">MYR</span>
                        </label>
                        <label class="form-check form-check-inline">
                          <input class="form-check-input" type="checkbox" value="option4"><span class="form-check-label">USD</span>
                        </label>
                      </div>
                    </div>
                  </div>
                  <div class="col-12 col-lg-6">
                    <div class="form-group">
                      <label class="fm-label">Products</label>
                      <div>
                        <input class="form-control" type="text" data-role="tagsinput">
                      </div>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-12 col-lg-6">
                    <div class="form-group">
                      <label class="fm-label">License</label>
                      <input class="form-control fm-calendar js-datepicker" type="text">
                    </div>
                  </div>
                  <div class="col-12 col-lg-6">
                    <div class="form-group">
                      <label class="fm-label">Deposit Methods</label>
                      <input class="form-control fm-calendar js-datepicker" type="text">
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-12 col-lg-6">
                    <div class="form-group">
                      <label class="fm-label">Owner</label>
                      <input class="form-control" type="text">
                    </div>
                  </div>
                  <div class="col-12 col-lg-6">
                    <div class="form-group">
                      <label class="fm-label">Withdrawal Methods</label>
                      <input class="form-control" type="text">
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-12 col-lg-6">
                    <div class="form-group">
                      <label class="fm-label">Established</label>
                      <input class="form-control" type="text">
                    </div>
                  </div>
                  <div class="col-12 col-lg-6">
                    <div class="form-group">
                      <label class="fm-label">Withdrawal Time</label>
                      <select class="form-control">
                        <option selected>Local Banks: 0-24 hours</option>
                        <option>International Banks: 0-24 hours</option>
                      </select>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-12 col-lg-6">
                    <div class="form-group">
                      <label class="fm-label">Live Chat</label>
                      <div>
                        <label class="form-check form-check-inline">
                          <input class="form-check-input" id="fm-livechat1" type="radio" name="fm-livechat" value="yes" selected><span class="form-check-label" for="fm-livechat1">Yes</span>
                        </label>
                        <label class="form-check form-check-inline">
                          <input class="form-check-input" id="fm-livechat2" type="radio" name="fm-livechat" value="no"><span class="form-check-label" for="fm-livechat2">No</span>
                        </label>
                        <label class="form-check form-check-inline">
                          <input class="form-check-input" id="fm-livechat3" type="radio" name="fm-livechat" value="other" disabled><span class="form-check-label" for="fm-livechat3">Other (disabled)</span>
                        </label>
                      </div>
                    </div>
                  </div>
                  <div class="col-12 col-lg-6">
                    <div class="form-group">
                      <label class="fm-label">Withdrawal Limit</label>
                      <select class="form-control">
                        <option selected>Up to $50,000 per transaction</option>
                        <option>Up to $30,000 per transaction</option>
                        <option>Up to $10,000 per transaction</option>
                      </select>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-12 col-lg-6">
                    <div class="form-group">
                      <label class="fm-label">Rebates</label>
                      <select class="form-control">
                        <option selected>Max 1.5%</option>
                        <option>Max 1.0%</option>
                        <option>Max 0.5%</option>
                      </select>
                    </div>
                  </div>
                  <div class="col-12 col-lg-6"></div>
                </div>
                <div class="form-group">
                  <label class="fm-label">Contact</label>
                  <textarea class="form-control" rows="4"></textarea>
                </div>
                <div class="form-group">
                  <label class="fm-label">Overview</label>
                  <textarea class="form-control" rows="8"></textarea>
                </div>
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
            <div class="category-row">
              <p class="category-title"><a class="trans" href="#"><i class="fas fa-pencil-alt"></i>Reviews (52)</a></p>
              <div class="category-inner">
                <ul class="category-list">
                  <li><a class="trans" href="#">Unresponded reviews (31)</a></li>
                  <li><a class="trans" href="#">Responded reviews (21)</a></li>
                </ul>
              </div>
            </div>
            <div class="category-row">
              <p class="category-title"><a class="trans" href="#"><i class="fas fa-comment"></i>Complaints (1,796)</a></p>
              <div class="category-inner">
                <ul class="category-list">
                  <li><a class="trans" href="#">Open cases (18)</a></li>
                  <li><a class="trans" href="#">Resolved (99)</a></li>
                  <li><a class="trans" href="#">Rejected (0)</a></li>
                </ul>
              </div>
            </div>
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
<?php
$script = <<< JS
$('form').on('keyup keypress', function(e) {
  var keyCode = e.keyCode || e.which;
  if (keyCode === 13) { 
    e.preventDefault();
    return false;
  }
});
JS;
$this->registerJs($script);
?>