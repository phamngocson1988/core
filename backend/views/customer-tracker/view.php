<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use dosamigos\datepicker\DatePicker;
use dosamigos\datepicker\DateRangePicker;
use yii\web\JsExpression;
use common\widgets\CheckboxInput;
use common\components\helpers\TimeElapsed;
?>
<style>
  .general-information tr>td:first-child, 
  .personal-information tr>td:first-child,
  .record-information tr>td:first-child {
    background-color: #8bb4e7;
  }
</style>
<div class="page-bar">
  <ul class="page-breadcrumb">
    <li>
      <a href="/"><?=Yii::t('app', 'home')?></a>
      <i class="fa fa-circle"></i>
    </li>
    <li>
      <a href="<?=Url::to(['task/index'])?>">Quản lý customer tracker</a>
      <i class="fa fa-circle"></i>
    </li>
    <li>
      <span>Profile cá nhân</span>
    </li>
  </ul>
</div>
<!-- END PAGE BAR -->
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title">Profile cá nhân</h1>
<!-- END PAGE TITLE-->
<div class="row">
  <div class="col-md-4">
    <!-- BEGIN PORTLET-->
    <div class="portlet light bordered">
      <div class="portlet-title">
        <div class="caption">
          <i class="icon-share font-red-sunglo"></i>
          <span class="caption-subject font-red-sunglo bold uppercase">General Information</span>
        </div>
      </div>
      <div class="portlet-body">
        <div class="clearfix">
          <div class="panel panel-success">
            <table class="table general-information">
              <tbody>
                <tr>
                  <td>Index</td>
                  <td>#<?=$model->id;?> </td>
                </tr>
                <tr>
                  <td>Name</td>
                  <td> <?=$model->name;?> </td>
                </tr>
                <tr>
                  <td>Link Account</td>
                  <td> <?=$model->data;?> </td>
                </tr>
                <tr>
                  <td>Account Manger</td>
                  <td><?=$model->saler ? $model->saler->getName() : '-';?> </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-md-4">
    <!-- BEGIN PORTLET-->
    <div class="portlet light bordered">
      <div class="portlet-title">
        <div class="caption">
          <i class="icon-share font-red-sunglo"></i>
          <span class="caption-subject font-red-sunglo bold uppercase">Personal Information</span>
        </div>
      </div>
      <div class="portlet-body">
        <div class="clearfix">
          <div class="panel panel-success">
            <table class="table personal-information">
              <tbody>
                <tr>
                  <td>Nationality</td>
                  <td><?=$model->getCountryName();?></td>
                </tr>
                <tr>
                  <td>Phone</td>
                  <td><?=$model->phone;?></td>
                </tr>
                <tr>
                  <td>Email</td>
                  <td><?=$model->email;?></td>
                </tr>
                <tr>
                  <td>Channel</td>
                  <td><?=$model->channel;?></td>
                </tr>
                <tr>
                  <td>Registration date</td>
                  <td><?=$model->registered_at;?> </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-md-4">
    <!-- BEGIN PORTLET-->
    <div class="portlet light bordered">
      <div class="portlet-title">
        <div class="caption">
          <i class="icon-share font-red-sunglo"></i>
          <span class="caption-subject font-red-sunglo bold uppercase">Record</span>
        </div>
      </div>
      <div class="portlet-body">
        <div class="clearfix">
          <div class="panel panel-success">
            <table class="table record-information">
              <tbody>
                <tr>
                  <td>Monthly Sales Target</td>
                  <td><?=round($model->sale_target * 100, 2);?>% - Achieve (x/%) - Remaining (y/%)</td>
                </tr>
                <tr>
                  <td>No.of games</td>
                  <td><?=$model->number_of_game;?></td>
                </tr>
                <tr>
                  <td>First order	</td>
                  <td><?=$model->first_order_at;?></td>
                </tr>
                <tr>
                  <td>A.Monthly Sale volume		</td>
                  <td>?</td>
                </tr>
                <tr>
                  <td>A.By plan Sale volume</td>
                  <td>?</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-md-6">
    <!-- BEGIN PORTLET-->
    <div class="portlet light bordered">
      <div class="portlet-title">
        <div class="caption">
          <i class="icon-share font-red-sunglo"></i>
          <span class="caption-subject font-red-sunglo bold uppercase">Potential Leads</span>
        </div>
      </div>
      <div class="portlet-body">
        <div class="clearfix">
          <div class="panel panel-success">
            <table class="table Potential-Leads">
              <thead>
                <tr>
                  <th>Topic</th>
                  <th>Criteria</th>
                  <th>Result</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>Engagement</td>
                  <td><?=round($model->sale_target * 100, 2);?></td>
                  <td><?=round($model->sale_target * 100, 2);?></td>
                  <td><?=round($model->sale_target * 100, 2);?></td>
                </tr>
                <tr>
                  <td rowspan="2">Network</td>
                  <td><?=$model->number_of_game;?></td>
                  <td><?=$model->number_of_game;?></td>
                  <td><?=$model->number_of_game;?></td>
                </tr>
                <tr>
                  <td>xxx</td>
                  <td>xxx</td>
                  <td>xxx</td>
                </tr>
                <tr>
                  <td>Legit account</td>
                  <td><?=$model->first_order_at;?></td>
                  <td><?=$model->first_order_at;?></td>
                  <td><?=$model->first_order_at;?></td>
                </tr>
                <tr>
                  <td colspan="2">Evaluation</td>
                  <td>Score</td>
                  <td>Yes/ No</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-md-6">
    <!-- BEGIN PORTLET-->
    <div class="portlet light bordered">
      <div class="portlet-title">
        <div class="caption">
          <i class="icon-share font-red-sunglo"></i>
          <span class="caption-subject font-red-sunglo bold uppercase">Target Leads</span>
        </div>
      </div>
      <div class="portlet-body">
        <div class="clearfix">
          <div class="panel panel-success">
            <table class="table Target-Leads">
              <thead>
                <tr>
                  <th>Topic</th>
                  <th>Criteria</th>
                  <th>Result</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td rowspan="3">Demand availability</td>
                  <td><?=round($model->sale_target * 100, 2);?></td>
                  <td><?=round($model->sale_target * 100, 2);?></td>
                  <td><?=round($model->sale_target * 100, 2);?></td>
                </tr>
                <tr>
                  <td><?=$model->number_of_game;?></td>
                  <td><?=$model->number_of_game;?></td>
                  <td><?=$model->number_of_game;?></td>
                </tr>
                <tr>
                  <td>xxx</td>
                  <td>xxx</td>
                  <td>xxx</td>
                </tr>
                <tr>
                  <td>Referred</td>
                  <td><?=$model->first_order_at;?></td>
                  <td><?=$model->first_order_at;?></td>
                  <td><?=$model->first_order_at;?></td>
                </tr>
                <tr>
                  <td colspan="2">Evaluation</td>
                  <td>Score</td>
                  <td>Yes/ No</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-md-12">
    <!-- BEGIN PORTLET-->
    <div class="portlet light bordered">
      <div class="portlet-title">
        <div class="caption">
          <i class="icon-share font-red-sunglo"></i>
          <span class="caption-subject font-red-sunglo bold uppercase">Potential Customer</span>
        </div>
      </div>
      <div class="portlet-body">
        <div class="clearfix">
          <div class="panel panel-success">
            <table class="table Potential-Customer">
              <thead>
                <tr>
                  <th colspan="3">Sales Performance</th>
                  <th colspan="2">Growth rate</th>
                  <th>Growth speed</th>
                  <th rowspan="2">Evaluation (1st date)</th>
                </tr>
                <tr>
                  <th>1st month</th>
                  <th>2nd month</th>
                  <th>3rd month</th>
                  <th>G1</th>
                  <th>G2</th>
                  <th>G2-G1</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>xxx</td>
                  <td>xxx</td>
                  <td>xxx</td>
                  <td>xxx</td>
                  <td>xxx</td>
                  <td>xxx</td>
                  <td>xxx</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-md-12">
    <!-- BEGIN PORTLET-->
    <div class="portlet light bordered">
      <div class="portlet-title">
        <div class="caption">
          <i class="icon-share font-red-sunglo"></i>
          <span class="caption-subject font-red-sunglo bold uppercase">Key Customer</span>
        </div>
      </div>
      <div class="portlet-body">
        <div class="clearfix">
          <div class="panel panel-success">
            <table class="table Key-Customer">
              <thead>
                <tr>
                  <th colspan="3">Development</th>
                  <th rowspan="2">Evaluation (1st date)</th>
                </tr>
                <tr>
                  <th>Sales Growth</th>
                  <th>Product Growth</th>
                  <th>% Result/ KPI</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>xxx</td>
                  <td>xxx</td>
                  <td>xxx</td>
                  <td>xxx</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-md-12">
    <!-- BEGIN PORTLET-->
    <div class="portlet light bordered">
      <div class="portlet-title">
        <div class="caption">
          <i class="icon-share font-red-sunglo"></i>
          <span class="caption-subject font-red-sunglo bold uppercase">Loyalty customer</span>
        </div>
      </div>
      <div class="portlet-body">
        <div class="clearfix">
          <div class="panel panel-success">
            <table class="table Loyalty-Customer">
              <tbody>
                <tr>
                  <td>Loyalty customer (6months in row)</td>
                  <td>Yes/No</td>
                </tr>
                <tr>
                  <td>Customer in dangerous (G1, G2<0)</td>
                  <td>Yes/No</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-md-12">
    <!-- BEGIN PORTLET-->
    <div class="portlet light bordered">
      <div class="portlet-title">
        <div class="caption">
          <i class="icon-share font-red-sunglo"></i>
          <span class="caption-subject font-red-sunglo bold uppercase">Contact Log</span>
        </div>
      </div>
      <div class="portlet-body">
        <div class="clearfix">
          <div class="panel panel-success">
            <table class="table Loyalty-Customer">
              <thead>
                <tr>
                  <th>No.</th>
                  <th>Date & Time</th>
                  <th>Reason to contact</th>
                  <th>Customer's response</th>
                  <th>Next Action</th>
                  <th>PIC</th>
                </tr>
                
              </thead>
              <tbody>
                <tr>
                  <td>xxx</td>
                  <td>xxx</td>
                  <td>xxx</td>
                  <td>xxx</td>
                  <td>xxx</td>
                  <td>xxx</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>