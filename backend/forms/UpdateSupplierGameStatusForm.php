<?php

namespace backend\forms;

use Yii;
use yii\base\Model;
use backend\models\SupplierGame;

class UpdateSupplierGameStatusForm extends Model
{
    public $game_id;
    public $supplier_id;

    protected $_supplier_game;

    public function rules()
    {
        return [
            [['game_id', 'supplier_id'], 'required'],
            ['game_id', 'validateSupplierGame']
        ];
    }

    public function validateSupplierGame($attribute, $params = [])
    {
        $model = $this->getSupplierGame();
        if (!$model) {
            $this->addError($attribute, 'Nhà cung cấp không sở hữu game này');
            return;
        }
    }

    public function enable()
    {
        $model = $this->getSupplierGame();
        if (!$model->price) {
            $this->addError('game_id', 'Không thể kích hoạt game này vì nhà cung cấp chưa cập nhật giá');
            return false;
        }
        $model->status = SupplierGame::STATUS_ENABLED;
        return $model->save();
    }

    public function disable()
    {
        $model = $this->getSupplierGame();
        $model->status = SupplierGame::STATUS_DISABLED;
        return $model->save();
    }

    public function getSupplierGame()
    {
        if (!$this->_supplier_game) {
            $this->_supplier_game = SupplierGame::findOne([
                'supplier_id' => $this->supplier_id,
                'game_id' => $this->game_id
            ]);
        }
        return $this->_supplier_game;
    }

}
