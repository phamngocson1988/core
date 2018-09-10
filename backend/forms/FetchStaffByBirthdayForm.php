<?php

namespace backend\forms;

use Yii;
use yii\base\Model;
use backend\models\Staff;
use backend\models\Department;
use yii\helpers\ArrayHelper;

/**
 * FetchStaffByBirthdayForm
 */
class FetchStaffByBirthdayForm extends Model
{
    public $interval;
    private $_command;

    public function rules()
    {
        return [
            [['interval'], 'trim'],
        ];
    }

    public function fetch()
    {
        $command = $this->getCommand();
        return $command->all();
    }

    protected function createCommand()
    {
        $command = Staff::find()->select('staff.*');
        $interval = (int)$this->interval;
        $command->where("DATE_ADD(birthday, INTERVAL YEAR(CURDATE())-YEAR(birthday) + IF(DAYOFYEAR(CURDATE()) >= DAYOFYEAR(birthday),1,0) YEAR)  
BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL $interval DAY) AND (end_date IS NULL OR end_date = '0000-00-00'");
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
