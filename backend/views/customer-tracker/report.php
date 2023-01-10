<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use dosamigos\chartjs\ChartJs;


$reportMonths = [date('Ym', strtotime('-3 month')), date('Ym', strtotime('-2 month')), date('Ym', strtotime('-1 month'))];
$monthlyConversionRate = $form->monthlyConversionRate();
$topTenUser = $form->topTenUsers();
$topTenGame = $form->topTenGames();

$performance = $form->reportPerformance();
$loyaltyPerformance = $form->reportLoyaltyPerformance();
$dangerousPerformance = $form->reportDangerousPerformance();
// month1
$potentialLeadPerformance1 = ArrayHelper::getValue($performance['month1'], -2, []);
$countPotentialLead1 = ArrayHelper::getValue($potentialLeadPerformance1, 'count', 0);

$targetLeadPerformance1 = ArrayHelper::getValue($performance['month1'], -1, []);
$countTargetLead1 = ArrayHelper::getValue($targetLeadPerformance1, 'count', 0);

$normalCustomerPerformance1 = ArrayHelper::getValue($performance['month1'], 1 , []);
$countNormal1 = ArrayHelper::getValue($normalCustomerPerformance1, 'count', 0);
$targetNormal1 = ArrayHelper::getValue($normalCustomerPerformance1, 'target', 0);
$quantityNormal1 = ArrayHelper::getValue($normalCustomerPerformance1, 'quantity', 0);

$potentialCustomerPerformance1 = ArrayHelper::getValue($performance['month1'], 2 , []);
$countPotential1 = ArrayHelper::getValue($potentialCustomerPerformance1, 'count', 0);
$targetPotential1 = ArrayHelper::getValue($potentialCustomerPerformance1, 'target', 0);
$quantityPotential1 = ArrayHelper::getValue($potentialCustomerPerformance1, 'quantity', 0);

$keyCustomerPerformance1 = ArrayHelper::getValue($performance['month1'], 3 , []);
$countKey1 = ArrayHelper::getValue($keyCustomerPerformance1, 'count', 0);
$targetKey1 = ArrayHelper::getValue($keyCustomerPerformance1, 'target', 0);
$quantityKey1 = ArrayHelper::getValue($keyCustomerPerformance1, 'quantity', 0);

$totalCount1 = array_sum([$countNormal1, $countPotential1, $countKey1]);
$totalQuantity1 = array_sum([$quantityNormal1, $quantityPotential1, $quantityKey1]);
$totalTarget1 = array_sum([$targetNormal1, $targetPotential1, $targetKey1]);

$percentNormal1 = $totalQuantity1 ? round(($quantityNormal1 / $totalQuantity1) * 100, 2) . '%' : '-';
$percentPotential1 = $totalQuantity1 ? round(($quantityPotential1 / $totalQuantity1) * 100, 2) . '%' : '-';
$percentKey1 = $totalQuantity1 ? round(($quantityKey1 / $totalQuantity1) * 100, 2) . '%' : '-';

// month2
$potentialLeadPerformance2 = ArrayHelper::getValue($performance['month2'], -2, []);
$countPotentialLead2 = ArrayHelper::getValue($potentialLeadPerformance2, 'count', 0);

$targetLeadPerformance2 = ArrayHelper::getValue($performance['month2'], -1, []);
$countTargetLead2 = ArrayHelper::getValue($targetLeadPerformance2, 'count', 0);

$normalCustomerPerformance2 = ArrayHelper::getValue($performance['month2'], 1 , []);
$countNormal2 = ArrayHelper::getValue($normalCustomerPerformance2, 'count', 0);
$targetNormal2 = ArrayHelper::getValue($normalCustomerPerformance2, 'target', 0);
$quantityNormal2 = ArrayHelper::getValue($normalCustomerPerformance2, 'quantity', 0);

$potentialCustomerPerformance2 = ArrayHelper::getValue($performance['month2'], 2 , []);
$countPotential2 = ArrayHelper::getValue($potentialCustomerPerformance2, 'count', 0);
$targetPotential2 = ArrayHelper::getValue($potentialCustomerPerformance2, 'target', 0);
$quantityPotential2 = ArrayHelper::getValue($potentialCustomerPerformance2, 'quantity', 0);

