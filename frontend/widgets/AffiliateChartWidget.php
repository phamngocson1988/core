<?php
namespace frontend\widgets;

use Yii;
use yii\base\Widget;
use yii\helpers\ArrayHelper;

class AffiliateChartWidget extends Widget
{
    protected $chartService;

    public function run()
    {
        $this->chartService = new \frontend\forms\FetchAffiliateCommissionChartForm(['user_id' => Yii::$app->user->id]);
        $this->registerClientScript();
        return $this->render('affiliate_chart');
    }

    protected function getScriptCode()
    {
        $service = $this->chartService;
        $labels = $service->getDateRange();
        $data = $service->getChartData();
        $dataByDate = ArrayHelper::map($data, 'report_date', 'commission');

        // Daily
        $reportByDate = [];
        foreach ($labels as $date) {
            $reportByDate[$date] = ArrayHelper::getValue($dataByDate, $date, 0);
        }

        $dailylabels = array_map(function($label) {
            return sprintf("'%s'", $label);
        }, $labels);
        $dailyDataByDate = array_map(function($data) {
            return sprintf("'%s'", $data);
        }, $reportByDate);

        $dailylabelString = implode(",", $dailylabels);
        $dailytDataString = implode(",", $dailyDataByDate);

        // Monthly
        // $dataByMonth = ArrayHelper::map($data, 'report_date', 'commission', 'report_month');
        // $months = $service->getMonthRange();
        // $reportByMonth = [];
        // foreach ($months as $date) {
        //     $month = date('m', strtotime($date));
        //     $reportByMonth[$date] = array_sum(ArrayHelper::getValue($dataByMonth, $month, []));
        // }
        // $monthlytDataString = implode(",", $reportByMonth);

        return "
var ctx = document.getElementById('myChart');
var myLineChart = new Chart(ctx, {
type: 'line',
data: {
  labels: [$dailylabelString],
  datasets: [{
      label: '# Daily',
      data: [$dailytDataString],
      backgroundColor: [
        'rgba(255, 99, 132, 0.2)',
        'rgba(54, 162, 235, 0.2)',
        'rgba(255, 206, 86, 0.2)',
        'rgba(75, 192, 192, 0.2)',
        'rgba(153, 102, 255, 0.2)',
        'rgba(255, 159, 64, 0.2)'
      ],
      borderColor: [
        'rgba(255, 99, 132, 1)',
        'rgba(54, 162, 235, 1)',
        'rgba(255, 206, 86, 1)',
        'rgba(75, 192, 192, 1)',
        'rgba(153, 102, 255, 1)',
        'rgba(255, 159, 64, 1)'
      ],
      borderWidth: 1
    },
  ]
},
options: {
  scales: {
    yAxes: [{
      ticks: {
        beginAtZero: true
      }
    }]
  }
},
});
";
    }

    protected function registerClientScript()
    {
        $js = [];
        $view = $this->getView();
        $js = $this->getScriptCode();
        $view->registerJs($js);
    }


}