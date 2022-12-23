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

/**
 * CustomerTrackerDetailForm is the model behind the contact form.
 */
class CustomerTrackerDetailForm extends Model
{
    public $id;

    /** CustomerTracker */
    private $_leadTracker;

    public function getCustomerTracker()
    {
        if (!$this->_leadTracker) {
            $this->_leadTracker = CustomerTracker::findOne($this->id);
        }
        return $this->_leadTracker;
    }

    public function getNumberOfGames()
    {
        $customerTracker = $this->getCustomerTracker();
        return Order::find()->where([
            'customer_id' => $customerTracker->user_id,
            'status' => Order::STATUS_CONFIRMED
        ])->select('game_id')->distinct()->count();
    }

    public function getListOfGames()
    {
        $customerTracker = $this->getCustomerTracker();
        return Order::find()->where([
            'customer_id' => $customerTracker->user_id,
            'status' => Order::STATUS_CONFIRMED
        ])->select(['game_id', 'game_title', 'created_at', 'SUM(quantity) as quantity'])
        ->groupBy('game_id')
        ->orderBy('quantity desc')
        ->asArray()
        ->all();
    }

}
