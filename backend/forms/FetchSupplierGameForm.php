<?php
namespace backend\forms;

use Yii;
use yii\base\Model;
use backend\models\SupplierGame;
use backend\models\Supplier;

class FetchSupplierGameForm extends Model
{
    public $q;
    public $supplier_id;
    public $game_id;
    public $price_from;
    public $price_to;
    public $speed_from;
    public $speed_to;

    private $_command;
    
    protected function createCommand()
    {
        $command = SupplierGame::find();
        if ($this->supplier_id) {
            $command->andWhere(['supplier_id' => $this->supplier_id]);
        }
        if ($this->game_id) {
            $command->andWhere(['game_id' => $this->game_id]);
        }
        if ($this->price_from) {
            $command->andWhere(['>=', 'price', $this->price_from]);
        }
        if ($this->price_to) {
            $command->andWhere(['<=', 'price', $this->price_to]);
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

    public function getSupplier()
    {
        if (!$this->supplier_id) return null;
        return Supplier::findOne($this->supplier_id)->user;
    }

    public function getSuppliers()
    {
        $data = SupplierGame::find()->where(['game_id' => $this->game_id])->with('user')->all();
        $suppliers = [];
        foreach ($data as $supplier) {
            $user = $supplier->user;
            $suppliers[$user->id] = $user->name;
        }
        return $suppliers;
    }
}
