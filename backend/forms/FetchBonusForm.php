<?php

namespace backend\forms;

use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use backend\models\Bonus;
use backend\models\Operator;

class FetchBonusForm extends Model
{
    public $q;
    public $operator_id;
    public $status;

    private $_command;
    
    public function attributeLabels()
    {
        return [
            'q' => Yii::t('app', 'keyword'),
            'operator' => Yii::t('app', 'operator'),
            'status' => Yii::t('app', 'status'),
        ];
    }
    protected function createCommand()
    {
        $command = Bonus::find();

        if ($this->q) {
            $command->andWhere(['or',
                ['like', 'title', $this->q],
                ['like', 'content', $this->q],

            ]);
        }
        if ($this->operator_id) {
            $command->andWhere(['operator_id' => $this->operator_id]);
        }
        if ($this->status) {
            $command->andWhere(['status' => $this->status]);
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

    public function fetchOperator()
    {
        $operators = Operator::find()->select(['id', 'name'])->all();
        return ArrayHelper::map($operators, 'id', 'name');
    }

    public function fetchStatus()
    {
        return Bonus::getStatusList();
    }
}
