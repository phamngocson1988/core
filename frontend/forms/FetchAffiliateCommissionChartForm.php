<?php
namespace frontend\forms;

use Yii;
use yii\base\Model;
use frontend\models\AffiliateCommission;
use common\components\helpers\FormatConverter;

class FetchAffiliateCommissionChartForm extends Model
{
    public $user_id;

    private $_command;
    
    public function getChartData()
    {
        $command = AffiliateCommission::find()
        ->where(['user_id' => $this->user_id]);
        $from = $this->getFromDate();
        $to = $this->getToDate();
        $command->andWhere(['between', 'created_at', $from, $to]);
        $command->select([
            'date(created_at) as report_date',
            'week(created_at) as report_week',
            'month(created_at) as report_month',
            'sum(commission) as commission', 
        ]);
        $command->groupBy(['report_date']);
        $command->asArray();
        return $command->all();
    }

    public function getFromDate()
    {
        return date('Y-m-01 00:00:00', strtotime('-1 months'));
    }

    public function getToDate()
    {
        return date('Y-m-d 23:59:59');
    }

    public function getDateRange()
    {
        $from = $this->getFromDate();
        $to = $this->getToDate();
        return FormatConverter::getDateRange($from, $to, 1);
    }

    public function getMonthRange()
    {
        $from = $this->getFromDate();
        $to = $this->getToDate();
        return FormatConverter::getDateRange($from, $to, 1, 'month');
    }
}
