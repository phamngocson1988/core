<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use dosamigos\datepicker\DatePicker;
use dosamigos\datepicker\DateRangePicker;
use yii\web\JsExpression;
use common\models\Promotion;
use common\widgets\TinyMce;
?>
<!-- BEGIN PAGE BAR -->
<div class="page-bar">
  <ul class="page-breadcrumb">
    <li>
      <a href="/"><?=Yii::t('app', 'home')?></a>
      <i class="fa fa-circle"></i>
    </li>
    <li>
      <a href="<?=Url::to(['realestate/index'])?>">Quản lý nhà cho thuê</a>
      <i class="fa fa-circle"></i>
    </li>
    <li>
      <span>Quản lý phòng</span>
    </li>
  </ul>
</div>
<!-- END PAGE BAR -->
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title">Quản lý phòng</h1>
<!-- END PAGE TITLE-->
<div class="row">
  <div class="col-md-12">
    <div class="portlet">
      <div class="portlet-title">
        <div class="actions btn-set">
          <a href="{$back}" class="btn default">
          <i class="fa fa-angle-left"></i> <?=Yii::t('app', 'back')?></a>
          <a  href="<?=Url::to(['room/create', 'id' => $realestate->id]);?>" class="btn btn-success">
          <i class="fa fa-check"></i> <?=Yii::t('app', 'add_new')?>
          </a>
        </div>
      </div>
      <div class="portlet-body form-horizontal form-row-seperated">
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
                  <label class="col-md-2 control-label">Nhà cho thuê</label>
                  <div class="col-md-6"><input class="form-control" value="<?=$realestate->title;?>" disabled readonly></div>
                </div>
                <div class="form-group">
                  <label class="col-md-2 control-label">Địa chỉ</label>
                  <div class="col-md-6"><input class="form-control" value="<?=$realestate->address;?>" disabled readonly></div>
                </div>
                <hr/>
                <table class="table table-striped table-bordered table-hover table-checkable">
                  <thead>
                    <tr>
                      <th style="width: 10%;"> Mã phòng </th>
                      <th style="width: 20%;"> Giá phòng </th>
                      <th style="width: 50%;"> Các dịch vụ </th>
                      <th style="width: 10%;"> Trạng thái </th>
                      <th style="width: 10%;"> Tác vụ </th>
                    </tr>
                  </thead>
                  <tbody>
                      <?php if (!$realestate->rooms) :?>
                      <tr><td colspan="5"><?=Yii::t('app', 'no_data_found');?></td></tr>
                      <?php endif;?>
                      <?php foreach ($realestate->rooms as $no => $room) :?>
                      <tr>
                        <td style="vertical-align: middle;"><?=$room->code;?></td>
                        <td style="vertical-align: middle;"><?=number_format($room->price);?></td>
                        <td style="vertical-align: middle;">
                        <?php
                        $rservices = [];
                        foreach ($room->availableRoomServices as $avaiRoomService) {
                          $service = ArrayHelper::getValue($services, $avaiRoomService->realestate_service_id);
                          if (!$service) continue;
                          $rservices[] = sprintf('<a href="javascript:void;">%s <span class="badge">%s</span></a>', $service->title, number_format($avaiRoomService->price));
                        }
                        echo implode(" | ", $rservices);
                        ?>
                        </td>
                        <td style="vertical-align: middle;"><?=$room->status;?></td>
                        <td style="vertical-align: middle;">
                          <a href='<?=Url::to(['room/edit', 'id' => $realestate->id, 'roomId' => $room->id]);?>' class="btn btn-xs grey-salsa tooltips" data-pjax="0" data-container="body" data-original-title="Chỉnh sửa"><i class="fa fa-pencil"></i></a>
                        </td>
                      </tr>
                      <?php endforeach;?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>