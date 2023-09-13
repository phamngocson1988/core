<?php
use dosamigos\chartjs\ChartJs;
use yii\helpers\StringHelper;
?>
<div class="page-bar">
    <ul class="page-breadcrumb">
        <li>
            <a href="index.html">Home</a>
            <i class="fa fa-circle"></i>
        </li>
        <li>
            <span>Dashboard</span>
        </li>
    </ul>
    <div class="page-toolbar">
        <div id="dashboard-report-range" class="pull-right tooltips btn btn-sm" data-container="body" data-placement="bottom" data-original-title="Change dashboard date range">
            <i class="icon-calendar"></i>&nbsp;
            <span class="thin uppercase hidden-xs"></span>&nbsp;
            <i class="fa fa-angle-down"></i>
        </div>
    </div>
</div>
<!-- END PAGE BAR -->
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title"> Admin Dashboard
    <small>statistics, charts, recent events and reports</small>
</h1>
<!-- END PAGE TITLE-->
<!-- END PAGE HEADER-->
<!-- BEGIN DASHBOARD STATS 1-->
<div class="row">
    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
        <a class="dashboard-stat dashboard-stat-v2 blue" href="#">
            <div class="visual">
                <i class="fa fa-comments"></i>
            </div>
            <div class="details">
                <div class="number">
                    <span data-counter="counterup" data-value="<?=number_format($revenue, 1);?>">0</span>/
                    <span data-counter="counterup" data-value="<?=number_format($quantity, 1);?>">0</span>
                </div>
                <div class="desc">  Revenue </div>
            </div>
        </a>
    </div>
    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
        <a class="dashboard-stat dashboard-stat-v2 red" href="#">
            <div class="visual">
                <i class="fa fa-bar-chart-o"></i>
            </div>
            <div class="details">
                <div class="number">
                    <span data-counter="counterup" data-value="<?=number_format($orders);?>">0</span>
                </div>
                <div class="desc"> No. of Orders </div>
            </div>
        </a>
    </div>
    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
        <a class="dashboard-stat dashboard-stat-v2 green" href="#">
            <div class="visual">
                <i class="fa fa-shopping-cart"></i>
            </div>
            <div class="details">
                <div class="number">
                    <span data-counter="counterup" data-value="<?=$games;?>">0</span>
                </div>
                <div class="desc"> No. of Games </div>
            </div>
        </a>
    </div>
    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
        <a class="dashboard-stat dashboard-stat-v2 purple" href="#">
            <div class="visual">
                <i class="fa fa-globe"></i>
            </div>
            <div class="details">
                <div class="number"> 
                    <span data-counter="counterup" data-value="<?=$customers;?>">0</span>
                </div>
                <div class="desc"> No. of Customers </div>
            </div>
        </a>
    </div>
