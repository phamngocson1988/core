<?php
use yii\widgets\ActiveForm;
use yii\helpers\Url;

$records = $model->fetch();
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
                <div class="table-responsive">
                  <table class="table table-bordered">
                    <thead>
                      <tr>
                        <th> IP </th>
                        <th> Name </th>
                        <th> Action </th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php if (!count($records)) :?>
                      <tr><td colspan="3" class="center"><?=Yii::t('app', 'no_data_found');?></td></tr>
                      <?php else:?>
                      <?php foreach ($records as $record) :?>
                      <tr>
                        <th> <?=$record->ip;?> </th>
                        <th> <?=$record->name;?> </th>
                        <th> 
                          <a href='<?=Url::to(['setting/whitelist-action', 'ip' => $record->ip, 'action' => 'approve']);?>' class="btn btn-xs grey-salsa tooltips approve" data-pjax="0" data-container="body" data-original-title="Approve"><i class="fa fa-check"></i></a>
                          <a href='<?=Url::to(['setting/whitelist-action', 'ip' => $record->ip, 'action' => 'delete']);?>' class="btn btn-xs grey-salsa tooltips delete" data-pjax="0" data-container="body" data-original-title="Delete"><i class="fa fa-close"></i></a>
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