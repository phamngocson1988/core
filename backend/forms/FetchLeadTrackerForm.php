<?php 
namespace backend\forms;

use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use backend\models\LeadTracker;
use backend\models\Game;
use common\models\Country;
use backend\models\User;

class FetchLeadTrackerForm extends Model
{
    public $id;
    public $saler_id;
    public $country_code;
    public $phone;
    public $game_id;
    public $email;
    public $is_potential;
    public $is_target;
    
    private $_command;
    
    public function fetch()
    {
        $command = $this->getCommand();
        return $command->all();
    }

    protected function createCommand()
    {
        $command = LeadTracker::find();
        $condition = [
            'id' => $this->id,
            'saler_id' => $this->saler_id,
            'country_code' => $this->country_code,
            'phone' => $this->phone,
            'game_id' => $this->game_id,
            'email' => $this->email,
        ];
        $condition = array_filter($condition);

        $booleanList = $this->getBooleanList();
        if (array_key_exists($this->is_potential, $booleanList)) {
          $condition['is_potential'] = $this->is_potential === 'yes';
        }
        if (array_key_exists($this->is_target, $booleanList)) {
          $condition['is_target'] = $this->is_target === 'yes';
        }
        
        if (count($condition)) {
            $command->andWhere($condition);
        }
        $command->orderBy("created_at desc");
        $this->_command = $command;
    }

    public function getCommand()
    {
        if (!$this->_command) {
            $this->createCommand();
        }
        return $this->_command;
    }
    public function getBooleanList() 
    {
        return [
            'no' => 'No',
            'yes' => 'Yes'
        ];
    }

    public function fetchChannels()
    {
        return LeadTracker::CHANNELS;
    }

    public function fetchGames()
    {
        $games = Game::find()
        ->where(['<>', 'status', Game::STATUS_DELETE])
        ->orderBy('title asc')
        ->select(['id', 'title'])->all();
        return ArrayHelper::map($games, 'id', 'title');
    }

    public function listCountries()
    {
        return ArrayHelper::map(Country::fetchAll(), 'country_code', 'country_name');
    }

    public function fetchSalers()
    {
        $member = Yii::$app->authManager->getUserIdsByRole('saler');
        $manager = Yii::$app->authManager->getUserIdsByRole('sale_manager');
        $admin = Yii::$app->authManager->getUserIdsByRole('admin');

        $salerTeamIds = array_merge($member, $manager, $admin);
        $salerTeamIds = array_unique($salerTeamIds);
        $salerTeamObjects = User::find()->where(['id' => $salerTeamIds])->select(['id', 'email'])->all();
        $salerTeam = ArrayHelper::map($salerTeamObjects, 'id', 'email');
        return $salerTeam;
    }    

    public function export($fileName = null)
    {
        $command = $this->getCommand();
        $names = [
            'No',
            'Index',
            'Lead Name & Link Account',
            'Account Manager',
            'Nationality',
            'Phone',
            'Email',
            'Channel',
            'Game',
            'Is Potential',
            'Is Target',
        ];
        $characters = [ 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K' ];
        $titles = array_combine($characters, $names);
        $totalRow = $command->count();
        $startRow = 4;
        $endRow = $startRow + $totalRow;
        $footerRow = $endRow + 1;
        $columns = array_keys($titles);
        $startColumn = reset($columns);
        $endColumn = end($columns);

        $rangeTitle = sprintf('%s%s:%s%s', $startColumn, $startRow, $endColumn, $startRow);
        $rangeData = sprintf('%s%s:%s%s', $startColumn, $startRow + 1, $endColumn, $endRow);
        $rangeTable = sprintf('%s%s:%s%s', $startColumn, $startRow, $endColumn, $endRow);
        $headerRange = "A1:{$endColumn}1";

        $header = [
            $headerRange => sprintf('DANH SÁCH LEAD TRACKER'),
        ];
        $footer = [
        ];
        
        $data = [];
        $models = $command->all();
        foreach ($models as $no => $model) {
            $data[] = [
                $no + 1, 
                '#' . $model->id, 
                $model->name,
                $model->saler ? $model->saler->getName() : '-', 
                $model->getCountryName(), 
                $model->phone, 
                $model->email, 
                $model->channel, 
                $model->game ? $model->game->title : '-', 
                $model->is_potential ? 'YES' : 'NO',
                $model->is_target ? 'YES' : 'NO',
            ];
        }
        $file = \Yii::createObject([
            'class' => 'codemix\excelexport\ExcelFile',
            'writerClass' => '\PHPExcel_Writer_Excel5', //\PHPExcel_Writer_Excel2007
            'sheets' => [
                'Report by transaction' => [
                    'class' => 'common\components\export\excel\ExcelSheet',//'codemix\excelexport\ExcelSheet',
                    // 'heading' => $heading,
                    'header' => $header,
                    'footer' => $footer,
                    'data' => $data,
                    'startRow' => $startRow,
                    'titles' => $titles,
                    'styles' => [
                        $headerRange => [
                            'font' => [
                                'bold' => true,
                            ],
                            'alignment' => [
                                'horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                            ],
                        ],
                        $rangeTitle => [
                            'font' => [
                                'bold' => true,
                            ],
                            'alignment' => [
                                'horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                            ],
                        ],
                        $rangeTable => [
                            'borders' => array(
                                'allborders' => array(
                                    'style' => \PHPExcel_Style_Border::BORDER_THIN
                                )
                            )
                        ],
                    ],
                    
                    'on beforeRender' => function ($event) {
                        $sender = $event->sender;
                        $sheet = $sender->getSheet();
                        $sender->renderHeader();
                        $sender->renderFooter();
                        $titles = $sender->getTitles();
                        $columns = array_keys($titles);
                        foreach ($columns as $column) {
                            $sheet->getColumnDimension($column)->setAutoSize(true);
                        }
                    },
                    'on afterRender' => function($event) {
                        $sheet = $event->sender->getSheet();
                        $sheet->setSelectedCell("A1");
                    }
                ],
            ],
        ]);
        $file->send($fileName);
    }
}