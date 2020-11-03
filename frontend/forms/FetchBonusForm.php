<?php

namespace frontend\forms;

use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use frontend\models\Bonus;
use frontend\models\Operator;

class FetchBonusForm extends Model
{
    public $operator_id;
    public $bonus_type;
    public $wagering_requirement;
    public $minimum_deposit_value;

    private $_command;
    
    public function attributeLabels()
    {
        return [
            'operator' => Yii::t('app', 'Operator'),
            'bonus_type' => Yii::t('app', 'Bonus Type'),
        ];
    }
    protected function createCommand()
    {
        $command = Bonus::find();

        if ($this->operator_id) {
            $command->andWhere(['operator_id' => $this->operator_id]);
        }
        if ($this->bonus_type) {
            $command->andWhere(['bonus_type' => $this->bonus_type]);
        }
        if ($this->wagering_requirement) {
            $command->andWhere(['like', 'wagering_requirement', $this->wagering_requirement]);
        }
        if ($this->minimum_deposit_value) {
            $command->andWhere(['minimum_deposit_value' => $this->minimum_deposit_value]);
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

    public function fetchType()
    {
        return Bonus::getTypeList();
    }
}
