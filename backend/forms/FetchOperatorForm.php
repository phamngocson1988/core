<?php

namespace backend\forms;

use Yii;
use yii\base\Model;
use backend\models\Operator;

class FetchOperatorForm extends Model
{
    public $q;
    public $status;
    private $_command;

    public function attributeLabels()
    {
        return [
            'q' => Yii::t('app', 'keyword'),
            'status' => Yii::t('app', 'status'),
        ];
    }

    protected function createCommand()
    {
        $command = Operator::find();
        $operatorTable = Operator::tableName();
        if ($this->q) {
            $command->andWhere(['OR',
               ["like", "{$operatorTable}.name", $this->q],
               ["like", "{$operatorTable}.main_url", $this->q],
           ]);
        }

        if ($this->status !== '') {
            $command->andWhere(["{$operatorTable}.status" => $this->status]);
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

    public function fetchStatus()
    {
        return Operator::getStatusList();
    }
}
