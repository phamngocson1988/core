<?php

namespace backend\forms;

use Yii;
use yii\base\Model;
use common\models\Realestate;
use yii\helpers\ArrayHelper;

class FetchRealestateForm extends Model
{
    public $q;
    public $status;
    protected $_command;

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
        $command = Realestate::find();
        // $command->with('image');

        if ($this->status) {
            $command->andWhere(['status' => $this->status]);
        }

        if ($this->q) {
            $command->andWhere(['like', 'title', $this->q]);
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