</div>
<div class="clearfix"></div>
<div class="row" id="charts">
    <div class="col-lg-6 col-xs-12 col-sm-12">
        <div class="portlet light bordered">
            <div class="portlet-title">
                <div class="caption">
                    <span class="caption-subject bold uppercase font-dark">Revenue</span>
                </div>
                <div class="actions">
                    <select class="bs-select form-control input-small" data-style="btn-primary" tabindex="-98" id="revenueOption">
                        <option value='today'>Today</option>
                        <option value='lastday'>Last day</option>
                        <option value='week'>Week</option>
                        <option value='month'>Month</option>
                        <option value='custom'>Custom</option>
                    </select>
                </div>
            </div>
            <div class="portlet-body">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-xs-6 col-sm-12">
        <div class="portlet light bordered">
            <div class="portlet-title">
                <div class="caption ">
                    <span class="caption-subject font-dark bold uppercase">Top Customers</span>
                </div>
            </div>
            <div class="portlet-body">
                <?php 
                $topCustomersLabels = array_map(function($customer) {
                    return StringHelper::truncateWords($customer['customer_name'], 4);
                }, $topCustomers);
                $topCustomersDataset = array_map(function($customer) {
                    return $customer['total_price'];
                }, $topCustomers);
                ?>
                <?= ChartJs::widget([
                    'type' => 'bar',
                    'options' => [
                        'height' => 500,
                        'scales' => ['y' => ['beginAtZero' => true]],
                        'responsive' => true
                    ],
                    'data' => [
                        'labels' => $topCustomersLabels,
                        'datasets' => [
                            [
                                'label' => "Top Customers",
                                'backgroundColor' => "rgba(255,99,132,0.2)",
                                'borderColor' => "rgba(255,99,132,1)",
                                'pointBackgroundColor' => "rgba(255,99,132,1)",
                                'pointBorderColor' => "#fff",
                                'pointHoverBackgroundColor' => "#fff",
                                'pointHoverBorderColor' => "rgba(255,99,132,1)",
                                'data' => [$topCustomersDataset]
                            ]
                        ]
                    ]
                ]);
                ?>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-xs-6 col-sm-12">
        <div class="portlet light bordered">
            <div class="portlet-title">
                <div class="caption ">
                    <span class="caption-subject font-dark bold uppercase">Top Games</span>
                </div>
            </div>
            <div class="portlet-body">
            <?php 
                $topGamesLabels = array_map(function($game) {
                    return StringHelper::truncateWords($game['game_title'], 4);
                }, $topGames);
                $topGamesDataset = array_map(function($game) {
                    return $game['total_price'];
                }, $topGames);
                ?>
                
                <?= ChartJs::widget([
                    'type' => 'bar',
                    'options' => [
                        'height' => 500,
                        'scales' => ['y' => ['beginAtZero' => true]],
                        'responsive' => true
                    ],
                    'data' => [
                        'labels' => $topGamesLabels,
                        'datasets' => [
                            [
                                'label' => "Top Games",
                                'backgroundColor' => "rgba(255,99,132,0.2)",
                                'borderColor' => "rgba(255,99,132,1)",
                                'pointBackgroundColor' => "rgba(255,99,132,1)",
                                'pointBorderColor' => "#fff",
                                'pointHoverBackgroundColor' => "#fff",
                                'pointHoverBorderColor' => "rgba(255,99,132,1)",
                                'data' => [$topGamesDataset]
                            ]
                        ]
                    ]
                ]);
                ?>
            </div>
        </div>
    </div>
</div>
<!-- END DASHBOARD STATS 1-->

<?php
$script = <<< JS

// Revenue chart
function getDayNames() {
    const dayNames = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];
    const result = [];
    const today = new Date();
    const dayIndex = today.getDay();
    for (let i = 0; i <= dayIndex; i++) {
    result.push(dayNames[i]);
    }
    return result;
}
const revenueLabels = {
    today: ['Today'],
    lastday: ['Last day'],
    week: getDayNames(),
    month: Array.from({length: new Date().getDate()}).map((x, i) => {
        return String((i + 1) + '/' + (new Date().getMonth() + 1));
    })
};
const revenueChartctx = document.getElementById('revenueChart');
const revenueChartFrame = {
    type: 'bar',
    data: {
        options: { 
            scales: { y: { beginAtZero: true } },
            responsive: true
        },
        datasets: [{
            label: 'Revenue',
            borderWidth: 1,
            borderColor: '#36A2EB',
            backgroundColor: '#9BD0F5'
        }]
    }
};
const buildRevenueChartData = (labels, chartData) => {
    const { type, data } = revenueChartFrame;
    const { datasets } = data;
    const chartDataSets = datasets.map(ds => ({...ds, data: chartData}));
    return {
        type,
        data: {
            ...data,
            labels,
            datasets: chartDataSets
        }
    }
}
const chartHandler = new Chart(revenueChartctx, revenueChartFrame);

$('#revenueOption').on('change', function() {
    const type = $(this).val();
    if (type === 'custom') {
        // show date rage
        return;
    }
    let labels = revenueLabels[type];
    $.ajax({
      url: '/site/report-revenue',
      type: 'POST',
      dataType : 'json',
      data: { type },
      success: function (result, textStatus, jqXHR) {
        const dataChart = buildRevenueChartData(labels, result);
        chartHandler.data = dataChart.data;
        chartHandler.update();
      },
    });
});
$('#revenueOption').trigger('change');


JS;
$this->registerJs($script);
?>