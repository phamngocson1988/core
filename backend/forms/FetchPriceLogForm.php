<?php

namespace backend\forms;

use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use backend\models\Game;
use backend\models\GamePriceLog;

class FetchPriceLogForm extends Model
{
	public $game_id;
    public $date_range;

	protected $_command;

	public function fetch()
    {
        $command = $this->getCommand();
        return $command->all();
    }

    protected function createCommand()
    {
        $command = GamePriceLog::find();
        if ($this->game_id) {
            $command->andWhere(["game_id" => $this->game_id]);
        }
        if ($this->date_range) {
            $from = $this->parseDateRange($this->date_range);
            $command->andWhere([">=", 'updated_at', $from]);
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

    public function getGame()
    {
        if ($this->game_id) {
            return Game::findOne($this->game_id);
        }
        return null;
    }

    public function getDateRange()
    {
        return [
            'today' => 'Today',
            'last_2_days' => 'Last 2 days',
            'last_7_days' => 'Last 7 days',
            'last_month' => 'Last month',
        ];
    }

    protected function parseDateRange($term)
    {
        switch ($term) {
            case 'today':
                return date('Y-m-d 00:00:00');
            case 'last_2_days':
                return date('Y-m-d 00:00:00', strtotime("-2 days"));
            case 'last_7_days':
                return date('Y-m-d 00:00:00', strtotime("-7 days"));
            case 'last_month':
                return date('Y-m-d 00:00:00', strtotime("-30 days"));
        }
    }

}