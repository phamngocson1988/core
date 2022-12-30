<?php 
namespace backend\forms;

use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use backend\models\CustomerTracker;
use common\models\Country;
use backend\models\User;
use backend\models\Game;
use backend\models\Order;

class FetchCustomerTrackerForm extends Model
{
    public $name;
    public $saler_id;
    public $country_code;
    public $phone;
    public $game_id;
    public $email;
    public $sale_growth;
    public $product_growth;
    public $is_loyalty;
    public $is_dangerous;
    
    private $_command;
    
    public function fetch()
    {
        $command = $this->getCommand();
        return $command->all();
    }

    protected function createCommand()
    {
        $command = CustomerTracker::find();
        $condition = [
            'name' => $this->name,
            'saler_id' => $this->saler_id,
            'country_code' => $this->country_code,
            'phone' => $this->phone,
            'game_id' => $this->game_id,
            'email' => $this->email,
        ];
        $condition = array_filter($condition);

        $booleanList = $this->getBooleanList();
        if (array_key_exists($this->sale_growth, $booleanList)) {
          $condition['sale_growth'] = $this->sale_growth === 'yes';
        }
        if (array_key_exists($this->product_growth, $booleanList)) {
          $condition['product_growth'] = $this->product_growth === 'yes';
        }
        if (array_key_exists($this->is_loyalty, $booleanList)) {
            $condition['is_loyalty'] = $this->is_loyalty === 'yes';
        }
        if (array_key_exists($this->is_dangerous, $booleanList)) {
            $condition['is_dangerous'] = $this->is_dangerous === 'yes';
        }
        
        if (count($condition)) {
            $command->andWhere($condition);
        }
        $command->orderBy("id desc");
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
        return CustomerTracker::CHANNELS;
    }

    public function fetchCustomerStatus() 
    {
        return CustomerTracker::CUSTOMER_STATUS;
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

    public function countSaleByUser()
    {
        $start = date("Y-m-01 00:00:00");
        $data = Order::find()->where([
            'status' => Order::STATUS_CONFIRMED
        ])
        ->andWhere([">=", "confirmed_at", $start])
        ->groupBy('customer_id')
        ->select(['customer_id', 'SUM(quantity) as quantity'])
        ->asArray()
        ->all();
        return ArrayHelper::map($data, 'customer_id', 'quantity');
    }

    public function export($fileName = null)
    {
        $command = $this->getCommand();
        $names = [
            'A4:A6' => 'No.',
            'B6' => 'Status',
            'C6' => 'Monthly Status Customer',
            'D6' => 'Name',
            'E6' => 'Nationality',
            'F6' => 'Phone',
            'G6' => 'Email',
            'H6' => 'Channel',
            'I6' => 'Game chủ đạo',
            'J6' => 'Account Manager',
            'K6' => '1st order date',
            'L6' => '1st order',
            'M6' => '2nd order',
            'N6' => '3rd order',
            'O6' => 'Monthly sales target',
            'P6' => 'G1',
            'Q6' => 'G2',
            'R6' => 'G2-G1',
            'S6' => 'Sales Growth',
            'T6' => 'Product Growth',
            'U6' => '%Result/KPI',
            'V5:V6' => 'Evaluation (1st date)',
            'W5:W6' => 'Date (1st date)',
            'X5:X6' => 'Evaluation (1st date)',
            'Y5:Y6' => 'Date (1st date)',
            'Z5:Z6' => 'Active 6 months continously',
            'AA5:AA6' => 'G1,G2<0',
            'D4:J5' => 'General Information',
            'K4:U4' => 'Sales Performance',
            'K5:O5' => 'Normal Customer',
            'P5:Q5' => 'Growth Rate',
            'R5' => 'Growth Speed',
            'S5:U5' => 'Development',
            'V4:W4' => 'Potential Customer',
            'X4:Y4' => 'Key Customer',
            'Z4' => 'Loyalty customer',
            'AA4' => 'Customer in dangerous'
        ];
        $characters = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', 'AA'];
        $titles = $names;
        $totalRow = $command->count();
        $startRow = 4;
        $startColumn = 'A';
        $endColumn = 'AA';
        $startDataRow = 7;
        $endRow = $startDataRow + $totalRow;
        $footerRow = $endRow + 1;

        $rangeTitle = sprintf('%s%s:%s%s', $startColumn, $startRow, $endColumn, $startRow + 2);
        $rangeData = sprintf('%s%s:%s%s', $startColumn, $startDataRow, $endColumn, $endRow);
        $rangeTable = sprintf('%s%s:%s%s', $startColumn, $startRow, $endColumn, $endRow);
        $headerRange = "A1:{$endColumn}1";

        $header = [
            $headerRange => sprintf('DANH SÁCH CUSTOMER TRACKER'),
        ];
        $footer = [
        ];
        
        $data = [];
        $models = $command->all();
        foreach ($models as $no => $model) {
            $data[] = [
                $model->id,
                $model->is_key_customer ? ($model->customer_tracker_status ? 'YES' : 'NO') : '-',
                $model->is_key_customer ? 'Key Customer' : ($model->is_potential_customer ? 'Potential Customer' : 'Normal Custormer'),
                $model->name,
                $model->getCountryName(),
                $model->phone,
                $model->email,
                $model->channel,
                $model->game ? $model->game->title : '-',
                $model->saler ? $model->saler->getName() : '-',
                $model->first_order_at,
                $model->sale_month_1,
                $model->sale_month_2,
                $model->sale_month_3,
                $model->sale_target,
                $model->growth_rate_1,
                $model->growth_rate_2,
                $model->growth_speed,
                $model->sale_growth,
                $model->product_growth,
                $model->kpi_growth,
                $model->is_potential_customer ? 'YES' : 'NO',
                $model->potential_customer_at,
                $model->is_key_customer ? 'YES' : 'NO',
                $model->key_customer_at,
                $model->is_loyalty ? 'YES' : 'NO',
                $model->is_dangerous ? 'YES' : 'NO',
            ];
        }
        $sheet = [
            'class' => 'common\components\export\excel\ExcelSheet',//'codemix\excelexport\ExcelSheet',
            'header' => $header,
            'footer' => $footer,
            'data' => $data,
            'startRow' => $startDataRow,
            'titles' => $titles,
            'overwriteTitles' => true,
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
                    'borders' => array(
                        'allborders' => array(
                            'style' => \PHPExcel_Style_Border::BORDER_THIN
                        )
                    )
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
                $columns = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', 'AA'];
                foreach ($columns as $column) {
                    $sheet->getColumnDimension($column)->setAutoSize(true);
                }
                $titles = $sender->getTitles();
                $sender->renderCells($titles, null);
            },
            'on afterRender' => function($event) {
                $sheet = $event->sender->getSheet();
                $sheet->setSelectedCell("A1");
            }
        ];
        $file = \Yii::createObject([
            'class' => 'codemix\excelexport\ExcelFile',
            'writerClass' => '\PHPExcel_Writer_Excel5', //\PHPExcel_Writer_Excel2007
            'sheets' => [
                'Customer Tracker' => $sheet,
            ],
        ]);
        $file->send($fileName);
    }
}