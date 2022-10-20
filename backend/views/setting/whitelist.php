<?php
use yii\widgets\ActiveForm;
use yii\helpers\Url;
?>
<div class="page-bar">
  <ul class="page-breadcrumb">
    <li>
      <a href="/"><?=Yii::t('app', 'home');?></a>
      <i class="fa fa-circle"></i>
    </li>
    <li>
      <a href="javascript:;"><?=Yii::t('app', 'settings');?></a>
      <i class="fa fa-circle"></i>
    </li>
    <li>
      <span>White list</span>
    </li>
  </ul>
</div>
<!-- END PAGE BAR -->
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title">White list</h1>
<!-- END PAGE TITLE-->
<div class="row">
  <div class="col-md-12">
    <?php $form = ActiveForm::begin(['options' => ['class' => 'form-horizontal form-row-seperated']]);?>
    <div class="portlet">
      <div class="portlet-title">
        <div class="caption">White list</div>
        <div class="actions btn-set">
          <button type="reset" class="btn default">
          <i class="fa fa-angle-left"></i> <?=Yii::t('app', 'reset');?>
          <button type="submit" class="btn btn-success">
          <i class="fa fa-check"></i> <?=Yii::t('app', 'save');?>
          </button>
        </div>
      </div>
      <div class="portlet-body">
        <div class="tabbable-bordered">
          <?php echo $this->render('@backend/views/setting/_widget_tabs.php', ['tab' => 'whitelist']);?>
          <div class="tab-content">
            <div class="tab-pane active" id="tab_general">
              <div class="form-body">
                <?=$form->field($model, 'status', [
                  'labelOptions' => ['class' => 'col-md-2 control-label'],
                  'template' => '{label}<div class="col-md-10">{input}{hint}{error}</div>'
                ])->checkbox(['1' => ' <span></span>'], [
                  'class' => 'md-checkbox', 
                  'encode' => false , 
                  'itemOptions' => ['labelOptions' => ['class'=>'mt-checkbox', 'style' => 'display: block']]
                ])->label('Chặn IP Việt Nam');?>
                <hr/>
                <?php $model->whitelist = @unserialize($model->whitelist);?>
                <?=$form->field($model, 'whitelist', [
                  'labelOptions' => ['class' => 'col-md-2 control-label'],
                  'inputOptions' => ['class' => 'form-control', 'id' => 'whitelist'],
                  'template' => '{label}<div class="col-md-6">{input}{hint}{error}</div>'
                ])->widget(kartik\select2\Select2::classname(), [
                  'options' => ['class' => 'form-control', 'multiple' => true],
                  'pluginOptions' => ['tags' => true]
                ]);?>
                <?=$form->field($model, 'unwhitelist', [
                  'labelOptions' => ['class' => ''],
                  'inputOptions' => ['class' => '', 'id' => 'unwhitelist'],
                  'template' => '{input}'
                ])->hiddenInput();?>
                <hr/>
                <?php $uips = explode(",", $model->unwhitelist);?>
                <div class="table-responsive">
                  <table class="table table-bordered">
                    <thead>
                      <tr>
                        <th> IP </th>
                        <th> Action </th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php if (!$model->whitelist) :?>
                      <tr><td colspan="2"><?=Yii::t('app', 'no_data_found');?></td></tr>
                      <?php else:?>
                      <?php foreach ((array)$model->whitelist as $ip) :?>
                      <tr>
                        <th> <?=$ip;?> </th>
                        <th> 
                          <a href='javascript:;' class="btn btn-xs grey-salsa tooltips approve" data-ip="192.168.0.2" data-pjax="0" data-container="body" data-original-title="Approve"><i class="fa fa-check"></i></a>
                          <a href='javascript:;' class="btn btn-xs grey-salsa tooltips" data-pjax="0" data-container="body" data-original-title="Delete"><i class="fa fa-close"></i></a>
                        </th>
                      </tr>
                      <?php endforeach;?>
                      <?php endif;?>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <?php ActiveForm::end();?>
  </div>
</div>

<?php
$script = <<< JS
function onlyUnique(value, index, self) {
  return self.indexOf(value) === index;
}

function getOptions(selectId) {
  var options = [];
  $(selectId + " option").each(function() {
    options.push($(this).val());
  });
  return options;
}

function approve(ip) {
  var options = getOptions('#whitelist');
  if (options.indexOf(ip) <= -1) {
    var newOption = new Option(ip, ip, false, false);
    $('#whitelist').append(newOption);
  }
  var selectedOptions = $('#whitelist').val();
  selectedOptions = Array.isArray(selectedOptions) ? selectedOptions : [];
  selectedOptions.push(ip);
  selectedOptions = selectedOptions.filter(onlyUnique);
  $('#whitelist').val(null);
  $('#whitelist').val(selectedOptions);
  return false;
}
function remove(ip) {
  var ips = $('#unwhitelist').val();
  ips = ips.split(',').filter(function (x){ return x }).filter(function(x){ return x != ip });
  $('#unwhitelist').val(ips.join(","));
}
$('.approve').on('click', function() {
  approve($(this).data('ip'));
});
$('.remove').on('click', function() {
  approve($(this).data('ip'));
});
JS;
$this->registerJs($script);
?>