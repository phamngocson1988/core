<?php 
namespace backend\forms;

use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use backend\models\LeadTracker;
use common\models\Country;
use backend\models\User;

class FetchLeadTrackerForm extends Model
{
    public $id;
    public $saler_id;
    public $country_code;
    public $phone;
    public $game;
    public $email;
    public $is_potential;
    public $is_target;
    
    public function rules()
    {
        return [['id', 'saler_id', 'country_code', 'phone', 'game', 'email', 'is_potential', 'is_target'], 'safe'];
    }

    private $_command;
    
    public function fetch()
    {
        $command = $this->getCommand();
        return $command->all();
    }

    protected function createCommand()
    {
        $command = LeadTracker::find();
        $condition = [
            'id' => $this->id,
            'saler_id' => $this->saler_id,
            'country_code' => $this->country_code,
            'phone' => $this->phone,
            'game' => $this->game,
            'email' => $this->email,
        ];
        $condition = array_filter($condition);
        
        if (count($condition)) {
            $command->andWhere($condition);
        }
        $this->_command = $command;
    }

    public function getCommand()
    {
        if (!$this->_command) {
            $this->createCommand();
        }
        return $this->_command;
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