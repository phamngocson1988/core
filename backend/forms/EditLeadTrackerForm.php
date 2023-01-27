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
 * EditLeadTrackerForm is the model behind the contact form.
 */
class EditLeadTrackerForm extends Model
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
    public $questions = [];

    /** LeadTracker */
    private $_leadTracker;

    protected $_questions;
    /**
     * @inheritdoc
     */

    public function rules()
    {
        return [
            [['name', 'email', 'phone', 'link'], 'trim'],
            [['id', 'name', 'link'], 'required'],
            [['id'], 'validateLeadTracker'],
            ['email', 'email'],
            ['email', 'validateEmail'],
            ['phone', 'validatePhone'],
            [['name', 'link', 'saler_id', 'country_code', 'channels', 'contacts', 'game_id'], 'safe'],
            [['questions'], 'safe'],    
        ];
    }

    public function validateLeadTracker($attribute, $params)
    {
        $leadTracker = $this->getLeadTracker();
        if (!$leadTracker) {
            return $this->addError($attribute, 'Lead tracker không tồn tại');
        }
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

    public function getLeadTracker()
    {
        if (!$this->_leadTracker) {
            $this->_leadTracker = LeadTracker::findOne($this->id);
        }
        return $this->_leadTracker;
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
        $this->questions = array_keys(array_filter($this->questions));
        $now = date('Y-m-d H:i:s');
        $leadTracker = $this->getLeadTracker();
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
        $leadTracker->is_potential = $this->calculateIsPotential();
        $leadTracker->is_target = $this->calculateIsTarget();
        if ($leadTracker->is_potential && !$leadTracker->potential_lead_at) {
            $leadTracker->potential_lead_at = $now;
        }
        if ($leadTracker->is_target && !$leadTracker->target_lead_at) {
            $leadTracker->target_lead_at = $now;
        }
        $leadTracker->save();
        return true;
    }

    public function loadData()
    {
        $leadTracker = $this->getLeadTracker();
        $this->name = $leadTracker->name;
        $this->link = $leadTracker->link;
        $this->saler_id = $leadTracker->saler_id;
        $this->country_code = $leadTracker->country_code;
        $this->phone = $leadTracker->phone;
        $this->email = $leadTracker->email;
        $this->channels = $leadTracker->channels ? explode(',', $leadTracker->channels) : [];
        $this->contacts = $leadTracker->contacts ? explode(',', $leadTracker->contacts) : [];
        $this->game_id = $leadTracker->game_id;
        $this->questions = (array)explode(',', $leadTracker->lead_questions);
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

    public function getQuestionTitle($question) 
    {
        return LeadTracker::getQuestionTitle($question);
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

    protected function calculateIsPotential()
    {
        $questions = array_filter($this->getQuestions(), function($item) {
            return $item->type === LeadTrackerQuestion::TYPE_POTENTIAL_TARGET;
        });
        $point = 0;
        foreach ($questions as $question) {
            $flag = in_array($question->id, $this->questions);
            $point += $flag ? $question->point_yes : $question->point_no;
        }
        return $point >= 2;
    }

    protected function calculateIsTarget()
    {
        $questions = array_filter($this->getQuestions(), function($item) {
            return $item->type === LeadTrackerQuestion::TYPE_LEAD_TARGET;
        });
        $point = 0;
        foreach ($questions as $question) {
            $flag = in_array($question->id, $this->questions);
            $point += $flag ? $question->point_yes : $question->point_no;
        }
        return $point >= 3;
    }
}
