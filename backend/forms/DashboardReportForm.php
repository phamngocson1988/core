<?php
namespace backend\forms;

use Yii;
use yii\base\Model;
use backend\models\Order;

class DashboardReportForm extends Model
{
    public $type;
    public $start;
    public $end;

    public function run()
    {
        $type = $this->type;
        $start = $this->start . " 00:00:00";
        $end = $this->end . " 23:59:59";
        return [
            'revenue' => $this->revenueReport($type, $start, $end),
            'topCustomers' => $this->topCustomers($start, $end),
            'topGames' => $this->topGames($start, $end),
            'other' => $this->otherReport($start, $end)
        ];
    }

    protected function revenueReport($type, $start, $end) {
        $command = Order::find()->where(['status' => [Order::STATUS_CONFIRMED, Order::STATUS_COMPLETED]]);
        $data = [];
        switch ($type) {
            case 'Today':
            case 'Last Day':
                $result = $command
               ->andWhere(['BETWEEN', 'completed_at', $start, $end])
                ->sum('total_price');
                $data = [$result];
                break;            
            case 'Last Week':
            case 'Custom':
                $result = $command
                ->select(['DATE(completed_at) as report_date', 'SUM(total_price) as total_price'])
                ->andWhere(['BETWEEN', 'completed_at', $start, $end])
                ->groupBy(['report_date'])
                ->orderBy(['completed_at' => SORT_ASC])
                ->asArray()
                ->all();
                $data = array_map(function($row) {
                    return $row['total_price'];
                }, $result);
                break;
            case 'Last 3 Months':
            case 'Last 6 Months':
                $result = $command
                ->select(['MONTH(completed_at) as report_date', 'SUM(total_price) as total_price'])
                ->andWhere(['BETWEEN', 'completed_at', $start, $end])
                ->groupBy(['report_date'])
                ->orderBy(['completed_at' => SORT_ASC])
                ->asArray()
                ->all();
                $data = array_map(function($row) {
                    return $row['total_price'];
                }, $result);
                break;
            case 'Last Month':
                $startDate = date("$start 00:00:00");
                $endDate = date("$end 23:59:59");
                $result = $command
                ->select(['WEEK(completed_at, 0) as report_date', 'SUM(total_price) as total_price'])
                ->andWhere(['BETWEEN', 'completed_at', $start, $end])
                ->groupBy(['report_date'])
                ->orderBy(['completed_at' => SORT_ASC])
                ->asArray()
                ->all();
                $data = array_map(function($row) {
                    return $row['total_price'];
                }, $result);
                break;
            default:
                break;
        }
        return $data;
    }

    protected function topCustomers($start, $end)
    {
        return Order::find()
        ->select(['customer_id', 'customer_name', 'sum(quantity) as quantity'])
        ->where(['status' => [Order::STATUS_CONFIRMED, Order::STATUS_COMPLETED]])
        ->andWhere(['BETWEEN', 'completed_at', $start, $end])
        ->groupBy('customer_id')
        ->orderBy('quantity desc')
        ->limit(5)
        ->asArray()
        ->all();
    }

    protected function topGames($start, $end)
    {
        return Order::find()
        ->select(['game_id', 'game_title', 'sum(quantity) as quantity'])
        ->where(['status' => [Order::STATUS_CONFIRMED, Order::STATUS_COMPLETED]])
        ->andWhere(['BETWEEN', 'completed_at', $start, $end])
        ->groupBy('game_id')
        ->orderBy('quantity desc')
        ->limit(5)
        ->asArray()
        ->all();
    }

    protected function otherReport($start, $end) {
        $command = Order::find()
        ->where(['status' => [Order::STATUS_CONFIRMED, Order::STATUS_COMPLETED]])
        ->andWhere(['BETWEEN', 'completed_at', $start, $end])
        ;
        $data = [
            'revenue' => $command->sum('total_price'),
            'quantity' => $command->sum('quantity'),
            'orders' => $command->count(),
            'games' => $command->count('DISTINCT game_id'),
            'customers' => $command->count('DISTINCT customer_id'),
        ];
        return $data;
    }
}
