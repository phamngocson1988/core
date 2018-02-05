<?php

namespace backend\forms;

use Yii;
use yii\base\Model;
use common\models\Promotion;

class FetchPromotionForm extends Model
{
    public $q;

    public $type;

    public $from_date;

    public $to_date;

    public $status;

    private $_command;
    
    public function fetch()
    {
        $command = $this->getCommand();
        return $command->all();
    }

    protected function createCommand()
    {
        $command = Promotion::find();

        if ($this->q) {
            $command->orWhere(['like', 'title', $this->q]);
            $command->orWhere(['like', 'excerpt', $this->q]);
            $command->orWhere(['like', 'content', $this->q]);
        }

        if ($this->type) {
            $command->andWhere(['type' => $this->type]);
        }

        if ($this->status) {
            $command->andWhere(['status' => $this->status]);
        }

        if ($this->from_date) {
            // $command->andWhere('>=', ['from_date' => $this->from_date]);
            $command->andWhere('(from_date >= :from AND from_date IS NOT NULL)', [':from' => $this->from_date]);
        }

        if ($this->to_date) {
            // $command->andWhere('<=', ['to_date' => $this->to_date]);
            $command->andWhere('(to_date <= :to to_date IS NOT NULL)', [':to' => $this->to_date]);
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
}
