<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use dosamigos\datepicker\DatePicker;
use dosamigos\datepicker\DateRangePicker;
use yii\web\JsExpression;
use common\widgets\CheckboxInput;
use common\components\helpers\TimeElapsed;
use backend\models\CustomerTracker;
?>
<style>
  .general-information tr>td:first-child, 
  .personal-information tr>td:first-child,
  .record-information tr>td:first-child {
    background-color: #061932;
    color: white;
    font-weight: bold;
  }
  .general-information tr td:not(:first-child), 
  .personal-information tr td:not(:first-child),
  .record-information tr td:not(:first-child) {
    inline-size: 70%;
    word-break: break-all;
  }

  table .highlight-yellow {
    background-color: #817706;
    color: white;
    font-weight: bold;
  }

  table .highlight-green {
    background-color: #068154;
    color: white;
    font-weight: bold;
  }

  table .highlight-red {
    background-color: #810c06;
    color: white;
    font-weight: bold;
  }

  table .highlight-blue {
    background-color: #337ab7;
    color: white;
    font-weight: bold;
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
  <div class="page-toolbar">
    <div class="btn">
        <a href="<?=Url::to(['customer-tracker/edit', 'id' => $model->id]);?>" class="btn green btn-sm btn-outline"> Edit
            <i class="fa fa-pencil"></i>
        </a>
    </div>
</div>
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
                  <td> <?=$model->link;?> </td>
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
                <tr>
                  <td>1st order date</td>
                  <td><?=$model->first_order_at;?> </td>
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
                  <td>
                    <?php if ($model->sale_target) : ?>
                    <?=$model->sale_target;?> - Achieve (<?=round($model->kpi_growth * 100, 2);?>%) - Remaining (<?=100 - round($model->kpi_growth * 100, 2);?>%)
                    <?php endif;?>
                  </td>
                </tr>
                <tr>
                  <td>No.of games</td>
                  <td>
                    <a href="#game-list-modal" data-toggle="modal"><?=$form->getNumberOfGames();?></a>
                  </td>
                </tr>
                <tr>
                  <td>First order	</td>
                  <td><?=$model->first_order_at;?></td>
                </tr>
                <tr>
                  <td>A.Monthly Sale volume		</td>
                  <td><?=$model->monthly_sale_volumn;?><td>
                </tr>
                <tr>
                  <td>A.By plan Sale volume</td>
                  <td><?=$model->daily_sale_volumn;?><td>
                </tr>
                <tr>
                  <td>Status</td>
                  <td><?=$model->customer_tracker_status ? 'YES' : 'NO';?></td>
                </tr>
                <tr>
                  <td>Monthly Status Customer</td>
                  <td><?=$model->customer_tracker_status ? 'YES' : 'NO';?></td>
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
                <tr class="highlight-yellow">
                  <th>Topic</th>
                  <th>Criteria</th>
                  <th>Result</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td class="highlight-yellow">Engagement</td>
                  <td><?=CustomerTracker::getQuestionTitle('question_1');?></td>
                  <td><?=$model->question_1 ? 'YES' : 'NO';?></td>
                  <td> Update</td>
                </tr>
                <tr>
                  <td class="highlight-yellow" rowspan="2">Network</td>
                  <td><?=CustomerTracker::getQuestionTitle('question_2');?></td>
                  <td><?=$model->question_2 ? 'YES' : 'NO';?></td>
                  <td> Update</td>
                </tr>
                <tr>
                  <td><?=CustomerTracker::getQuestionTitle('question_3');?></td>
                  <td><?=$model->question_3 ? 'YES' : 'NO';?></td>
                  <td> Update</td>
                </tr>
                <tr>
                  <td class="highlight-yellow">Legit account</td>
                  <td><?=CustomerTracker::getQuestionTitle('question_4');?></td>
                  <td><?=$model->question_4 ? 'YES' : 'NO';?></td>
                  <td> Update</td>
                </tr>
                <tr class="highlight-yellow">
                  <td colspan="2">Evaluation</td>
                  <td><?=$model->calculatePointPotential();?></td>
                  <td><?=$model->is_potential ? 'YES' : 'NO';?></td>
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
                <tr class="highlight-yellow">
                  <th>Topic</th>
                  <th>Criteria</th>
                  <th>Result</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td class="highlight-yellow" rowspan="4">Demand availability</td>
                  <td><?=CustomerTracker::getQuestionTitle('question_5');?></td>
                  <td><?=$model->question_5 ? 'YES' : 'NO';?></td>
                  <td> Update</td>
                </tr>
                <tr>
                  <td><?=CustomerTracker::getQuestionTitle('question_6');?></td>
                  <td><?=$model->question_6 ? 'YES' : 'NO';?></td>
                  <td> Update</td>
                </tr>
                <tr>
                  <td><?=CustomerTracker::getQuestionTitle('question_7');?></td>
                  <td><?=$model->question_7 ? 'YES' : 'NO';?></td>
                  <td> Update</td>
                </tr>
                <tr>
                  <td><?=CustomerTracker::getQuestionTitle('question_8');?></td>
                  <td><?=$model->question_8 ? 'YES' : 'NO';?></td>
                  <td> Update</td>
                </tr>
                <tr>
                  <td class="highlight-yellow">Referred</td>
                  <td><?=CustomerTracker::getQuestionTitle('question_9');?></td>
                  <td><?=$model->question_9 ? 'YES' : 'NO';?></td>
                  <td> Update</td>
                </tr>
                <tr class="highlight-yellow">
                  <td colspan="2">Evaluation</td>
                  <td><?=$model->calculatePointTarget();?></td>
                  <td><?=$model->is_target ? 'YES' : 'NO';?></td>
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
          <span class="caption-subject font-red-sunglo bold uppercase">Sales Performance</span>
        </div>
      </div>
      <div class="portlet-body">
        <div class="clearfix">
          <div class="panel panel-success">
            <table class="table Sales-Performance">
              <thead>
                <tr class="highlight-green">
                  <th colspan="3">Normal Customer</th>
                  <th colspan="2">Growth rate</th>
                  <th>Growth speed</th>
                  <th colspan="3">Development</th>
                </tr>
                <tr>
                  <th>1st month</th>
                  <th>2nd month</th>
                  <th>3rd month</th>
                  <th>G1</th>
                  <th>G2</th>
                  <th>G2-G1</th>
                  <th>Sales Growth</th>
                  <th>Product Growth</th>
                  <th>% Result/ KPI</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td class="center"><?=round($model->sale_month_1, 2);?></td>
                  <td class="center"><?=round($model->sale_month_2, 2);?></td>
                  <td class="center"><?=round($model->sale_month_3, 2);?></td>
                  <td class="center"><?=round($model->growth_rate_1, 2);?></td>
                  <td class="center"><?=round($model->growth_rate_2, 2);?></td>
                  <td class="center"><?=round($model->growth_speed, 2);?></td>
                  <td class="center"><?=round($model->sale_growth, 2);?></td>
                  <td class="center"><?=round($model->product_growth, 2);?></td>
                  <td class="center"><?=round($model->kpi_growth * 100, 2);?>%</td>
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
                <tr class="highlight-blue">
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

<div class="modal fade" id="game-list-modal" tabindex="-1" role="basic" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
        <h4 class="modal-title">Danh sách game</h4>
      </div>
      <div class="modal-body" style="height: 200px; position: relative; overflow: auto; display: block;"> 
        <?php
          $games = $form->getListOfGames();
        ?>
        <ul>
          <?php foreach ($games as $game) : ?>
          <li><?=sprintf("%s: %s", $game['game_title'], round($game['quantity'], 2));?></li>
          <?php endforeach;?>
        </ul>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn dark btn-outline" data-dismiss="modal">Close</button>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
