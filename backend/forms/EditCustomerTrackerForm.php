<?php

namespace backend\forms;

use Yii;
use yii\base\Model;
use backend\models\CustomerTracker;
use yii\helpers\ArrayHelper;
use common\models\Country;
use backend\models\User;
use backend\models\Game;

/**
 * EditCustomerTrackerForm is the model behind the contact form.
 */
class EditCustomerTrackerForm extends Model
{
    public $id;
    public $name;
    public $link;
    public $saler_id;
    public $country_code;
    public $phone;
    public $email;
    public $channel;
    public $game_id;
    public $sale_target;
    public $customer_tracker_status;

    /** CustomerTracker */
    private $_leadTracker;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['name', 'trim'],
            [['id', 'name'], 'required'],
            [['id'], 'validateCustomerTracker'],
            [['name', 'link', 'saler_id', 'country_code', 'phone', 'email', 'channel', 'game_id', 'customer_tracker_status', 'sale_target'], 'safe'],
        ];
    }

    public function validateCustomerTracker($attribute, $params)
    {
        $leadTracker = $this->getCustomerTracker();
        if (!$leadTracker) {
            return $this->addError($attribute, 'Lead tracker không tồn tại');
        }
    }

    public function getCustomerTracker()
    {
        if (!$this->_leadTracker) {
            $this->_leadTracker = CustomerTracker::findOne($this->id);
        }
        return $this->_leadTracker;
    }

    public function save()
    {
        if (!$this->validate()) {
            return false;
        }
        $leadTracker = $this->getCustomerTracker();
        $leadTracker->name = $this->name;
        $leadTracker->link = $this->link;
        $leadTracker->saler_id = $this->saler_id;
        $leadTracker->country_code = $this->country_code;
        $leadTracker->phone = $this->phone;
        $leadTracker->email = $this->email;
        $leadTracker->channel = $this->channel;
        $leadTracker->game_id = $this->game_id;
        $leadTracker->customer_tracker_status = $this->customer_tracker_status;
        $leadTracker->sale_target = $this->sale_target;
        $leadTracker->save();
        return true;
    }

    public function loadData()
    {
        $leadTracker = $this->getCustomerTracker();
        $shouldCalculatePerformance = $this->sale_target != $leadTracker->sale_target;
        $this->name = $leadTracker->name;
        $this->link = $leadTracker->link;
        $this->saler_id = $leadTracker->saler_id;
        $this->country_code = $leadTracker->country_code;
        $this->phone = $leadTracker->phone;
        $this->email = $leadTracker->email;
        $this->channel = $leadTracker->channel;
        $this->game_id = $leadTracker->game_id;
        $this->customer_tracker_status = $leadTracker->customer_tracker_status;
        $this->sale_target = $leadTracker->sale_target;
        if ($shouldCalculatePerformance) {
            Yii::$app->queue->push(new \common\queue\RunCustomerTrackerPerformanceJob(['id' => $this->id]));
        }
    }

    public function getBooleanList() 
    {
        return [
            '0' => 'No',
            '1' => 'Yes'
        ];
    }

    public function listCountries()
    {
        return ArrayHelper::map(Country::fetchAll(), 'country_code', 'country_name');
    }

    public function listCountryAttributes()
    {
        $attrs = [];
        foreach (Country::fetchAll() as $country) {
            $attrs[$country->country_code] = ['data-dialling' => $country->dialling_code];
        }
        return $attrs;
    }

    public function fetchSalers()
    {
        $member = Yii::$app->authManager->getUserIdsByRole('saler');
        $manager = Yii::$app->authManager->getUserIdsByRole('sale_manager');
        $admin = Yii::$app->authManager->getUserIdsByRole('admin');

        $salerTeamIds = array_merge($member, $manager, $admin);
        $salerTeamIds = array_unique($salerTeamIds);
        $salerTeamObjects = User::find()->where(['id' => $salerTeamIds])->select(['id', 'email'])->all();
        $salerTeam = ArrayHelper::map($salerTeamObjects, 'id', 'email');
        return $salerTeam;
    }

    public function fetchChannels()
    {
        return CustomerTracker::CHANNELS;
    }

    public function fetchCustomerStatus()
    {
        return CustomerTracker::CUSTOMER_STATUS;
    }

    public function fetchGames()
    {
        $games = Game::find()->where(['<>', 'status', Game::STATUS_DELETE])->select(['id', 'title'])->all();
        return ArrayHelper::map($games, 'id', 'title');
    }
}
