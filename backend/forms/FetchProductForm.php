<?php

namespace backend\forms;

use Yii;
use yii\base\Model;
use common\models\Product;
use yii\helpers\ArrayHelper;

class FetchProductForm extends Model
{
    public $q;
    public $game_id;
    public $status;
    protected $_command;

    public function rules()
    {
        return [
            ['game_id', 'required'],
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
        $command = Product::find();
        $command->andWhere(['game_id' => $this->game_id]);
        
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
