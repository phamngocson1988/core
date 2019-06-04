<?php

namespace backend\forms;

use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use backend\models\Order;
use backend\models\Game;

class ReportProcessOrderByGame extends Model
{
    public $game_id;
    public $start_date;
    public $end_date;
    public $limit = '5';
    public $period;

    protected $_game;
    protected $filter_column = "filter";
    private $_command;

    public function init()
    {
        if ($this->limit === null) $this->limit = '5';
        if ($this->limit != '0') $this->game_id = null;
        if (!$this->start_date) $this->start_date = date('Y-m-d 00:00', strtotime('-29 days'));
        if (!$this->end_date) $this->end_date = date('Y-m-d 23:59');
    }

    public function rules()
    {
        return [
            ['start_date', 'default', 'value' => date('Y-m-d 00:00', strtotime('-29 days'))],
            ['end_date', 'default', 'value' => date('Y-m-d 23:59')],
            ['period', 'default', 'value' => 'day'],
            [['start_date', 'end_date', 'period'], 'required'],
            ['limit', 'default', 'value' => '5'],
            ['game_id', 'trim'],
            ['game_id', 'required', 'when' => function($model) {
                return $model->limit == 0;
            }, 'whenClient' => "function (attribute, value) {
                return $('#limit').val() == '0';
            }",
            'message' => 'Chọn một game để thống kê'],
    
        ];
    }

    public function fetch()
    {
        if (!$this->validate()) return false;
        $gameIds = $this->filterTopGames();
        $games = $this->statByGame($gameIds);
        // Other games
        $others = $this->statByOtherGames($gameIds);
        $games = array_merge_recursive($games, $others);
        return $games;
    }

    public function createCommand()
    {
        $command = Order::find();
        $command->where(['BETWEEN', 'created_at', $this->start_date, $this->end_date]);
        $command->orderBy(['game_pack' => SORT_DESC]);
        return $command;
    }

    public function getCommand()
    {
        if (!$this->_command) {
            $this->_command = $this->createCommand();
        }
        return clone $this->_command;
    }

    protected function filterTopGames()
    {
        if ($this->game_id) return [$this->game_id];

        $status = $this->availabelStatus();
        $command = $this->getCommand();
        $command->select(['id', 'game_id', 'SUM(game_pack) as game_pack']);
        $command->andWhere(['IN', 'status', $status]);
        $command->offset(0);
        $command->limit($this->limit);
        $games = $command->asArray()->all();
        return array_column($games, 'game_id');
    }

    protected function statByGame($gameIds)
    {
        $status = $this->availabelStatus();
        $command = $this->getCommand();
        $command->select(array_merge(['id', 'game_id', 'game_title', 'SUM(game_pack) as game_pack', 'status'], [$this->getSelectByPeriod()]));
        $command->andWhere(['IN', 'status', $status]);
        $command->andWhere(['IN', 'game_id', $gameIds]);
        $command->groupBy([$this->getGroupByPeriod(), 'game_id', 'status']);
        $reports = $command->asArray()->all();
        $filterColumn = $this->filter_column;
        $reportDates = array_unique(array_column($reports, $filterColumn));
        $games = [];
        $unCompleteStatus = $this->unCompleteStatus();
        $completeStatus = $this->completeStatus();

        foreach ($reportDates as $date) {
            $reportByDates = array_filter($reports, function($r) use ($date, $filterColumn) {
                return $r[$filterColumn] == $date;
            });
            foreach ($gameIds as $gameId) {
                $reportByGame = array_filter($reportByDates, function($r) use ($gameId) {
                    return $r['game_id'] == $gameId;
                });
                $unCompleteRecords = array_filter($reportByGame, function($r) use ($unCompleteStatus) {
                    return in_array($r['status'], $unCompleteStatus);
                });
                $completeRecords = array_filter($reportByGame, function($r) use ($completeStatus) {
                    return in_array($r['status'], $completeStatus);
                });
                $completedCount = count($completeRecords);
                $penddingCount = count($unCompleteRecords);

                $totalProcessTime = array_sum(array_column($completeRecords, 'process_duration_time'));
                $totalPackage = array_sum(array_column($reportByGame, 'game_pack'));
                $rate = (!$completedCount) ? 0 : $completedCount / ($completedCount + $penddingCount) * 100;
                $avarageTime = (!$completedCount) ? 0 : $totalProcessTime / ($completedCount * 60); //mins
                
                $gameInfo = reset($reportByGame);
                $games[$date][$gameId]['game_title'] = $gameInfo['game_title'];
                $games[$date][$gameId]['game_pack'] = $totalPackage;
                $games[$date][$gameId]['completed_rate'] = $rate;
                $games[$date][$gameId]['avarage_time'] = $avarageTime;
            }
        }
        return $games;
    }

    protected function statByOtherGames($gameIds)
    {
        $status = $this->availabelStatus();
        $command = $this->getCommand();
        $command->select(array_merge(['SUM(game_pack) as game_pack', 'status'], [$this->getSelectByPeriod()]));
        $command->andWhere(['IN', 'status', $status]);
        $command->andWhere(['NOT IN', 'game_id', $gameIds]);
        $command->groupBy([$this->getGroupByPeriod(), 'status']);
        $reports = $command->asArray()->all();
        $filterColumn = $this->filter_column;
        $reportDates = array_unique(array_column($reports, $filterColumn));
        $games = [];
        $unCompleteStatus = $this->unCompleteStatus();
        $completeStatus = $this->completeStatus();

        foreach ($reportDates as $date) {
            $reportByDates = array_filter($reports, function($r) use ($date, $filterColumn) {
                return $r[$filterColumn] == $date;
            });
            $unCompleteRecords = array_filter($reportByDates, function($r) use ($unCompleteStatus) {
                return in_array($r['status'], $unCompleteStatus);
            });
            $completeRecords = array_filter($reportByDates, function($r) use ($completeStatus) {
                return in_array($r['status'], $completeStatus);
            });
            $completedCount = count($completeRecords);
            $penddingCount = count($unCompleteRecords);
            $totalProcessTime = array_sum(array_column($completeRecords, 'process_duration_time'));
            $totalPackage = array_sum(array_column($reportByDates, 'game_pack'));
            $rate = (!$completedCount) ? 0 : $completedCount / ($completedCount + $penddingCount) * 100;
            $avarageTime = (!$completedCount) ? 0 : $totalProcessTime / ($completedCount * 60); //mins
            
            $games[$date]['other']['game_title'] = 'Game khác';
            $games[$date]['other']['game_pack'] = $totalPackage;
            $games[$date]['other']['completed_rate'] = $rate;
            $games[$date]['other']['avarage_time'] = $avarageTime;
        }
        return $games;
    }


    public function fetchGames()
    {
        return ArrayHelper::map(Game::find()->all(), 'id', 'title');
    }

    public function getGame()
    {
        if (!$this->_game) {
            $this->_game = Game::findOne($this->game_id);
        }
        return $this->_game;
    }

    public function availabelStatus()
    {
        return [
            Order::STATUS_VERIFYING,
            Order::STATUS_PENDING, 
            Order::STATUS_PROCESSING, 
            Order::STATUS_COMPLETED
        ];
    }

    public function unCompleteStatus()
    {
        return [
            Order::STATUS_VERIFYING,
            Order::STATUS_PENDING, 
        ];
    }

    public function completeStatus()
    {
        return [
            Order::STATUS_PROCESSING, 
            Order::STATUS_COMPLETED
        ];
    }

    public function getLimitOptions()
    {
        return [
            '3' => 'Top 3',
            '5' => 'Top 5',
            '10' => 'Top 10',
            '0' => 'Game cụ thể',
        ];
    }

    public function getGroupByPeriod()
    {
        switch ($this->period) {
            case 'quarter':
                $group = "CONCAT_WS('-', YEAR(created_at), QUARTER(created_at))";
                // $group = ['year', 'quarter'];
                break;
            case 'month':
                $group = "CONCAT_WS('-', YEAR(created_at), MONTH(created_at))";
                // $group = ['year', 'month'];
                break;
            case 'week': 
                $group = "CONCAT_WS('-', YEAR(created_at), WEEK(created_at))";
                // $group = ['year', 'week'];
                break;
            default: //day
                $group = "CONCAT_WS('-', YEAR(created_at), MONTH(created_at), DAY(created_at))";
                // $group = ['year', 'month', 'day'];
                break;
        }
        return $group;
    }

    public function getSelectByPeriod()
    {
        return $this->getGroupByPeriod() . " AS " . $this->filter_column;
        // return ["YEAR(payment_at) as `year`", "QUARTER(payment_at) as `quarter`", "MONTH(payment_at) as `month`", "WEEK(payment_at) as `week`", "DAY(payment_at) as `day`"];
    }
}
