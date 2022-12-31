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
