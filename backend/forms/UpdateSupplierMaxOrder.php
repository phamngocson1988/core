<?php

namespace backend\forms;

use Yii;
use yii\base\Model;
use backend\models\User;
use backend\models\SupplierGame;
use yii\helpers\ArrayHelper;
/**
 * AssignRoleForm
 */
class UpdateSupplierMaxOrder extends Model
{
    public $supplier_id;
    public $game_id;
    public $max_order;

    protected $_supplierGame;
    protected $_supplier;

    public function rules()
    {
    	return [
    		[['supplier_id', 'game_id'], 'required'],
    		['supplier_id', 'validateSupplier'],
    		['max_order', 'required'],
    		['max_order', 'number'],
    	];
    }

    public function validateSupplier($attribute, $params = [])
    {
    	$supplierGame = $this->getSupplierGame();
    	if (!$supplierGame) {
    		return $this->addError($attribute, 'Nhà cung cấp chưa đăng ký game này');
    	}
    }

    public function getSupplierGame()
    {
    	if (!$this->_supplierGame) {
    		$this->_supplierGame = SupplierGame::findOne(['supplier_id' => $this->supplier_id, 'game_id' => $this->game_id]);
    	}
    	return $this->_supplierGame;
    }

    public function getSupplier()
    {
    	if (!$this->_supplier) {
    		$this->_supplier = User::findOne($this->supplier_id);
    	}
    	return $this->_supplier;
    }

    public function update()
    {
    	if (!$this->validate()) return false;
    	$supplierGame = $this->getSupplierGame();
    	$supplierGame->max_order = $this->max_order;
    	return $supplierGame->save();
    }

    public function loadData()
    {
    	$supplierGame = $this->getSupplierGame();
    	$this->max_order = (int)$supplierGame->max_order;
    }
}
