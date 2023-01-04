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
  /* .general-information tr>td:first-child, 
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
  } */

  table .highlight-dark {
    background-color: #061932;
    color: white;
    font-weight: bold;
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

  /* table, th, td {
    border: 1px solid black;
  } */
  
</style>
<div class="page-bar">
  <ul class="page-breadcrumb">
    <li>
      <a href="/"><?=Yii::t('app', 'home')?></a>
      <i class="fa fa-circle"></i>
    </li>
    <li>
      <a href="<?=Url::to(['customer-tracker/index'])?>">Quản lý customer tracker</a>
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
                  <td class="highlight-dark">Index</td>
                  <td>#<?=$model->id;?> </td>
                </tr>
                <tr>
                  <td class="highlight-dark">Name</td>
                  <td> <?=$model->name;?> </td>
                </tr>
                <tr>
                  <td class="highlight-dark">Link Account</td>
                  <td> <?=$model->link;?> </td>
                </tr>
                <tr>
                  <td class="highlight-dark">Account Manger</td>
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
                  <td class="highlight-dark">Nationality</td>
                  <td><?=$model->getCountryName();?></td>
                </tr>
                <tr>
                  <td class="highlight-dark">Phone</td>
                  <td><?=$model->phone;?></td>
                </tr>
                <tr>
                  <td class="highlight-dark">Email</td>
                  <td><?=$model->email;?></td>
                </tr>
                <tr>
                  <td class="highlight-dark">Channel</td>
                  <td><?=$model->getChannelLabels();?></td>
                </tr>
                <tr>
                  <td class="highlight-dark">Contacts</td>
                  <td><?=$model->getContactLabels();?></td>
                </tr>
                <tr>
                  <td class="highlight-dark">Registration date</td>
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
                  <td class="highlight-dark">Monthly Sales Target (Last month)</td>
                  <td class="center">
                    <?php if ($model->getSaleTarget(date('Ym', strtotime('last month')))) : ?>
                    <div class="progress">
                      <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="<?=min(1, $model->kpi_growth);?>" aria-valuemin="0" aria-valuemax="100" style="width: <?=min(100, $model->kpi_growth * 100);?>%">
                        <?=round($model->kpi_growth * 100, 2);?>%
                      </div>
                    </div>
                    <?php else :?>
                    <div class="progress">
                      <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%">
                        0%
                      </div>
                    </div>
                    <?php endif;?>
                  </td>
                </tr>
                <tr>
                  <td class="highlight-dark">Monthly Sales Target (This month)</td>
                  <td class="center">
                    <?php 
                    $currentSaleTarget = $model->getCurrentSaleTarget();
                    if ($currentSaleTarget) : 
                      $currentSale = $form->getCurrentSale();
                      $kpiGrowth = round($currentSale / $currentSaleTarget, 2);
                    ?>
                    <div class="progress">
                      <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="<?=min(1, $kpiGrowth);?>" aria-valuemin="0" aria-valuemax="100" style="width: <?=min(100, $kpiGrowth * 100);?>%">
                        <?=round($kpiGrowth * 100, 2);?>%
                      </div>
                    </div>
                    <?php else :?>
                    <div class="progress">
                      <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%">
                        0%
                      </div>
                    </div>
                    <?php endif;?>
                  </td>
                </tr>
                <tr>
                  <td class="highlight-dark">No.of games</td>
                  <td>
                    <a href="#game-list-modal" data-toggle="modal"><?=$form->getNumberOfGames();?></a>
                  </td>
                </tr>
                <tr>
                  <td class="highlight-dark">First order	</td>
                  <td><?=$model->first_order_at;?></td>
                </tr>
                <tr>
                  <td class="highlight-dark">A.Monthly Sale volume		</td>
                  <td><?=$model->monthly_sale_volumn;?><td>
                </tr>
                <tr>
                  <td class="highlight-dark">A. Daily Sale volume</td>
                  <td><?=$model->daily_sale_volumn;?><td>
                </tr>
                <tr>
                  <td class="highlight-dark">Status</td>
                  <td><?=$model->getCustomerTrackerLabel();?></td>
                </tr>
                <tr>
                  <td class="highlight-dark">Monthly Status Customer</td>
                  <td><?=$model->getCustomerMonthlyLabel();?></td>
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
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td class="highlight-yellow">Engagement</td>
                  <td><?=CustomerTracker::getQuestionTitle('question_1');?></td>
                  <td><?=$model->question_1 ? 'YES' : 'NO';?></td>
                </tr>
                <tr>
                  <td class="highlight-yellow" rowspan="2">Network</td>
                  <td><?=CustomerTracker::getQuestionTitle('question_2');?></td>
                  <td><?=$model->question_2 ? 'YES' : 'NO';?></td>
                </tr>
                <tr>
                  <td><?=CustomerTracker::getQuestionTitle('question_3');?></td>
                  <td><?=$model->question_3 ? 'YES' : 'NO';?></td>
                </tr>
                <tr>
                  <td class="highlight-yellow">Legit account</td>
                  <td><?=CustomerTracker::getQuestionTitle('question_4');?></td>
                  <td><?=$model->question_4 ? 'YES' : 'NO';?></td>
                </tr>
                <tr class="highlight-yellow">
                  <td>Evaluation</td>
                  <td class="center"><?=$model->calculatePointPotential();?></td>
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
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td class="highlight-yellow" rowspan="4">Demand availability</td>
                  <td><?=CustomerTracker::getQuestionTitle('question_5');?></td>
                  <td><?=$model->question_5 ? 'YES' : 'NO';?></td>
                </tr>
                <tr>
                  <td><?=CustomerTracker::getQuestionTitle('question_6');?></td>
                  <td><?=$model->question_6 ? 'YES' : 'NO';?></td>
                </tr>
                <tr>
                  <td><?=CustomerTracker::getQuestionTitle('question_7');?></td>
                  <td><?=$model->question_7 ? 'YES' : 'NO';?></td>
                </tr>
                <tr>
                  <td><?=CustomerTracker::getQuestionTitle('question_8');?></td>
                  <td><?=$model->question_8 ? 'YES' : 'NO';?></td>
                </tr>
                <tr>
                  <td class="highlight-yellow">Referred</td>
                  <td><?=CustomerTracker::getQuestionTitle('question_9');?></td>
                  <td><?=$model->question_9 ? 'YES' : 'NO';?></td>
                </tr>
                <tr class="highlight-yellow">
                  <td>Evaluation</td>
                  <td class="center"><?=$model->calculatePointTarget();?></td>
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
        <a href='<?=Url::to(['customer-tracker/add-action', 'id' => $model->id]);?>' data-target="#add-comment" class="btn btn-xs grey-salsa tooltips" data-pjax="0" data-container="body" data-original-title="Thêm ghi chú" data-toggle="modal" >Contact Log</i></a>

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
        <table class="table">
          <thead>
            <tr>
              <th>Game</th>
              <th>First Order</th>
              <th>Quantity</th>
            </tr>
          </thead>
          <tbody>
          <?php foreach ($games as $game) : ?>
            <tr>
              <td><?=$game['game_title'];?></td>
              <td class="center"><?=$game['created_at'];?></td>
              <td class="center"><?=round($game['quantity'], 2);?></td>
            </tr>
          <?php endforeach;?>
          <tbody>
          </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn dark btn-outline" data-dismiss="modal">Close</button>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>

<div class="modal fade" id="add-comment" tabindex="-1" role="basic" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<?php
$script = <<< JS
$(document).on('submit', 'body #add-comment-form', function(e) {
  e.preventDefault();
  e.stopImmediatePropagation();
  var form = $(this);
  form.unbind('submit');
  $.ajax({
    url: form.attr('action'),
    type: form.attr('method'),
    dataType : 'json',
    data: form.serialize(),
    success: function (result, textStatus, jqXHR) {
      console.log(result);
      if (result.status) {
        location.reload();
      }
    },
  });
  return false;
});
JS;
$this->registerJs($script);
?>