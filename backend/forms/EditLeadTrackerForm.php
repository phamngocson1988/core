<?php

namespace backend\forms;

use Yii;
use yii\base\Model;
use backend\models\LeadTracker;
use yii\helpers\ArrayHelper;
use common\models\Country;
use backend\models\User;

/**
 * EditLeadTrackerForm is the model behind the contact form.
 */
class EditLeadTrackerForm extends Model
{
    public $id;
    public $name;
    public $data;
    public $saler_id;
    public $country_code;
    public $phone;
    public $email;
    public $channel;
    public $game;
    public $is_potential;
    public $is_target;

    /** LeadTracker */
    private $_leadTracker;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['name', 'trim'],
            [['id', 'name'], 'required'],
            [['id'], 'validateLeadTracker'],
            [['name', 'data', 'saler_id', 'country_code', 'phone', 'email', 'channel', 'game', 'is_potential', 'is_target'], 'safe'],
        ];
    }

    public function validateLeadTracker($attribute, $params)
    {
        $leadTracker = $this->getLeadTracker();
        if (!$leadTracker) {
            return $this->addError($attribute, 'Lead tracker không tồn tại');
        }
    }

    public function getLeadTracker()
    {
        if (!$this->_leadTracker) {
            $this->_leadTracker = LeadTracker::findOne($this->id);
        }
        return $this->_leadTracker;
    }

    public function save()
    {
        if (!$this->validate()) {
            return false;
        }
        $leadTracker = $this->getLeadTracker();
        $leadTracker->name = $this->name;
        $leadTracker->data = $this->data;
        $leadTracker->saler_id = $this->saler_id;
        $leadTracker->country_code = $this->country_code;
        $leadTracker->phone = $this->phone;
        $leadTracker->email = $this->email;
        $leadTracker->channel = $this->channel;
        $leadTracker->game = $this->game;
        $leadTracker->is_potential = $this->is_potential;
        $leadTracker->is_target = $this->is_target;
        $leadTracker->save();
        return true;
    }


    public function loadData()
    {
        $leadTracker = $this->getLeadTracker();
        $this->name = $leadTracker->name;
        $this->data = $leadTracker->data;
        $this->saler_id = $leadTracker->saler_id;
        $this->country_code = $leadTracker->country_code;
        $this->phone = $leadTracker->phone;
        $this->email = $leadTracker->email;
        $this->channel = $leadTracker->channel;
        $this->game = $leadTracker->game;
        $this->is_potential = $leadTracker->is_potential;
        $this->is_target = $leadTracker->is_target;
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
}
