<?php

namespace backend\forms;

use Yii;
use yii\base\Model;
use backend\models\CustomerTracker;
use yii\helpers\ArrayHelper;
use common\models\Country;
use backend\models\User;
use backend\models\Game;
use backend\models\Order;
use common\models\UserTracker;
use common\models\LeadTrackerPeriodic;

/**
 * CustomerTrackerReportForm is the model behind the contact form.
 */
class CustomerTrackerReportForm extends Model
{

    public function reportLeadTrackerCreation()
    {
        $month3 = date('Ym', strtotime('-1 month'));
        $month2 = date('Ym', strtotime('-2 month'));
        $month1 = date('Ym', strtotime('-3 month'));

        $report = LeadTrackerPeriodic::find()
        ->where(['month' => [$month1, $month2, $month3]])
        ->groupBy('month')
        ->select([
            'month',
            'sum(is_become_potential_lead) as is_become_potential_lead',
            'sum(is_become_target_lead) as is_become_target_lead',
            'sum(is_become_normal_customer) as is_become_normal_customer',
            'sum(is_become_potential_customer) as is_become_potential_customer',
            'sum(is_become_key_customer) as is_become_key_customer',
            'sum(is_become_loyalty_customer) as is_become_loyalty_customer',
            'sum(is_become_dangerous_customer) as is_become_dangerous_customer',
        ])
        ->asArray()
        ->indexBy('month')
        ->all();
        return [
            'month1' => ArrayHelper::getValue($report, $month1, []),
            'month2' => ArrayHelper::getValue($report, $month2, []),
            'month3' => ArrayHelper::getValue($report, $month3, []),
        ];
    }

    // ========= Customer tracker performance ======
    public function reportPerformance()
    {
        $month3 = date('Ym', strtotime('-1 month'));
        $month2 = date('Ym', strtotime('-2 month'));
        $month1 = date('Ym', strtotime('-3 month'));
        return [
            'month1' => $this->getPerformanceByMonth($month1),
            'month2' => $this->getPerformanceByMonth($month2),
            'month3' => $this->getPerformanceByMonth($month3),
        ];
    }

    protected function getPerformanceByMonth($month)
    {
        $report = LeadTrackerPeriodic::find()
        ->where(['month' => $month, 'monthly_status' => [-2, -1, 1, 2, 3]])
        ->groupBy('monthly_status')
        ->select(['monthly_status', 'COUNT(1) as count', 'SUM(quantity) as quantity', 'SUM(target) as target'])
        ->asArray()
        ->indexBy('monthly_status')
        ->all();
        return $report;
    }

    //============ LOYALTY =============
    public function reportLoyaltyPerformance()
    {
        $month3 = date('Ym', strtotime('-1 month'));
        $month2 = date('Ym', strtotime('-2 month'));
        $month1 = date('Ym', strtotime('-3 month'));
        return [
            'month1' => $this->getLoyaltyPerformanceByMonth($month1),
            'month2' => $this->getLoyaltyPerformanceByMonth($month2),
            'month3' => $this->getLoyaltyPerformanceByMonth($month3),
        ];
    }

    protected function getLoyaltyPerformanceByMonth($month)
    {
        $report = LeadTrackerPeriodic::find()
        ->where(['month' => $month, 'is_loyalty' => true])
        ->groupBy('month')
        ->select(['month', 'COUNT(1) as count', 'SUM(quantity) as quantity', 'SUM(target) as target'])
        ->asArray()
        ->one();
        return $report;
    }

    //========= DANGEROUS =========
    public function reportDangerousPerformance()
    {
        $month3 = date('Ym', strtotime('-1 month'));
        $month2 = date('Ym', strtotime('-2 month'));
        $month1 = date('Ym', strtotime('-3 month'));
        return [
            'month1' => $this->getDangerousPerformanceByMonth($month1),
            'month2' => $this->getDangerousPerformanceByMonth($month2),
            'month3' => $this->getDangerousPerformanceByMonth($month3),
        ];
    }

    protected function getDangerousPerformanceByMonth($month)
    {
        $report = LeadTrackerPeriodic::find()
        ->where(['month' => $month, 'is_dangerous' => true])
        ->groupBy('month')
        ->select(['month', 'COUNT(1) as count', 'SUM(quantity) as quantity', 'SUM(target) as target'])
        ->asArray()
        ->one();
        return $report;
    }

    public function topTenUsers()
    {
        $totalQuantity = Order::find()
        ->where(['status' => Order::STATUS_CONFIRMED])
        ->sum('quantity');
        $totalQuantity = round($totalQuantity, 2);

        $topUsers = Order::find()
        ->select(['customer_id', 'customer_name', 'sum(quantity) as quantity'])
        ->where(['status' => Order::STATUS_CONFIRMED])
        ->groupBy('customer_id')
        ->orderBy('quantity desc')
        ->limit(10)
        ->asArray()
        ->all();

        $reportData = [];
        foreach ($topUsers as $user) {
            $record = [];
            $record['name'] = $user['customer_name'];
            $record['quantity'] = round($user['quantity'], 2);
            $record['percent'] = round(($user['quantity'] / $totalQuantity) * 100, 2);
            $reportData[] = $record;
        }
        $topTenQuantity = array_sum(array_column($reportData, 'quantity'));
        $topTenPercent = array_sum(array_column($reportData, 'percent'));
        $otherRecord = [];
        $otherRecord['name'] = 'The rest';
        $otherRecord['quantity'] = round($totalQuantity - $topTenQuantity, 2);
        $otherRecord['percent'] = round(100 - $topTenPercent, 2);
        $reportData[] = $otherRecord;
        return ['data' => $reportData, 'total' => $totalQuantity];
    }

    public function topTenGames()
    {
        $totalQuantity = Order::find()
        ->where(['status' => Order::STATUS_CONFIRMED])
        ->sum('quantity');
        $totalQuantity = round($totalQuantity, 2);

        $topUsers = Order::find()
        ->select(['game_id', 'game_title', 'sum(quantity) as quantity'])
        ->where(['status' => Order::STATUS_CONFIRMED])
        ->groupBy('game_id')
        ->orderBy('quantity desc')
        ->limit(10)
        ->asArray()
        ->all();

        $reportData = [];
        foreach ($topUsers as $user) {
            $record = [];
            $record['name'] = $user['game_title'];
            $record['quantity'] = round($user['quantity'], 2);
            $record['percent'] = round(($user['quantity'] / $totalQuantity) * 100, 2);
            $reportData[] = $record;
        }
        $topTenQuantity = array_sum(array_column($reportData, 'quantity'));
        $topTenPercent = array_sum(array_column($reportData, 'percent'));
        $otherRecord = [];
        $otherRecord['name'] = 'The rest';
        $otherRecord['quantity'] = round($totalQuantity - $topTenQuantity, 2);
        $otherRecord['percent'] = round(100 - $topTenPercent, 2);
        $reportData[] = $otherRecord;
        return ['data' => $reportData, 'total' => $totalQuantity];
    }
}
