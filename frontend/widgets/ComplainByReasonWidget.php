<?php
namespace frontend\widgets;

use Yii;
use yii\base\Widget;
use yii\helpers\ArrayHelper;
use frontend\models\Complain;
use frontend\models\ComplainReason;

class ComplainByReasonWidget extends Widget
{

    public function run()
    {
        $stats = Complain::find()
        ->groupBy(["reason_id"])
        ->select(["reason_id", 'COUNT(*) as count_complain'])
        ->orderBy(['count_complain' => SORT_DESC])
        ->asArray()
        ->all();
        $stats = ArrayHelper::map($stats, 'reason_id', 'count_complain');
        $reasonIds = array_keys($stats);
        $reasons = ComplainReason::findAll($reasonIds);
        $total = array_sum($stats);
        return $this->render('complain_by_reason', [
            'stats' => $stats,
            'reasons' => $reasons,
            'total' => $total,
        ]);
    }
}