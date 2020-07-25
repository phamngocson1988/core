<?php
namespace common\behaviors;

use Yii;
use yii\helpers\ArrayHelper;
use yii\behaviors\AttributeBehavior;
use common\models\UserPoint;

class UserPointBehavior extends AttributeBehavior
{
    public function plusPoint($point = 0, $description = '') 
    {
        $owner = $this->owner; // User
        $log = new UserPoint();
        $log->user_id = $owner->id;
        $log->point = $point;
        $log->description = $description;
        return $log->save();
    }

    public function minusPoint($point = 0, $description = '') 
    {
        $owner = $this->owner; // User
        $log = new UserPoint();
        $log->user_id = $owner->id;
        $log->point = (-1) * $point;
        $log->description = $description;
        return $log->save();
    }

    public function totalPoint()
    {
        $owner = $this->owner; // User
        $point = UserPoint::find()
        ->where(['user_id' => $owner->id])
        ->sum('point');
        return (int)$point;
    }

    // public function getLevelText()
    // {
    //     $owner = $this->owner; // User
    //     $point = $this->totalPoint();
    //     $levels = UserPoint::definedLevel();
    //     $level = UserPoint::getLevel($point);
    //     return ArrayHelper::getValue($levels, $level, '');
    // }

    public function getLevel()
    {
        $owner = $this->owner; // User
        $point = $this->totalPoint();
        return UserPoint::getLevelByPoint($point);
    }

    public function getRanking()
    {
        $point = $this->totalPoint();
        $records = UserPoint::find()
        ->select(["SUM(point) as point"])
        ->groupBy(["user_id"])
        ->having("point >= $point")
        ->asArray()
        ->all();
        $points = array_column($records, 'point');
        $points = array_unique($points);
        return count($points);
    }
}
