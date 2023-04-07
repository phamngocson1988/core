<?php
namespace backend\forms;

use Yii;
use yii\base\Model;
use backend\models\Game;
use backend\models\GameMethod;
use yii\helpers\ArrayHelper;

class FetchGameForm extends Model
{
    public $q;
    public $status;
    public $auto_dispatcher;
    public $soldout;
    public $method;

    private $_command;
    
    protected function createCommand()
    {
        $q = $this->q;
        $status = $this->status;
        $command = Game::find();
        $command->where(['<>', 'status', Game::STATUS_DELETE]);
        if ($status) {
            if (is_array($status)) {
                $command->andWhere(['in', 'status', $status]);
            } else {
                $command->andWhere(['status' => $status]);
            }
        }
        if (is_numeric($this->soldout)) {
            $command->andWhere(['soldout' => $this->soldout]);
        }
        if (is_numeric($this->auto_dispatcher)) {
            $command->andWhere(['auto_dispatcher' => $this->auto_dispatcher]);
        }
        if (is_numeric($this->method)) {
            $command->andWhere(['method' => $this->method]);
        }
        if ($q) {
            $command->andWhere(['like', 'title', $q]);
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

    public function fetchMethod()
    {
        $models = GameMethod::find()->select(['id', 'title'])->all();
        return ArrayHelper::map($models, 'id', 'title');
    }

}
