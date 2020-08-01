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

    private $_command;
    
    public function attributeLabels()
    {
        return [
            'operator' => Yii::t('app', 'operator'),
            'bonus_type' => Yii::t('app', 'bonus_type'),
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
