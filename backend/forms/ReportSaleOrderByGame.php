<?php

namespace backend\forms;

use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use backend\models\Order;
use backend\models\Game;
use dosamigos\chartjs\ChartJs;

class ReportSaleOrderByGame extends Model
{
    public $game_ids;
    public $start_date;
    public $end_date;
    public $period;
    public $limit;

    protected $_game;
    private $_command;
    protected $filter_column = "filter";

    public function init()
    {
        if ($this->limit === null) $this->limit = '5';
        if ($this->limit != '0') $this->game_ids = [];
        if (!$this->start_date) $this->start_date = date('Y-m-d 00:00', strtotime('-29 days'));
        if (!$this->end_date) $this->end_date = date('Y-m-d 23:59');
    }

    public function rules()
    {
        return [
            ['start_date', 'default', 'value' => date('Y-m-d 00:00', strtotime('-29 days'))],
            ['end_date', 'default', 'value' => date('Y-m-d 23:59')],
            [['start_date', 'end_date'], 'required'],
            ['period', 'default', 'value' => 'day'],
            ['limit', 'default', 'value' => '5'],
            ['game_ids', 'required', 'when' => function($model) {
                return $model->limit == 0;
            }, 'whenClient' => "function (attribute, value) {
                return $('#limit').val() == '0';
            }",
            'message' => 'Chọn ít nhất một game để thống kê'],
        ];
    }

    public function fetch()
    {
        if (!$this->validate()) return [];
        $gameIds = $this->filterTopGames();
        $games = $this->statByGame($gameIds);
        if ($this->game_ids) return $games;
        // Other games
        $others = $this->statByOtherGames($gameIds);
        $games = array_merge_recursive($games, $others);
        return $games;
    }

    protected function filterTopGames()
    {
        if ($this->game_ids) return (array)$this->game_ids;

        $command = $this->getCommand();
        $command->select(['id', 'game_id', 'SUM(game_pack) as game_pack']);
        $command->groupBy('game_id');
        $command->orderBy(['game_pack' => SORT_DESC]);
        $command->offset(0);
        $command->limit($this->limit);
        $games = $command->asArray()->all();
        return array_column($games, 'game_id');
    }

    protected function statByGame($gameIds)
    {
        $command = $this->getCommand();
        $command->select(array_merge(['id', 'game_id', 'game_title', 'SUM(game_pack) as game_pack', 'SUM(total_price) as total_price'], [$this->getSelectByPeriod()]));
        $command->andWhere(['IN', 'game_id', $gameIds]);
        $command->orderBy(['created_at' => SORT_ASC]);
        $command->groupBy([$this->getGroupByPeriod(), 'game_id']);
        $reports = $command->asArray()->all();
        $filterColumn = $this->filter_column;
        $reportDates = array_unique(array_column($reports, $filterColumn));
        $games = [];
        foreach ($reportDates as $date) {
            $reportByDates = array_filter($reports, function($r) use ($date, $filterColumn) {
                return $r[$filterColumn] == $date;
            });
            foreach ($gameIds as $gameId) {
                $reportByGame = array_filter($reportByDates, function($r) use ($gameId) {
                    return $r['game_id'] == $gameId;
                });
                if (!$reportByGame) continue;
                $totalPackage = array_sum(array_column($reportByGame, 'game_pack'));
                $totalPrice = array_sum(array_column($reportByGame, 'total_price'));
                $gameInfo = reset($reportByGame);
                $games[$date][$gameId]['game_title'] = $gameInfo['game_title'];
                $games[$date][$gameId]['game_pack'] = $totalPackage;
                $games[$date][$gameId]['total_price'] = $totalPrice;
            }
        }
        return $games;
    }

    protected function statByOtherGames($gameIds)
    {
        $command = $this->getCommand();
        $command->select(array_merge(['id', 'game_id', 'game_title', 'SUM(game_pack) as game_pack', 'SUM(total_price) as total_price'], [$this->getSelectByPeriod()]));
        $command->andWhere(['NOT IN', 'game_id', $gameIds]);
        $command->orderBy(['created_at' => SORT_ASC]);
        $command->groupBy([$this->getGroupByPeriod()]);
        $reports = $command->asArray()->all();
        $filterColumn = $this->filter_column;
        $reportDates = array_unique(array_column($reports, $filterColumn));
        $games = [];

        foreach ($reportDates as $date) {
            $reportByDates = array_filter($reports, function($r) use ($date, $filterColumn) {
                return $r[$filterColumn] == $date;
            });
            $totalPackage = array_sum(array_column($reportByDates, 'game_pack'));
            $totalPrice = array_sum(array_column($reportByDates, 'total_price'));
            
            $games[$date]['other']['game_title'] = 'Game khác';
            $games[$date]['other']['game_pack'] = $totalPackage;
            $games[$date]['other']['total_price'] = $totalPrice;
        }
        return $games;
    }

    public function createCommand()
    {
        $command = Order::find();
        $command->where(['BETWEEN', 'created_at', $this->start_date, $this->end_date]);
        $command->andWhere(['IN', 'status', $this->availabelStatus()]);
        return $command;
    }

    public function getCommand()
    {
        if (!$this->_command) {
            $this->_command = $this->createCommand();
        }
        return clone $this->_command;
    }


    public function fetchGames()
    {
        return ArrayHelper::map(Game::find()->all(), 'id', 'title');
    }

    public function completeStatus()
    {
        return [
            Order::STATUS_PROCESSING, 
            Order::STATUS_COMPLETED
        ];
    }

    public function getGame()
    {
        if (!$this->_game) {
            $this->_game = Game::findOne($this->game_id);
        }
        return $this->_game;
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
                break;
            case 'month':
                $group = "CONCAT_WS('-', YEAR(created_at), MONTH(created_at))";
                break;
            case 'week': 
                $group = "CONCAT_WS('-', YEAR(created_at), WEEK(created_at))";
                break;
            default: //day
                $group = "CONCAT_WS('-', YEAR(created_at), MONTH(created_at), DAY(created_at))";
                break;
        }
        return $group;
    }

    public function getLabelByPeriod($label)
    {
        switch ($this->period) {
            case 'quarter':
                list($year, $quarter) = explode("-", $label);
                return sprintf("Qúy %s / %s", $quarter, $year);
            case 'month':
                list($year, $month) = explode("-", $label);
                return sprintf("Tháng %s / %s", str_pad($month, 2, "0", STR_PAD_LEFT), $year);
            case 'week': 
                list($year, $week) = explode("-", $label);
                return sprintf("Tuần %s / %s", $week + 1, $year);
            default: //day
                list($year, $month, $day) = explode("-", $label);
                return sprintf("%s-%s-%s", $year, str_pad($month, 2, "0", STR_PAD_LEFT), str_pad($day, 2, "0", STR_PAD_LEFT));
        }
        return $group;
    }

    public function getSelectByPeriod()
    {
        return $this->getGroupByPeriod() . " AS " . $this->filter_column;
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
}