$keyCustomerPerformance2 = ArrayHelper::getValue($performance['month2'], 3 , []);
$countKey2 = ArrayHelper::getValue($keyCustomerPerformance2, 'count', 0);
$targetKey2 = ArrayHelper::getValue($keyCustomerPerformance2, 'target', 0);
$quantityKey2 = ArrayHelper::getValue($keyCustomerPerformance2, 'quantity', 0);

$totalCount2 = array_sum([$countNormal2, $countPotential2, $countKey2]);
$totalQuantity2 = array_sum([$quantityNormal2, $quantityPotential2, $quantityKey2]);
$totalTarget2 = array_sum([$targetNormal2, $targetPotential2, $targetKey2]);

$percentNormal2 = $totalQuantity2 ? round(($quantityNormal2 / $totalQuantity2) * 100, 2) . '%' : '-';
$percentPotential2 = $totalQuantity2 ? round(($quantityPotential2 / $totalQuantity2) * 100, 2) . '%' : '-';
$percentKey2 = $totalQuantity2 ? round(($quantityKey2 / $totalQuantity2) * 100, 2) . '%' : '-';

// month3
$potentialLeadPerformance3 = ArrayHelper::getValue($performance['month3'], -2, []);
$countPotentialLead3 = ArrayHelper::getValue($potentialLeadPerformance3, 'count', 0);

$targetLeadPerformance3 = ArrayHelper::getValue($performance['month3'], -1, []);
$countTargetLead3 = ArrayHelper::getValue($targetLeadPerformance3, 'count', 0);

$normalCustomerPerformance3 = ArrayHelper::getValue($performance['month3'], 1 , []);
$countNormal3 = ArrayHelper::getValue($normalCustomerPerformance3, 'count', 0);
$targetNormal3 = ArrayHelper::getValue($normalCustomerPerformance3, 'target', 0);
$quantityNormal3 = ArrayHelper::getValue($normalCustomerPerformance3, 'quantity', 0);

$potentialCustomerPerformance3 = ArrayHelper::getValue($performance['month3'], 2 , []);
$countPotential3 = ArrayHelper::getValue($potentialCustomerPerformance3, 'count', 0);
$targetPotential3 = ArrayHelper::getValue($potentialCustomerPerformance3, 'target', 0);
$quantityPotential3 = ArrayHelper::getValue($potentialCustomerPerformance3, 'quantity', 0);

$keyCustomerPerformance3 = ArrayHelper::getValue($performance['month3'], 3 , []);
$countKey3 = ArrayHelper::getValue($keyCustomerPerformance3, 'count', 0);
$targetKey3 = ArrayHelper::getValue($keyCustomerPerformance3, 'target', 0);
$quantityKey3 = ArrayHelper::getValue($keyCustomerPerformance3, 'quantity', 0);

$totalCount3 = array_sum([$countNormal3, $countPotential3, $countKey3]);
$totalQuantity3 = array_sum([$quantityNormal3, $quantityPotential3, $quantityKey3]);
$totalTarget3 = array_sum([$targetNormal3, $targetPotential3, $targetKey3]);

$percentNormal3 = $totalQuantity3 ? round(($quantityNormal3 / $totalQuantity3) * 100, 2) . '%' : '-';
$percentPotential3 = $totalQuantity3 ? round(($quantityPotential3 / $totalQuantity3) * 100, 2) . '%' : '-';
$percentKey3 = $totalQuantity3 ? round(($quantityKey3 / $totalQuantity3) * 100, 2) . '%' : '-';

// Loyalty
$countLoyalty1 = ArrayHelper::getValue($loyaltyPerformance, 'month1.count', 0);
$targetLoyalty1 = ArrayHelper::getValue($loyaltyPerformance, 'month1.target', 0);
$quantityLoyalty1 = ArrayHelper::getValue($loyaltyPerformance, 'month1.quantity', 0);

$countLoyalty2 = ArrayHelper::getValue($loyaltyPerformance, 'month2.count', 0);
$targetLoyalty2 = ArrayHelper::getValue($loyaltyPerformance, 'month2.target', 0);
$quantityLoyalty2 = ArrayHelper::getValue($loyaltyPerformance, 'month2.quantity', 0);

