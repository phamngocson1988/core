<?php
namespace backend\forms;

use Yii;
use yii\base\Model;
use common\components\helpers\CommonHelper;
use backend\models\Cash;

class FetchCashForm extends Model
{
    public $currency;

    private $_command;

    protected function createCommand()
    {
        $command = Cash::find();
        if ($this->currency) {
            $command->andWhere(['currency' => $this->currency]);
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
    
    public function fetchCurrency()
    {
        return CommonHelper::fetchCurrency();
    }
}
