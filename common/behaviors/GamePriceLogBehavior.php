<?php
namespace common\behaviors;

use Yii;
use yii\behaviors\AttributeBehavior;
use common\models\GamePriceLog;
use common\models\Game;

class GamePriceLogBehavior extends AttributeBehavior
{
    public function getLastChange() 
    {
        $owner = $this->owner; //Game
        return GamePriceLog::find()->where(['game_id' => $owner->id])->orderBy(['updated_at' => SORT_DESC])->one();
    }

    public function hasChangePrice()
    {
        $log = $this->getLastChange();
        return ($log) ? true : false;
    }

    public function isIncreasePrice()
    {
        $log = $this->getLastChange();
        if (!$log) return false;
        return $log->new_price > $log->old_price;
    }

    public function isDescreasePrice()
    {
        $log = $this->getLastChange();
        if (!$log) return false;
        return $log->new_price < $log->old_price;
    }
}
