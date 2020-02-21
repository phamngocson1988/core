<?php

namespace backend\forms;

use Yii;
use yii\base\Model;
use backend\models\User;
use backend\models\SupplierWallet;
use yii\helpers\ArrayHelper;
use yii\data\Pagination;

class ReportSupplierBalanceDetailForm extends Model
{
    public $supplier_id;
    public $report_from;
    public $report_to;
    public $type;

    protected $_command;
    protected $_page;

    public function rules()
    {
        return [
            ['supplier_id', 'required'],
        ];
    }

    public function getCommand()
    {
        if (!$this->_command) {
            $this->_command = $this->createCommand();
        }
        return $this->_command;
    }

    public function createCommand()
    {
        $command = SupplierWallet::find()->where(['supplier_id' => $this->supplier_id]);
        if ($this->report_from) {
            $command->andWhere(['>=', "created_at", $this->report_from]);
        }
        if ($this->report_to) {
            $command->andWhere(['<=', "created_at", $this->report_to]);
        }
        if ($this->type) {
            $command->andWhere(['type' => $this->type]);
        }
        return $command;
    }

    public function fetch()
    {
        $command = $this->getCommand();
        $command->orderBy(['created_at' => SORT_DESC]);
        $pages = $this->getPage();
        return $command->offset($pages->offset)->limit($pages->limit)->all();
    }

    public function getPage()
    {
        if (!$this->_page) {
            $command = $this->getCommand();
            $pages = new Pagination(['totalCount' => $command->count()]);
            $this->_page = $pages;
        }
        return $this->_page;
    }


    public function getSupplier()
    {
        return User::findOne($this->supplier_id);
    }
}
