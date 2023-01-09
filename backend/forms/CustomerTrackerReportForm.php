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
    const CUSTOMER_STATUS_MEASUREMENT = [
        'potential_lead_at' => 'potential_lead_at',
        'target_lead_at' => 'target_lead_at',
        'normal_customer_at' => 'first_order_at',
        'potential_customer_at' => 'potential_customer_at',
        'key_customer_at' => 'key_customer_at',
        'loyalty_customer_at' => 'loyalty_customer_at',
        'dangerous_customer_at' => 'dangerous_customer_at',
    ];

    protected $_monthlyConversionTotal = [];
    protected $_monthlyTotal = [];
    /**
     * @return [
     *  potential_lead_at => [
     *      'label' => 'xxx',
     *      'data' => [
     *          '202210' => 10,
     *          '202211' => 13,
     *          '202212' => 14
     *      ]
     *  ],
     * ]
     */
    public function monthlyConversionMeasurement()
    {
        if (count($this->_monthlyConversionTotal)) {
            return $this->_monthlyConversionTotal;
        }
        $start = date("Y-m-01 00:00:00", strtotime("-3 month"));
        $end = date("Y-m-t 23:59:59", strtotime("-1 month"));
        $measurements = [
            'potential_lead_at' => [
                'column' => 'potential_lead_at',
                'label' => 'Potential Lead'
            ],
            'target_lead_at' => [
                'column' => 'target_lead_at',
                'label' => 'Target Lead'
            ],
            'normal_customer_at' => [
                'column' => 'normal_customer_at',
                'label' => 'Normal Customer'
            ],
            'potential_customer_at' => [
                'column' => 'potential_customer_at',
                'label' => 'Potential Customer'
            ],
            'key_customer_at' => [
                'column' => 'key_customer_at',
                'label' => 'Key Customer'
            ],
            'loyalty_customer_at' => [
                'column' => 'loyalty_customer_at',
                'label' => 'Loyalty Customer'
            ],
            'dangerous_customer_at' => [
                'column' => 'dangerous_customer_at',
                'label' => 'Cus "in dangerous"'
            ],
        ];
        $measurementData = [];
        foreach ($measurements as $key => $measurement) {
            $column = $measurement['column'];
            $label = $measurement['label'];
            $data = [];
            $data['label'] = $measurement['label'];
            $records = UserTracker::find()
                ->select(["EXTRACT(YEAR_MONTH FROM $column) AS year_and_month", "COUNT(1) as `count`"])
                ->where(['between', $column, $start, $end])
                ->groupBy('year_and_month')
                ->all();
            $data['data'] = ArrayHelper::map($records, 'year_and_month', 'count');
            $measurementData[$key] = $data;
        }
        $this->_monthlyConversionTotal = $measurementData;
        return $measurementData;
    }

    /**
     * @return [
     *  potential_lead_at => [
     *      'label' => 'xxx',
     *      'data' => [
     *          '202210' => 10,
     *          '202211' => 13,
     *          '202212' => 14
     *      ]
     *  ],
     * ]
     */
    public function monthlyCustomerStatusTotal()
    {
        if (count($this->_monthlyTotal)) {
            return $this->_monthlyTotal;
        }
        $measurements = [
            'potential_lead_at' => [
                'column' => 'potential_lead_at',
                'compare_column' => 'target_lead_at',
                'label' => 'Potential Lead'
            ],
            'target_lead_at' => [
                'column' => 'target_lead_at',
                'compare_column' => 'normal_customer_at',
                'label' => 'Target Lead'
            ],
            'normal_customer_at' => [
                'column' => 'normal_customer_at',
                'compare_column' => 'potential_customer_at',
                'label' => 'Normal Customer'
            ],
            'potential_customer_at' => [
                'column' => 'potential_customer_at',
                'compare_column' => 'key_customer_at',
                'label' => 'Potential Customer'
            ],
            'key_customer_at' => [
                'column' => 'key_customer_at',
                'compare_column' => 'loyalty_customer_at',
                'label' => 'Key Customer'
            ],
            'loyalty_customer_at' => [
                'column' => 'loyalty_customer_at',
                'compare_column' => 'dangerous_customer_at',
                'label' => 'Loyalty Customer'
            ],
            'dangerous_customer_at' => [
                'column' => 'dangerous_customer_at',
                'compare_column' => 'normal_customer_at',
                'label' => 'Cus "in dangerous"'
            ],
        ];
        $measurementData = [];
        foreach ($measurements as $key => $measurement) {
            $label = $measurement['label'];
            $column = $measurement['column'];
            $compare_column = $measurement['compare_column'];
            $data = [];
            $data['label'] = $measurement['label'];
            $data['data'] = [];
            for ($i = 1; $i <= 3; $i++) {
                $start = date("Y-m-01 00:00:00", strtotime("-$i month"));
                $end = date("Y-m-t 23:59:59", strtotime("-$i month"));
                $ym = date('Ym', strtotime($start));
                $data['data'][$ym] = UserTracker::find()
                    ->where(['<=', $column, $end])
                    ->andWhere(['or',
                        ['between', $compare_column, $start, $end],
                        ['is', $compare_column, new \yii\db\Expression('null')]
                    ])
                    ->count();
            }
            $measurementData[$key] = $data;
        }
        $this->_monthlyTotal = $measurementData;
        return $measurementData;
    }

    /**
     * @return [
     *  PL-TL => [
     *     '202210' => 10,
     *     '202211' => 13,
     *     '202212' => 14
     *  ],
     * ]
     */
    public function monthlyConversionRate()
    {
        $conversionMeasurement = $this->monthlyConversionMeasurement();
        $totalStatus = $this->monthlyCustomerStatusTotal();
        $formula = [
            'PL-TL' => [
                't' => 'target_lead_at',
                'm' => 'potential_lead_at'
            ],
            'TL-NC' => [
                't' => 'normal_customer_at',
                'm' => 'target_lead_at'
            ],
            'NC-PC' => [
                't' => 'potential_customer_at',
                'm' => 'normal_customer_at'
            ],
            'PC-KC' => [
                't' => 'key_customer_at',
                'm' => 'potential_customer_at'
            ],
            'NC-LC' => [
                't' => 'loyalty_customer_at',
                'm' => 'normal_customer_at'
            ],
            'NC-CI' => [
                't' => 'dangerous_customer_at',
                'm' => 'normal_customer_at'
            ],
            
        ];
        $measurementData = [];
        foreach ($formula as $key => $f) {
            $t = $f['t'];
            $m = $f['m'];
            $data = [];
            for ($i = 3; $i >= 1; $i--) {
                $ym = date('Ym', strtotime("-$i month"));
                $tv = ArrayHelper::getValue($conversionMeasurement[$t]['data'], $ym, 0);
                $mv = ArrayHelper::getValue($totalStatus[$m]['data'], $ym, 0);
                $data[$ym] = $mv ? round($tv / $mv, 2) : 0;
            }
            $measurementData[$key] = $data;
        }
        return $measurementData;
    }

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
        ->where(['month' => $month, 'monthly_status' => [1, 2, 3]])
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
