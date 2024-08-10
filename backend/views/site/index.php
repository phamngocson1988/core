<?php
use yii\helpers\StringHelper;
$this->registerJsFile('https://cdn.jsdelivr.net/npm/chart.js', ['depends' => ['\yii\web\JqueryAsset']]);
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
        <div id="dashboard-report-range1" class="pull-right tooltips btn btn-sm" data-container="body" data-placement="bottom" data-original-title="Change dashboard date range">
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
                    <span id="total_revenue">0</span>/
                    <span id="total_quantity">0</span>
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
                    <span id="total_order">0</span>
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
                    <span id="total_game">0</span>
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
                    <span id="total_customer">0</span>
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
            </div>
            <div class="portlet-body">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>
    </div>
    <div class="col-lg-6 col-xs-6 col-sm-12">
        <div class="portlet light bordered">
            <div class="portlet-title">
                <div class="caption ">
                    <span class="caption-subject font-dark bold uppercase">Top Customers</span>
                </div>
            </div>
            <div class="portlet-body">
                <canvas id="topCustomerChart"></canvas>
            </div>
        </div>
    </div>
    <div class="col-lg-6 col-xs-6 col-sm-12">
        <div class="portlet light bordered">
            <div class="portlet-title">
                <div class="caption ">
                    <span class="caption-subject font-dark bold uppercase">Top Games</span>
                </div>
            </div>
            <div class="portlet-body">
                <canvas id="topGameChart"></canvas>
            </div>
        </div>
    </div>
</div>
<!-- END DASHBOARD STATS 1-->

<?php
$script = <<< JS

// Revenue chart
const revenueLabels = {
    'Today': () => ['Today'],
    'Last Day': () => ['Last Day'],
    'Last Week': () => [6,5,4,3,2,1,0].map(num => moment().subtract('days', num).format('dddd')),
    'Last Month': () => [
        'Tuần 1',
        'Tuần 2',
        'Tuần 3',
        'Tuần 4'
    ],
    'Last 3 Months': () => [
        moment().subtract('months', 2).format('MM-YYYY'),
        moment().subtract('months', 1).format('MM-YYYY'),
        moment().format('MM-YYYY')
    ], // last 3 months
    'Last 6 Months': () => [
        moment().subtract('months', 5).format('MM-YYYY'),
        moment().subtract('months', 4).format('MM-YYYY'),
        moment().subtract('months', 3).format('MM-YYYY'),
        moment().subtract('months', 2).format('MM-YYYY'),
        moment().subtract('months', 1).format('MM-YYYY'),
        moment().format('MM-YYYY')
    ], // last 6 months
    'Custom': (startDate, endDate) => {
        let dates = [];

        let currDate = moment(startDate).startOf('day');
        let lastDate = moment(endDate).startOf('day');

        do {
            dates.push(moment(currDate).format('DD-MM'));
        } while(currDate.add(1, 'days').diff(lastDate) <= 0)

        return dates;
    }
};
const revenueChartctx = document.getElementById('revenueChart');
const topCustomerChartctx = document.getElementById('topCustomerChart');
const topGameChartctx = document.getElementById('topGameChart');
const barChartFrame = {
    type: 'bar',
    data: {
        options: { 
            scales: { y: { beginAtZero: true } },
            responsive: true
        },
        datasets: [{
            label: ' ',
            borderWidth: 1,
            borderColor: '#36A2EB',
            backgroundColor: '#9BD0F5'
        }]
    }
};
const buildBarChartData = (chartLabel, labels, chartData) => {
    const { type, data } = barChartFrame;
    const { datasets } = data;
    const chartDataSets = datasets.map(ds => ({...ds, label: chartLabel, data: chartData}));
    return {
        type,
        data: {
            ...data,
            labels: [...labels],
            datasets: chartDataSets
        }
    }
}
const revenueChartHandler = new Chart(revenueChartctx, barChartFrame);
const topCustomerChartHandler = new Chart(topCustomerChartctx, barChartFrame);
const topGameChartHandler = new Chart(topGameChartctx, barChartFrame);

