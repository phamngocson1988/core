<?php

namespace backend\forms;

use Yii;
use yii\base\Model;
use backend\models\LeadTracker;
use yii\helpers\ArrayHelper;
use common\models\Country;
use backend\models\User;
use backend\models\Game;
use common\models\LeadTrackerQuestion;
/**
 * CreateLeadTrackerForm is the model behind the contact form.
 */
class CreateLeadTrackerForm extends Model
{
    public $name;
    public $link;
    public $saler_id;
    public $country_code;
    public $phone;
    public $email;
    public $channels = [];
    public $contacts = [];
    public $game_id;
    public $questions = [];

    protected $_questions;

    const SCENARIO_CREATE = 'create';
    const SCENARIO_CONVERT = 'convert';

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $attributes = ['name', 'link', 'saler_id', 'country_code', 'phone', 'email', 'channels', 'contacts', 'game_id'];
        $scenarios[self::SCENARIO_CREATE] = array_merge($attributes, ['questions']);
        $scenarios[self::SCENARIO_CONVERT] = $attributes;
        return $scenarios;
    }

    public function rules()
    {
        return [
            [['name', 'email', 'phone', 'link'], 'trim'],
            ['name', 'required'],
            ['email', 'email'],
            ['email', 'validateEmail', 'on' => self::SCENARIO_CREATE],
            ['phone', 'validatePhone', 'on' => self::SCENARIO_CREATE],
            ['link', 'required', 'on' => self::SCENARIO_CREATE],
            ['email', 'unique', 'targetClass' => LeadTracker::className(), 'message' => 'This email address has already been taken.'],
            ['phone', 'unique', 'targetClass' => LeadTracker::className(), 'message' => 'This phone has already been taken.'],
            [['name', 'link', 'saler_id', 'country_code', 'channels', 'contacts', 'game_id', 'questions'], 'safe'],
        ];
    }

    public function validateEmail($attribute, $params)
    {
        if ($this->email) {
            if (User::find()->where(['email' => $this->email])->exists()) {
                return $this->addError($attribute, 'Email đã có tài khoản trong kinggems');
            }
        }
    }

    public function validatePhone($attribute, $params)
    {
        if ($this->phone) {
            if (User::find()->where(['phone' => $this->phone])->exists()) {
                return $this->addError($attribute, 'Phone đã có tài khoản trong kinggems');
            }
        }
    }

    protected function getQuestions() 
    {
        if (!$this->_questions) {
            $this->_questions = LeadTrackerQuestion::find()->all();
        }
        return $this->_questions;
    }

    public function save()
    {
        if (!$this->validate()) {
            return false;
        }
        if ($this->scenario === self::SCENARIO_CONVERT) {
            $this->questions = ArrayHelper::getColumn($this->getQuestions(), 'id');
        }
        $now = date('Y-m-d H:i:s');
        $this->questions = array_keys(array_filter($this->questions));
        $leadTracker = new LeadTracker();
        $leadTracker->name = $this->name;
        $leadTracker->link = $this->link;
        $leadTracker->saler_id = $this->saler_id;
        $leadTracker->country_code = $this->country_code;
        $leadTracker->phone = $this->phone;
        $leadTracker->email = $this->email;
        $leadTracker->channels = implode(',', (array)$this->channels);
        $leadTracker->contacts = implode(',', (array)$this->contacts);
        $leadTracker->game_id = $this->game_id;
        $leadTracker->lead_questions = implode(',', $this->questions);
        $leadTracker->is_potential = $leadTracker->calculateIsPotential();
        $leadTracker->is_target = $leadTracker->calculateIsTarget();
        if ($leadTracker->is_potential && !$leadTracker->potential_lead_at) {
            $leadTracker->potential_lead_at = $now;
        }
        if ($leadTracker->is_target && !$leadTracker->target_lead_at) {
            $leadTracker->target_lead_at = $now;
        }
        if ($leadTracker->save()) {
            return $leadTracker;
        }
        return false;
    }

    public function getBooleanList() 
    {
        return [
            '0' => 'No',
            '1' => 'Yes'
        ];
    }

    protected function extractQuestionAnswer()
    {
        if (!is_array($this->questions)) {
            $this->questions = [];
        }
        return array_keys(array_filter($this->questions));
    }

    public function listCountries()
    {
        return ArrayHelper::map(Country::fetchAll(), 'country_code', 'country_name');
    }

    public function listTargetLeadQuestions()
    {
        return ArrayHelper::map(array_filter($this->getQuestions(), function($item) {
            return $item->type === LeadTrackerQuestion::TYPE_LEAD_TARGET;
        }), 'id', 'question');
    }

    public function listPotentialLeadQuestions()
    {
        return ArrayHelper::map(array_filter($this->getQuestions(), function($item) {
            return $item->type === LeadTrackerQuestion::TYPE_POTENTIAL_TARGET;
        }), 'id', 'question');
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
        return LeadTracker::CHANNELS;
    }

    public function fetchContacts()
    {
        return LeadTracker::CONTACTS;
    }

    public function fetchGames()
    {
        $games = Game::find()->where(['<>', 'status', Game::STATUS_DELETE])->select(['id', 'title'])->all();
        return ArrayHelper::map($games, 'id', 'title');
    }
}
