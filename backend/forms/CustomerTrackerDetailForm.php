<?php

namespace backend\forms;

use Yii;
use yii\base\Model;
use backend\models\CustomerTracker;
use backend\models\CustomerTrackerActionLog;
use yii\helpers\ArrayHelper;
use common\models\Country;
use backend\models\User;
use backend\models\Game;
use backend\models\Order;
use common\models\LeadTrackerSurvey;

/**
 * CustomerTrackerDetailForm is the model behind the contact form.
 */
class CustomerTrackerDetailForm extends Model
{
    public $id;

    /** CustomerTracker */
    private $_leadTracker;
    private $_surveys = null;

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

    public function getCurrentSale()
    {
        $customerTracker = $this->getCustomerTracker();
        $start = date("Y-m-01 00:00:00");
        return Order::find()->where([
            'customer_id' => $customerTracker->user_id,
            'status' => Order::STATUS_CONFIRMED
        ])
        ->andWhere([">=", "confirmed_at", $start])
        ->sum('quantity');
    }

    public function getContacts()
    {
        return CustomerTrackerActionLog::find()->where(['lead_tracker_id' => $this->id])->all();
    }

    protected function fetchAllSurveys()
    {
        if (!$this->_surveys) {
            $this->_surveys = LeadTrackerSurvey::find()->all();
        }
        return $this->_surveys;
    }

    public function fetchSurveys($customerType)
    {
        $surveys = $this->fetchAllSurveys();
        return array_filter($surveys, function($s) use ($customerType) {
            return $s->customer_type === $customerType;
        });
    }
}
