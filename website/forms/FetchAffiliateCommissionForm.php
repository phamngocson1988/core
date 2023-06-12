<?php
namespace website\forms;

use Yii;
use yii\base\Model;
use website\models\AffiliateCommission;

class FetchAffiliateCommissionForm extends Model
{
    public $user_id;
    public $start_date;
    public $end_date;
    public $customer_name;
    public $game_title;
    public $order_id;
    public $status;

    private $_command;
    
    protected function createCommand()
    {
        $command = AffiliateCommission::find()
        ->where(['user_id' => $this->user_id]);

        if ($this->order_id) {
            $command->andWhere(["order_id" => $this->order_id]);
        }
        if ($this->customer_name) {
            $command->andWhere(["LIKE", "customer_name", $this->customer_name]);
        }
        if ($this->game_title) {
            $command->andWhere(["LIKE", "game_title", $this->game_title]);
        }

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
