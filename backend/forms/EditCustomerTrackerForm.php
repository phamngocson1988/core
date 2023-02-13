<?php

namespace backend\forms;

use Yii;
use yii\base\Model;
use backend\models\CustomerTracker;
use yii\helpers\ArrayHelper;
use common\models\Country;
use backend\models\User;
use backend\models\Game;
use common\models\LeadTrackerSurvey;
use common\models\LeadTrackerSurveyAnswer;

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
    public $channels = [];
    public $contacts = [];
    public $game_id;
    public $sale_target;
    public $customer_tracker_status;

    /** CustomerTracker */
    private $_leadTracker;

    private $_surveys = null;
    private $_answers = null;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'link'], 'trim'],
            [['id', 'name', 'link'], 'required'],
            [['id'], 'validateCustomerTracker'],
            [['name', 'link', 'saler_id', 'country_code', 'phone', 'email', 'channels', 'contacts', 'game_id', 'customer_tracker_status', 'sale_target'], 'safe'],
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
        $shouldCalculatePerformance = $this->sale_target != $leadTracker->getCurrentSaleTarget();
        $leadTracker->name = $this->name;
        $leadTracker->link = $this->link;
        $leadTracker->saler_id = $this->saler_id;
        $leadTracker->country_code = $this->country_code;
        $leadTracker->phone = $this->phone;
        $leadTracker->email = $this->email;
        $leadTracker->channels = implode(",", (array)$this->channels);
        $leadTracker->contacts = implode(",", (array)$this->contacts);
        $leadTracker->game_id = $this->game_id;
        $leadTracker->customer_tracker_status = $this->customer_tracker_status;
        $leadTracker->setCurrentSaleTarget($this->sale_target);
        $leadTracker->save();

        if ($shouldCalculatePerformance) {
            Yii::$app->queue->push(new \common\queue\RunCustomerTrackerPerformanceJob(['id' => $this->id]));
        }
        return true;
    }

    public function loadData()
    {
        $leadTracker = $this->getCustomerTracker();
        $this->name = $leadTracker->name;
        $this->link = $leadTracker->link;
        $this->saler_id = $leadTracker->saler_id;
        $this->country_code = $leadTracker->country_code;
        $this->phone = $leadTracker->phone;
        $this->email = $leadTracker->email;
        $this->channels = explode(',', $leadTracker->channels);
        $this->contacts = explode(',', $leadTracker->contacts);
        $this->game_id = $leadTracker->game_id;
        $this->customer_tracker_status = $leadTracker->customer_tracker_status;
        $this->sale_target = $leadTracker->getCurrentSaleTarget();
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

    public function fetchContacts()
    {
        return CustomerTracker::CONTACTS;
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

    protected function fetchAllSurveys()
    {
        if (!$this->_surveys) {
            $this->_surveys = LeadTrackerSurvey::find()->all();
        }
        return $this->_surveys;
    }

    public function fetchAllAnswers()
    {
        if (!$this->_answers) {
            $this->_answers = LeadTrackerSurveyAnswer::find()->where(['lead_tracker_id' => $this->id])->indexBy('question_id')->all();
        }
        return $this->_answers;
    }

    public function fetchSurveys($customerType)
    {
        $surveys = $this->fetchAllSurveys();
        return array_filter($surveys, function($s) use ($customerType) {
            return $s->customer_type === $customerType;
        });
    }
}
