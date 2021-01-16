<?php
namespace frontend\forms;

use Yii;
use yii\base\Model;
use frontend\models\AffiliateCommission;

class FetchAffiliateCommissionForm extends Model
{
    public $user_id;
    public $start_date;
    public $end_date;
    public $status;

    private $_command;
    
    protected function createCommand()
    {
        $command = AffiliateCommission::find()
        ->where(['user_id' => $this->user_id]);

        if ($this->start_date) {
            $command->andWhere(['>=', 'created_at', $this->start_date . " 00:00:00"]);
        }
        if ($this->end_date) {
            $command->andWhere(['<=', 'created_at', $this->end_date . " 23:59:59"]);
        }
        if ($this->status) {
            $now = date('Y-m-d H:i:s');
            if ($this->status == 'pending') {
                $command->andWhere(['status' => AffiliateCommission::STATUS_VALID]);
                $command->andWhere(['<=', 'valid_from_date', $now]);
            } elseif ($this->status == 'ready') {
                $command->andWhere(['status' => AffiliateCommission::STATUS_VALID]);
                $command->andWhere(['>=', 'valid_from_date', $now]);
                $command->andWhere(['<=', 'valid_to_date', $now]);
            }
        }
        $command->with('order');
        $this->_command = $command;
    }

    public function getCommand()
    {
        if (!$this->_command) {
            $this->createCommand();
        }
        return $this->_command;
    }

    public function fetchStatusList()
    {
        return [
            'pending' => 'Pending',
            'ready' => 'Ready',
        ];
    }
}
