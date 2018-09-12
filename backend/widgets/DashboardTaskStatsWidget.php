<?php
namespace backend\widgets;

use yii\base\Widget;
use common\models\Task;
use yii\helpers\ArrayHelper;

class DashboardTaskStatsWidget extends Widget
{
    public function run()
    {
        $sql = "select `status`, count(`status`) as count from task group by `status`";
        $statuses = Task::getStatusList();
        $statuses = array_keys($statuses);
        $statuses = array_fill_keys($statuses, 0);

        // task statistics
        $statistics = Task::find()->select("`status`, count(`status`) as count")->groupBy('status')->asArray()->all();
        $statistics = ArrayHelper::map($statistics, 'status', 'count');
        $total = array_sum($statistics);
        foreach ($statistics as &$value) {
            $value = (int)($value * 100 / $total);
        }
        $statistics = array_merge($statuses, $statistics);
        
        return $this->render('dashboardtaskstatswidget.tpl', [
            'statistics' => $statistics, 
        ]);
    }
}