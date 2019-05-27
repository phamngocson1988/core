<?php

namespace backend\forms;

use Yii;
use yii\base\Model;
use common\models\User;

/**
 * FetchCustomerForm
 */
class FetchCustomerForm extends Model
{
    public $created_start;
    public $created_end;
    public $birthday_start;
    public $birthday_end;
    public $country_code;
    public $game;
    public $order_start;
    public $order_end;
    public $purchase_from;
    public $purchase_end;
    public $saler_id;
    public $last_purchase_from;
    public $last_purchase_end;
    public $total_topup_from;
    public $total_topup_end;
    private $_command;

    public function rules()
    {
        return [
            [['q', 'status'], 'trim'],
        ];
    }

    public function fetch()
    {
        $command = $this->getCommand();
        return $command->all();
    }

    protected function createCommand()
    {
        $command = User::find();

        if ($this->created_start) {
            $command->andWhere(['>=', 'created_at', $this->created_start . " 00:00:00"]);
        }
        if ($this->created_end) {
            $command->andWhere(['<=', 'created_at', $this->created_end . " 23:59:59"]);
        }

        if ($this->birthday_start) {
            $command->andWhere(['>=', 'birthday', $this->birthday_start . " 00:00:00"]);
        }
        if ($this->birthday_end) {
            $command->andWhere(['<=', 'birthday', $this->birthday_end . " 23:59:59"]);
        }

        if ($this->country_code) {
            $command->andWhere(['country_code' => $this->country_code]);
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

    public function getUserStatus()
    {
        return User::getUserStatus();
    }
}