$countLoyalty3 = ArrayHelper::getValue($loyaltyPerformance, 'month3.count', 0);
$targetLoyalty3 = ArrayHelper::getValue($loyaltyPerformance, 'month3.target', 0);
$quantityLoyalty3 = ArrayHelper::getValue($loyaltyPerformance, 'month3.quantity', 0);

$countDangerous1 = ArrayHelper::getValue($dangerousPerformance, 'month1.count', 0);
$countDangerous2 = ArrayHelper::getValue($dangerousPerformance, 'month2.count', 0);
$countDangerous3 = ArrayHelper::getValue($dangerousPerformance, 'month3.count', 0);
?>
<style>
  table .highlight-yellow {
    background-color: #817706;
    color: white;
    font-weight: bold;
  }

  table .highlight-orange {
    background-color: #e59b14;
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

  table .highlight-grey {
    background-color: #8c9299;
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
      <a href="<?=Url::to(['customer-tracker/index'])?>">Quản lý customer tracker</a>
      <i class="fa fa-circle"></i>
    </li>
    <li>
      <span>Report</span>
    </li>
  </ul>
</div>
<!-- END PAGE BAR -->
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title">Report</h1>

<!-- END PAGE TITLE-->
<div class="row">
  <div class="col-md-5">
    <!-- BEGIN PORTLET-->
    <div class="portlet light bordered">
      <div class="portlet-title">
        <div class="caption">
          <i class="icon-share font-red-sunglo"></i>
          <span class="caption-subject font-red-sunglo bold uppercase">MONTHLY CONVERSION MEASUREMENT</span>
        </div>
      </div>
      <div class="portlet-body">
        <div class="clearfix">
          <div class="panel panel-success">
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>Group of Cus</th>
                        <th>1st Month</th>
                        <th>2nd Month</th>
                        <th>3rd Month</th>
                    </tr>
                </thead>
                <tbody>                
                    <tr>
                      <td>Potential Lead</td>
                      <td class="center"><?=$countPotentialLead1;?></td>
                      <td class="center"><?=$countPotentialLead2;?></td>
                      <td class="center"><?=$countPotentialLead3;?></td>
                    </tr>
                    <tr>
                      <td>Target Lead</td>
                      <td class="center"><?=$countTargetLead1;?></td>
                      <td class="center"><?=$countTargetLead2;?></td>
                      <td class="center"><?=$countTargetLead3;?></td>
                    </tr>
                    <tr>
                      <td>Normal Customer</td>
                      <td class="center"><?=$countNormal1;?></td>
                      <td class="center"><?=$countNormal2;?></td>
                      <td class="center"><?=$countNormal3;?></td>
                    </tr>
                    <tr>
                      <td>Potential Customer</td>
                      <td class="center"><?=$countPotential1;?></td>
                      <td class="center"><?=$countPotential2;?></td>
                      <td class="center"><?=$countPotential3;?></td>
                    </tr>
                    <tr>
                      <td>Key Customer</td>
                      <td class="center"><?=$countKey1;?></td>
                      <td class="center"><?=$countKey2;?></td>
                      <td class="center"><?=$countKey3;?></td>
                    </tr>
                    <tr>
                      <td>Loyalty Customer</td>
                      <td class="center"><?=$countLoyalty1;?></td>
                      <td class="center"><?=$countLoyalty2;?></td>
                      <td class="center"><?=$countLoyalty3;?></td>
                    </tr>
                    <tr>
                      <td>Cus "in dangerous"</td>
                      <td class="center"><?=$countDangerous1;?></td>
                      <td class="center"><?=$countDangerous2;?></td>
                      <td class="center"><?=$countDangerous3;?></td>
                    </tr>
                </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-md-7">
    <!-- BEGIN PORTLET-->
    <div class="portlet light bordered">
      <div class="portlet-title">
        <div class="caption">
          <i class="icon-share font-red-sunglo"></i>
          <span class="caption-subject font-red-sunglo bold uppercase">MONTHLY CONVERSION VISUALIZATION</span>
        </div>
      </div>
      <div class="portlet-body">
        <div class="clearfix">
          <div class="panel panel-success">
          <?= ChartJs::widget([
            'type' => 'bar',
            'options' => [
                'height' => 120,
                'width' => 200
            ],
            'data' => [
                'labels' => [
                  'Potential Lead',
                  'Target Lead',
                  'Normal Customer',
                  'Potential Customer',
                  'Key Customer',
                  'Loyalty Customer',
                  'Cus "in dangerous"',
                ],
                'datasets' => [
                    [
                        'label' => 'month 1',
                        'backgroundColor' => "rgba(179,181,198,0.2)",
                        'borderColor' => "rgba(179,181,198,1)",
                        'pointBackgroundColor' => "rgba(179,181,198,1)",
                        'pointBorderColor' => "#fff",
                        'pointHoverBackgroundColor' => "#fff",
                        'pointHoverBorderColor' => "rgba(179,181,198,1)",
                        'data' => [$countPotentialLead1, $countTargetLead1, $countNormal1, $countPotential1, $countKey1, $countLoyalty1, $countDangerous1]
                    ],
                    [
                        'label' => "month 2",
                        'backgroundColor' => "rgba(255,99,132,0.2)",
                        'borderColor' => "rgba(255,99,132,1)",
                        'pointBackgroundColor' => "rgba(255,99,132,1)",
                        'pointBorderColor' => "#fff",
                        'pointHoverBackgroundColor' => "#fff",
                        'pointHoverBorderColor' => "rgba(255,99,132,1)",
                        'data' => [$countPotentialLead2, $countTargetLead2, $countNormal2, $countPotential2, $countKey2, $countLoyalty2, $countDangerous2]
                    ],
                    [
                        'label' => "month 3",
                        'backgroundColor' => "rgba(251,188,4,0.2)",
                        'borderColor' => "rgba(251,188,4,1)",
                        'pointBackgroundColor' => "rgba(251,188,4,1)",
                        'pointBorderColor' => "#fff",
                        'pointHoverBackgroundColor' => "#fff",
                        'pointHoverBorderColor' => "rgba(251,188,4,1)",
                        'data' => [$countPotentialLead3, $countTargetLead3, $countNormal3, $countPotential3, $countKey3, $countLoyalty3, $countDangerous3]
                    ]
                ]
            ]
        ]);
        ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="row">
  <div class="col-md-5">
    <!-- BEGIN PORTLET-->
    <div class="portlet light bordered">
      <div class="portlet-title">
        <div class="caption">
          <i class="icon-share font-red-sunglo"></i>
          <span class="caption-subject font-red-sunglo bold uppercase">MONTHLY CONVERSION RATE</span>
        </div>
      </div>
      <div class="portlet-body">
        <div class="clearfix">
          <div class="panel panel-success">
            <table class="table">
                <thead>
                    <tr>

                        <th>Conversion</th>
                        <th>1st Month</th>
                        <th>2nd Month</th>
                        <th>3rd Month</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($monthlyConversionRate as $key => $value) :?>
                    <tr>
                        <td><?=$key;?></td>
                        <?php foreach ($reportMonths as $yearMonth) : ?>
                        <td class="center"><?=ArrayHelper::getValue($value, $yearMonth, 0);?></td>
                        <?php endforeach;?>
                    </tr>
                    <?php endforeach;?>
                </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-md-7">
    <!-- BEGIN PORTLET-->
    <div class="portlet light bordered">
      <div class="portlet-title">
        <div class="caption">
          <i class="icon-share font-red-sunglo"></i>
          <span class="caption-subject font-red-sunglo bold uppercase">MONTHLY CONVERSION VISUALIZATION</span>
        </div>
      </div>
      <div class="portlet-body">
        <div class="clearfix">
          <div class="panel panel-success">
          <?php 
          $labels = array_keys($monthlyConversionRate);
          foreach ($reportMonths as $ym) {
            $datasets[] = ArrayHelper::getColumn($monthlyConversionRate, function ($element) use ($ym) {
                return ArrayHelper::getValue($element, $ym, 0);
            });
          }

          ?>
          <?= ChartJs::widget([
            'type' => 'bar',
            'options' => [
                'height' => 100,
                'width' => 200
            ],
            'data' => [
                'labels' => array_values($labels),
                'datasets' => [
                    [
                        'label' => 'month1',
                        'backgroundColor' => "rgba(179,181,198,0.2)",
                        'borderColor' => "rgba(179,181,198,1)",
                        'pointBackgroundColor' => "rgba(179,181,198,1)",
                        'pointBorderColor' => "#fff",
                        'pointHoverBackgroundColor' => "#fff",
                        'pointHoverBorderColor' => "rgba(179,181,198,1)",
                        'data' => $datasets[0]
                    ],
                    [
                        'label' => "month2",
                        'backgroundColor' => "rgba(255,99,132,0.2)",
                        'borderColor' => "rgba(255,99,132,1)",
                        'pointBackgroundColor' => "rgba(255,99,132,1)",
                        'pointBorderColor' => "#fff",
                        'pointHoverBackgroundColor' => "#fff",
                        'pointHoverBorderColor' => "rgba(255,99,132,1)",
                        'data' => $datasets[1]
                    ],
                    [
                        'label' => "month3",
                        'backgroundColor' => "rgba(255,99,132,0.2)",
                        'borderColor' => "rgba(255,99,132,1)",
                        'pointBackgroundColor' => "rgba(255,99,132,1)",
                        'pointBorderColor' => "#fff",
                        'pointHoverBackgroundColor' => "#fff",
                        'pointHoverBorderColor' => "rgba(255,99,132,1)",
                        'data' => $datasets[2]
                    ]
                ]
            ]
        ]);
        ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="row">
<div class="col-md-12">
    <!-- BEGIN PORTLET-->
    <div class="portlet light bordered">
      <div class="portlet-body">
        <div class="clearfix">
          <div class="panel panel-success">
            
            <table class="table table-striped table-bordered">
              <thead>
                <tr>
                  <th rowspan="2" class="highlight-red">Month</th>
                  <th colspan="5" class="highlight-orange">1st Month</th>
                  <th colspan="5" class="highlight-yellow">2nd Month</th>
                  <th colspan="5" class="highlight-green">3rd Month</th>
                </tr>
                <tr class="highlight-grey">
                  <th>No. of customers</th>
                  <th>Target</th>
                  <th>Result</th>
                  <th>R/T</th>
                  <th>Percentage</th>
                  <th>No. of customers</th>
                  <th>Target</th>
                  <th>Result</th>
                  <th>R/T</th>
                  <th>Percentage</th>
                  <th>No. of customers</th>
                  <th>Target</th>
                  <th>Result</th>
                  <th>R/T</th>
                  <th>Percentage</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td class="highlight-grey">Normal Customer</td>
                  <td class="center"><?=$countNormal1;?></td>
                  <td class="center"><?=round($targetNormal1, 2);?></td>
                  <td class="center"><?=round($quantityNormal1, 2);?></td>
                  <td class="center"><?=($targetNormal1) ? round(($quantityNormal1 / $targetNormal1) * 100, 2) . '%' : '-';?></td>
                  <td class="center"><?=$percentNormal1;?></td>

                  <td class="center"><?=$countNormal2;?></td>
                  <td class="center"><?=round($targetNormal2, 2);?></td>
                  <td class="center"><?=round($quantityNormal2, 2);?></td>
                  <td class="center"><?=($targetNormal2) ? round(($quantityNormal2 / $targetNormal2) * 100, 2) . '%' : '-';?></td>
                  <td class="center"><?=$percentNormal2;?></td>

                  <td class="center"><?=$countNormal3;?></td>
                  <td class="center"><?=round($targetNormal3, 2);?></td>
                  <td class="center"><?=round($quantityNormal3, 2);?></td>
                  <td class="center"><?=($targetNormal3) ? round(($quantityNormal3 / $targetNormal3) * 100, 2) . '%' : '-';?></td>
                  <td class="center"><?=$percentNormal3;?></td>
                </tr>
                <tr>
                  <td class="highlight-grey">Potential Customer</td>
                  <td class="center"><?=$countPotential1;?></td>
                  <td class="center"><?=round($targetPotential1, 2);?></td>
                  <td class="center"><?=round($quantityPotential1, 2);?></td>
                  <td class="center"><?=($targetPotential1) ? round(($quantityPotential1 / $targetPotential1) * 100, 2) . '%' : '-';?></td>
                  <td class="center"><?=$percentPotential1;?></td>

                  <td class="center"><?=$countPotential2;?></td>
                  <td class="center"><?=round($targetPotential2, 2);?></td>
                  <td class="center"><?=round($quantityPotential2, 2);?></td>
                  <td class="center"><?=($targetPotential2) ? round(($quantityPotential2 / $targetPotential2) * 100, 2) . '%' : '-';?></td>
                  <td class="center"><?=$percentPotential2;?></td>

                  <td class="center"><?=$countPotential3;?></td>
                  <td class="center"><?=round($targetPotential3, 2);?></td>
                  <td class="center"><?=round($quantityPotential3, 2);?></td>
                  <td class="center"><?=($targetPotential3) ? round(($quantityPotential3 / $targetPotential3) * 100, 2) . '%' : '-';?></td>
                  <td class="center"><?=$percentPotential3;?></td>
                </tr>
                <tr>
                  <td class="highlight-grey">Key Customer</td>
                  <td class="center"><?=$countKey1;?></td>
                  <td class="center"><?=round($targetKey1, 2);?></td>
                  <td class="center"><?=round($quantityKey1, 2);?></td>
                  <td class="center"><?=($targetKey1) ? round(($quantityKey1 / $targetKey1) * 100, 2) . '%' : '-';?></td>
                  <td class="center"><?=$percentKey1;?></td>

                  <td class="center"><?=$countKey2;?></td>
                  <td class="center"><?=round($targetKey2, 2);?></td>
                  <td class="center"><?=round($quantityKey2, 2);?></td>
                  <td class="center"><?=($targetKey2) ? round(($quantityKey2 / $targetKey2) * 100, 2) . '%' : '-';?></td>
                  <td class="center"><?=$percentKey2;?></td>

                  <td class="center"><?=$countKey3;?></td>
                  <td class="center"><?=round($targetKey3, 2);?></td>
                  <td class="center"><?=round($quantityKey3, 2);?></td>
                  <td class="center"><?=($targetKey3) ? round(($quantityKey3 / $targetKey3) * 100, 2) . '%' : '-';?></td>
                  <td class="center"><?=$percentKey3;?></td>
                </tr>
                <tr>
                  <td class="highlight-grey">Loyalty Customer</td>
                  <td class="center"><?=round($countLoyalty1, 2);?></td>
                  <td class="center"><?=round($targetLoyalty1, 2);?></td>
                  <td class="center"><?=round($quantityLoyalty1, 2);?></td>
                  <td class="center">-</td>
                  <td class="center">-</td>

                  <td class="center"><?=round($countLoyalty2, 2);?></td>
                  <td class="center"><?=round($targetLoyalty2, 2);?></td>
                  <td class="center"><?=round($quantityLoyalty2, 2);?></td>
                  <td class="center">-</td>
                  <td class="center">-</td>

                  <td class="center"><?=round($countLoyalty3, 2);?></td>
                  <td class="center"><?=round($targetLoyalty3, 2);?></td>
                  <td class="center"><?=round($quantityLoyalty3, 2);?></td>
                  <td class="center">-</td>
                  <td class="center">-</td>
                </tr>
                <tr>
                  <td class="center highlight-red">Total</td>
                  <td class="center"><?=$totalCount1;?></td>
                  <td class="center"><?=$totalQuantity1;?></td>
                  <td class="center"><?=$totalTarget1;?></td>
                  <td class="center"></td>
                  <td class="center"></td>

                  <td class="center"><?=$totalCount2;?></td>
                  <td class="center"><?=$totalQuantity2;?></td>
                  <td class="center"><?=$totalTarget2;?></td>
                  <td class="center"></td>
                  <td class="center"></td>

                  <td class="center"><?=$totalCount3;?></td>
                  <td class="center"><?=$totalQuantity3;?></td>
                  <td class="center"><?=$totalTarget3;?></td>
                  <td class="center"></td>
                  <td class="center"></td>
                </tr>

                
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="row">
    <div class="col-md-5">
        <!-- BEGIN PORTLET-->
        <div class="portlet light bordered">
            <div class="portlet-title">
                <div class="caption">
                <i class="icon-share font-red-sunglo"></i>
                <span class="caption-subject font-red-sunglo bold uppercase">SALES BY TOP 10 OF CUSTOMERS</span>
                </div>
            </div>
            <div class="portlet-body">
                <div class="clearfix">
                <div class="panel panel-success">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Customers</th>
                                <th>Sales Volume</th>
                                <th>Percentage</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($topTenUser['data'] as $no => $value) :?>
                            <tr>
                                <td><?=$no + 1;?></td>
                                <td><?=$value['name'];?></td>
                                <td><?=$value['quantity'];?></td>
                                <td><?=$value['percent'];?>%</td>
                            </tr>
                            <?php endforeach;?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="2" class="right">Total</td>
                                <td><?=$topTenUser['total'];?></td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-7">
        <!-- BEGIN PORTLET-->
        <div class="portlet light bordered">
        <div class="portlet-title">
            <div class="caption">
            <i class="icon-share font-red-sunglo"></i>
            <span class="caption-subject font-red-sunglo bold uppercase">SALES BY TOP 10 OF CUSTOMERS</span>
            </div>
        </div>
        <div class="portlet-body">
            <div class="clearfix">
            <div class="panel panel-success">
            <?php 
            $labels = array_map(function($item) {
                return $item['name'];
            }, $topTenUser['data']);
            
            $datasets = array_map(function($item) {
                return $item['quantity'];
            }, $topTenUser['data']);
            ?>
            <?= ChartJs::widget([
                'type' => 'bar',
                'options' => [
                    'height' => 180,
                    'width' => 200
                ],
                'data' => [
                    'labels' => array_values($labels),
                    'datasets' => [
                        [
                            'label' => "Top Users",
                            'backgroundColor' => "rgba(255,99,132,0.2)",
                            'borderColor' => "rgba(255,99,132,1)",
                            'pointBackgroundColor' => "rgba(255,99,132,1)",
                            'pointBorderColor' => "#fff",
                            'pointHoverBackgroundColor' => "#fff",
                            'pointHoverBorderColor' => "rgba(255,99,132,1)",
                            'data' => $datasets
                        ]
                    ]
                ]
            ]);
            ?>
            </div>
            </div>
        </div>
        </div>
    </div>
</div>
<div class="row">   
    <div class="col-md-5">
        <!-- BEGIN PORTLET-->
        <div class="portlet light bordered">
            <div class="portlet-title">
                <div class="caption">
                <i class="icon-share font-red-sunglo"></i>
                <span class="caption-subject font-red-sunglo bold uppercase">SALES BY TOP 10 OF GAMES</span>
                </div>
            </div>
            <div class="portlet-body">
                <div class="clearfix">
                <div class="panel panel-success">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Customers</th>
                                <th>Sales Volume</th>
                                <th>Percentage</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($topTenGame['data'] as $no => $value) :?>
                            <tr>
                                <td><?=$no + 1;?></td>
                                <td><?=$value['name'];?></td>
                                <td><?=$value['quantity'];?></td>
                                <td><?=$value['percent'];?>%</td>
                            </tr>
                            <?php endforeach;?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="2" class="right">Total</td>
                                <td><?=$topTenGame['total'];?></td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                </div>
            </div>
        </div>
    </div>  
    <div class="col-md-7">
        <!-- BEGIN PORTLET-->
        <div class="portlet light bordered">
        <div class="portlet-title">
            <div class="caption">
            <i class="icon-share font-red-sunglo"></i>
            <span class="caption-subject font-red-sunglo bold uppercase">SALES BY TOP 10 OF GAMES</span>
            </div>
        </div>
        <div class="portlet-body">
            <div class="clearfix">
            <div class="panel panel-success">
            <?php 
            $labels = array_map(function($item) {
                return $item['name'];
            }, $topTenGame['data']);
            $datasets = array_map(function($item) {
                return $item['quantity'];
            }, $topTenGame['data']);

            ?>
            <?= ChartJs::widget([
                'type' => 'bar',
                'options' => [
                    'height' => 180,
                    'width' => 200
                ],
                'data' => [
                    'labels' => $labels,
                    'datasets' => [
                        [
                            'label' => 'Top Games',
                            'backgroundColor' => "rgba(255,99,132,0.2)",
                            'borderColor' => "rgba(255,99,132,1)",
                            'pointBackgroundColor' => "rgba(255,99,132,1)",
                            'pointBorderColor' => "#fff",
                            'pointHoverBackgroundColor' => "#fff",
                            'pointHoverBorderColor' => "rgba(255,99,132,1)",
                            'data' => $datasets
                        ]
                    ]
                ]
            ]);
            ?>
            </div>
            </div>
        </div>
        </div>
    </div>   
</div>