// Update all chart
const updateCharts = (type, start, end) => {
    $.ajax({
      url: '/site/report-revenue',
      type: 'POST',
      dataType : 'json',
      data: { type, start, end },
      success: function (result, textStatus, jqXHR) {
        const revenueChartLabels = type === 'Custom' ? revenueLabels[type](start, end) : revenueLabels[type]();
        console.log('Revenue Labels', revenueChartLabels);
        const revenueChartData = buildBarChartData('Revenue', revenueChartLabels, result.revenue);
        revenueChartHandler.data = revenueChartData.data;
        revenueChartHandler.update();

        const topCustomerLabels = result.topCustomers.map(customer => customer.customer_name);
        const topCustomerValues = result.topCustomers.map(customer => customer.quantity);
        const topCustomerChartData = buildBarChartData('Top Customers', topCustomerLabels, topCustomerValues);
        console.log('top customer', topCustomerLabels, topCustomerValues, topCustomerChartData);
        topCustomerChartHandler.data = topCustomerChartData.data;
        topCustomerChartHandler.update();

        const topGameLabels = result.topGames.map(game => game.game_title);
        const topGameValues = result.topGames.map(game => game.quantity);
        const topGameChartData = buildBarChartData('Top Games', topGameLabels, topGameValues);
        console.log('top game', topGameLabels, topGameValues, topGameChartData);
        topGameChartHandler.data = topGameChartData.data;
        topGameChartHandler.update();

        $('#total_revenue').text(result.other.revenue ? Number(result.other.revenue).toFixed(0) : 0);
        $('#total_quantity').text(result.other.quantity ? Number(result.other.quantity).toFixed(1) : 0)
        $('#total_order').text(result.other.orders || 0)
        $('#total_game').text(result.other.games || 0)
        $('#total_customer').text(result.other.customers || 0)
      },
    });
}


// Date range picker
$('#dashboard-report-range1').daterangepicker({
    "ranges": {
        'Today': [moment(), ,moment()],
        'Last Day': [moment().subtract('days', 1), moment()],
        'Last Week': [moment().subtract('days', 6), moment()],
        'Last Month': [moment().startOf('week').subtract(21,'days'), moment()], // last 4 weeks
        'Last 3 Months': [moment().subtract('months', 2).startOf('month'), moment().endOf('month')], // last 3 months
        'Last 6 Months': [moment().subtract('months', 5).startOf('month'), moment().endOf('month')], // last 6 months
    },
    "showCustomRangeLabel": false,
    "locale": {
        "format": "DD/MM/YYYY",
        "separator": " - ",
        "applyLabel": "Apply",
        "cancelLabel": "Cancel",
        "fromLabel": "From",
        "toLabel": "To",
        "customRangeLabel": "Custom",
        "daysOfWeek": [
            "Su",
            "Mo",
            "Tu",
            "We",
            "Th",
            "Fr",
            "Sa"
        ],
        "monthNames": [
            "January",
            "February",
            "March",
            "April",
            "May",
            "June",
            "July",
            "August",
            "September",
            "October",
            "November",
            "December"
        ],
        "firstDay": 0,
    },
    startDate: moment().startOf('week').subtract(21,'days').format('DD/MM/YYYY'), 
    endDate: moment().format('DD/MM/YYYY'),
    opens: (App.isRTL() ? 'right' : 'left'),
    }, function(start, end, label) {
        if ($('#dashboard-report-range1').attr('data-display-range') != '0') {
            $('#dashboard-report-range1 span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
        }
        updateCharts(label, start.format('YYYY-MM-DD'), end.format('YYYY-MM-DD'));
    });
    if ($('#dashboard-report-range1').attr('data-display-range') != '0') {
    $('#dashboard-report-range1 span').html(moment().startOf('week').subtract(21,'days').format('MMMM D, YYYY') + ' - ' + moment().format('MMMM D, YYYY'));
}
$('#dashboard-report-range1').show();
updateCharts('Last Month', moment().startOf('week').subtract(21,'days').format('YYYY-MM-DD'), moment().format('YYYY-MM-DD'))
JS;
$this->registerJs($script);
?>