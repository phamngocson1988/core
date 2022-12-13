<?php

namespace backend\forms;

use Yii;
use yii\base\Model;
use backend\models\LeadTracker;
use yii\helpers\ArrayHelper;
use common\models\Country;
use backend\models\User;
use backend\models\Game;

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
    public $game_id;
    public $question_1;
    public $question_2;
    public $question_3;
    public $question_4;
    public $question_5;
    public $question_6;
    public $question_7;
    public $question_8;
    public $question_9;

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
            [['name', 'data', 'saler_id', 'country_code', 'phone', 'email', 'channel', 'game_id'], 'safe'],
            [['question_1', 'question_2', 'question_3', 'question_4', 'question_5', 'question_6', 'question_7', 'question_8', 'question_9'], 'safe'],    
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
        $leadTracker->game_id = $this->game_id;
        $leadTracker->question_1 = $this->question_1;
        $leadTracker->question_2 = $this->question_2;
        $leadTracker->question_3 = $this->question_3;
        $leadTracker->question_4 = $this->question_4;
        $leadTracker->question_5 = $this->question_5;
        $leadTracker->question_6 = $this->question_6;
        $leadTracker->question_7 = $this->question_7;
        $leadTracker->question_8 = $this->question_8;
        $leadTracker->question_9 = $this->question_9;
        $leadTracker->is_potential = $leadTracker->calculateIsPotential();
        $leadTracker->is_target = $leadTracker->calculateIsTarget();
        $leadTracker->save();
        return true;
    }

    public function attributeLabels()
    {
        return [
            'question_1' => $this->getQuestionTitle('question_1'),
            'question_2' => $this->getQuestionTitle('question_2'),
            'question_3' => $this->getQuestionTitle('question_3'),
            'question_4' => $this->getQuestionTitle('question_4'),
            'question_5' => $this->getQuestionTitle('question_5'),
            'question_6' => $this->getQuestionTitle('question_6'),
            'question_7' => $this->getQuestionTitle('question_7'),
            'question_8' => $this->getQuestionTitle('question_8'),
            'question_9' => $this->getQuestionTitle('question_9'),
        ];
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
        $this->game_id = $leadTracker->game_id;
        $this->question_1 = $leadTracker->question_1;
        $this->question_2 = $leadTracker->question_2;
        $this->question_3 = $leadTracker->question_3;
        $this->question_4 = $leadTracker->question_4;
        $this->question_5 = $leadTracker->question_5;
        $this->question_6 = $leadTracker->question_6;
        $this->question_7 = $leadTracker->question_7;
        $this->question_8 = $leadTracker->question_8;
        $this->question_9 = $leadTracker->question_9;
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

    public function fetchGames()
    {
        $games = Game::find()->where(['<>', 'status', Game::STATUS_DELETE])->select(['id', 'title'])->all();
        return ArrayHelper::map($games, 'id', 'title');
    }

    public function getQuestionTitle($question) 
    {
        return LeadTracker::getQuestionTitle($question);
    }
}
