<?php
namespace frontend\widgets;

use Yii;
use yii\base\Widget;
use yii\helpers\ArrayHelper;
use frontend\models\UserPoint;
use frontend\models\User;

class ForumTopContributorWidget extends Widget
{
    public function run()
    {
    	$report = [];
        $year = date('Y-01-01 00:00:00');
        $week = date('Y-m-d 00:00:00', strtotime('monday this week'));
        $month = date('Y-m-01 00:00:00');
    	// this week
    	$weekRecords = UserPoint::find()
    	->select(['user_id', 'week(created_at) as report_week', 'year(created_at) as report_year', 'sum(point) sumpoint'])
        ->where(['>=', 'created_at', $week])
    	->groupBy(['user_id', 'report_week'])
    	->orderBy(['sumpoint' => SORT_DESC])
    	->asArray()
    	->limit(4)
    	->all();
    	$weekPoints = array_column($weekRecords, 'sumpoint', 'user_id');
    	$weekUserIds = array_keys($weekPoints);
    	$weekUsers = User::find()->where(['in', 'id', $weekUserIds])->indexBy('id')->all();
    	foreach ($weekPoints as $userId => $point) {
    		$report['week'][$userId]['point'] = $point;
    		$report['week'][$userId]['user'] = ArrayHelper::getValue($weekUsers, $userId);
    	}

    	// this month
    	$monthRecords = UserPoint::find()
    	->select(['user_id', 'month(created_at) as report_month', 'year(created_at) as report_year', 'sum(point) sumpoint'])
        ->where(['>=', 'created_at', $month])
    	->groupBy(['user_id', 'report_month'])
    	->orderBy(['sumpoint' => SORT_DESC])
    	->asArray()
    	->limit(4)
    	->all();
    	$monthPoints = array_column($monthRecords, 'sumpoint', 'user_id');
    	$monthUserIds = array_keys($monthPoints);
    	$monthUsers = User::find()->where(['in', 'id', $monthUserIds])->indexBy('id')->all();
    	foreach ($monthPoints as $userId => $point) {
    		$report['month'][$userId]['point'] = $point;
    		$report['month'][$userId]['user'] = ArrayHelper::getValue($monthUsers, $userId);
    	}

    	// this year
    	$yearRecords = UserPoint::find()
    	->select(['user_id', 'year(created_at) as report_year', 'sum(point) sumpoint'])
        ->where(['>=', 'created_at', $year])
    	->groupBy(['user_id', 'report_year'])
    	->orderBy(['sumpoint' => SORT_DESC])
    	->asArray()
    	->limit(4)
    	->all();
    	$yearPoints = array_column($yearRecords, 'sumpoint', 'user_id');
    	$yearUserIds = array_keys($yearPoints);
    	$yearUsers = User::find()->where(['in', 'id', $yearUserIds])->indexBy('id')->all();
    	foreach ($yearPoints as $userId => $point) {
    		$report['year'][$userId]['point'] = $point;
    		$report['year'][$userId]['user'] = ArrayHelper::getValue($yearUsers, $userId);
    	}

    	// this all
    	$allRecords = UserPoint::find()
    	->select(['user_id', 'sum(point) sumpoint'])
    	->groupBy(['user_id'])
    	->orderBy(['sumpoint' => SORT_DESC])
    	->asArray()
    	->limit(4)
    	->all();
    	$allPoints = array_column($allRecords, 'sumpoint', 'user_id');
    	$allUserIds = array_keys($allPoints);
    	$allUsers = User::find()->where(['in', 'id', $allUserIds])->indexBy('id')->all();
    	foreach ($allPoints as $userId => $point) {
    		$report['all'][$userId]['point'] = $point;
    		$report['all'][$userId]['user'] = ArrayHelper::getValue($allUsers, $userId);
    	}
        return $this->render('forum_topcontributor', [

        	'report' => $report
        ]);
    }
}