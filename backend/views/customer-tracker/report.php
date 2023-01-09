<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use dosamigos\chartjs\ChartJs;


$reportMonths = [date('Ym', strtotime('-3 month')), date('Ym', strtotime('-2 month')), date('Ym', strtotime('-1 month'))];
$monthlyConversionMeasurementData = $form->monthlyConversionMeasurement();
$monthlyConversionRate = $form->monthlyConversionRate();
$topTenUser = $form->topTenUsers();
$topTenGame = $form->topTenGames();
$performance = $form->reportPerformance();
?>
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
            <table class="table">
                <thead>
                    <tr>
                        <th>Group of Cus</th>
                        <th>1st Month</th>
                        <th>2nd Month</th>
                        <th>3rd Month</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($monthlyConversionMeasurementData as $key => $value) :?>
                    <tr>
                        <td><?=$value['label'];?></td>
                        <?php foreach ($reportMonths as $yearMonth) : ?>
                        <td class="center"><?=ArrayHelper::getValue($value['data'], $yearMonth, 0);?></td>
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
          $labels = array_map(function($item) {
            return $item['label'];
          }, $monthlyConversionMeasurementData);
          foreach ($reportMonths as $ym) {
            $datasets[] = ArrayHelper::getColumn($monthlyConversionMeasurementData, function ($element) use ($ym) {
                return ArrayHelper::getValue($element['data'], $ym, 0);
            });
          }
          ?>
          <?= ChartJs::widget([
            'type' => 'bar',
            'options' => [
                'height' => 120,
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
            <?php 
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
            ?>
            <table class="table table-striped table-bordered table-hover table-checkable dataTable no-footer">
              <thead>
                <tr class="highlight-blue">
                  <th rowspan="2">Month</th>
                  <th colspan="5">1st Month</th>
                  <th colspan="5">2nd Month</th>
                  <th colspan="5">3rd Month</th>
                </tr>
                <tr>
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
                  <td>Normal Customer</td>
                  <td><?=$countNormal1;?></td>
                  <td><?=round($targetNormal1, 2);?></td>
                  <td><?=round($quantityNormal1, 2);?></td>
                  <td><?=($targetNormal1) ? round(($quantityNormal1 / $targetNormal1) * 100, 2) . '%' : '-';?></td>
                  <td>xxx</td>

                  <td><?=$countNormal2;?></td>
                  <td><?=round($targetNormal2, 2);?></td>
                  <td><?=round($quantityNormal2, 2);?></td>
                  <td><?=($targetNormal2) ? round(($quantityNormal2 / $targetNormal2) * 100, 2) . '%' : '-';?></td>
                  <td>xxx</td>

                  <td><?=$countNormal3;?></td>
                  <td><?=round($targetNormal3, 2);?></td>
                  <td><?=round($quantityNormal3, 2);?></td>
                  <td><?=($targetNormal3) ? round(($quantityNormal3 / $targetNormal3) * 100, 2) . '%' : '-';?></td>
                  <td>xxx</td>
                </tr>
                <tr>
                  <td>Potential Customer</td>
                  <td><?=$countPotential1;?></td>
                  <td><?=round($targetPotential1, 2);?></td>
                  <td><?=round($quantityPotential1, 2);?></td>
                  <td><?=($targetPotential1) ? round(($quantityPotential1 / $targetPotential1) * 100, 2) . '%' : '-';?></td>
                  <td>xxx</td>

                  <td><?=$countPotential2;?></td>
                  <td><?=round($targetPotential2, 2);?></td>
                  <td><?=round($quantityPotential2, 2);?></td>
                  <td><?=($targetPotential2) ? round(($quantityPotential2 / $targetPotential2) * 100, 2) . '%' : '-';?></td>
                  <td>xxx</td>

                  <td><?=$countPotential3;?></td>
                  <td><?=round($targetPotential3, 2);?></td>
                  <td><?=round($quantityPotential3, 2);?></td>
                  <td><?=($targetPotential3) ? round(($quantityPotential3 / $targetPotential3) * 100, 2) . '%' : '-';?></td>
                  <td>xxx</td>
                </tr>
                <tr>
                  <td>Key Customer</td>
                  <td><?=$countKey1;?></td>
                  <td><?=round($targetKey1, 2);?></td>
                  <td><?=round($quantityKey1, 2);?></td>
                  <td><?=($targetKey1) ? round(($quantityKey1 / $targetKey1) * 100, 2) . '%' : '-';?></td>
                  <td>xxx</td>

                  <td><?=$countKey2;?></td>
                  <td><?=round($targetKey2, 2);?></td>
                  <td><?=round($quantityKey2, 2);?></td>
                  <td><?=($targetKey2) ? round(($quantityKey2 / $targetKey2) * 100, 2) . '%' : '-';?></td>
                  <td>xxx</td>

                  <td><?=$countKey3;?></td>
                  <td><?=round($targetKey3, 2);?></td>
                  <td><?=round($quantityKey3, 2);?></td>
                  <td><?=($targetKey3) ? round(($quantityKey3 / $targetKey3) * 100, 2) . '%' : '-';?></td>
                  <td>xxx</td>
                </tr>
                <tr>
                  <td>Loyalty Customer</td>
                  <td>xxx</td>
                  <td>xxx</td>
                  <td>xxx</td>
                  <td>xxx</td>
                  <td>xxx</td>

                  <td>xxx</td>
                  <td>xxx</td>
                  <td>xxx</td>
                  <td>xxx</td>
                  <td>xxx</td>

                  <td>xxx</td>
                  <td>xxx</td>
                  <td>xxx</td>
                  <td>xxx</td>
                  <td>xxx</td>
                </tr>
                <tr>
                  <td>Total</td>
                  <td>xxx</td>
                  <td>xxx</td>
                  <td>xxx</td>
                  <td>xxx</td>
                  <td>xxx</td>

                  <td>xxx</td>
                  <td>xxx</td>
                  <td>xxx</td>
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
