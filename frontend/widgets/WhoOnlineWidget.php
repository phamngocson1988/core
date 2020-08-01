<?php
namespace frontend\widgets;

use Yii;
use yii\base\Widget;
use yii\helpers\ArrayHelper;
use frontend\models\UserLog;

class WhoOnlineWidget extends Widget
{
    public $last_minute = 30;
    public $limit = 5;

    public function run()
    {
        $lastMins = date('Y-m-d H:i:s', strtotime(sprintf("-%s minutes", $this->last_minute)));
        $command = UserLog::find()->where(['>=', 'last_activity', $lastMins]);
        $total = $command->count();
        $logs = $command->with('user')->limit($this->limit)->all();
        return $this->render('who_online', [
            'logs' => $logs,
            'total' => $total
        ]);
    }
}