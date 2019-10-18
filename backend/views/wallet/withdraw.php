<?php 
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\web\JsExpression;
?>
<div class="page-bar">
  <ul class="page-breadcrumb">
    <li>
      <a href="/"><?=Yii::t('app', 'home')?></a>
      <i class="fa fa-circle"></i>
    </li>
    <li>
      <a href="<?=Url::to(['wallet/index'])?>">Ví tiền</a>
      <i class="fa fa-circle"></i>
    </li>
    <li>
      <span>Rút tiền khỏi ví</span>
    </li>
  </ul>
</div>
<!-- END PAGE BAR -->
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title">Rút tiền khỏi ví</h1>
<!-- END PAGE TITLE-->
<div class="row">
  <div class="col-md-12">
    <?php $form = ActiveForm::begin(['options' => ['class' => 'form-horizontal form-row-seperated']]);?>
    <div class="portlet">
      <div class="portlet-title">
        <div class="caption">Rút tiền khỏi ví</div>
        <div class="actions btn-set">
          <a href="<?=Url::to(['wallet/index'])?>" class="btn default">
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
              <div class="form-body">
                <div class="form-group">
                  <label class="col-md-2 control-label">Mã ID User</label>
                  <div class="col-md-6">
                    <input type="text" disabled="true" class="form-control" value="<?=$user->id;?>">
                  </div>
                </div>

                <div class="form-group">
                  <label class="col-md-2 control-label">Username</label>
                  <div class="col-md-6">
                    <input type="text" disabled="true" class="form-control" value="<?=$user->username;?>">
                  </div>
                </div>

                <div class="form-group">
                  <label class="col-md-2 control-label">User email</label>
                  <div class="col-md-6">
                    <input type="text" disabled="true" class="form-control" value="<?=$user->email;?>">
                  </div>
                </div>

                <div class="form-group">
                  <label class="col-md-2 control-label">Name</label>
                  <div class="col-md-6">
                    <input type="text" disabled="true" class="form-control" value="<?=$user->name;?>">
                  </div>
                </div>

                <div class="form-group">
                  <label class="col-md-2 control-label">Ví hiện tại</label>
                  <div class="col-md-6">
                    <input type="text" disabled="true" class="form-control" value="<?=$balance;?>">
                  </div>
                </div>

                <div class="form-group">
                  <label class="col-md-2 control-label">Số lượng rút</label>
                  <div class="col-md-6">
                    <input type="number" class="form-control" name="coin">
                  </div>
                </div>

                <div class="form-group">
                  <label class="col-md-2 control-label">Mô tả</label>
                  <div class="col-md-6">
                    <input type="text" class="form-control" name="description">
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