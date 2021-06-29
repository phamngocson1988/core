<?php
namespace supplier\forms;

use Yii;
use supplier\models\Supplier;
use supplier\models\SupplierGame;
use supplier\models\Game;

class EditGamePriceForm extends \common\forms\ActionForm
{
    public $supplier_id;
    public $game_id;
    public $price;

    protected $_supplier_game;
    protected $_supplier;
    protected $_game;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['supplier_id', 'game_id', 'price'], 'required'],
            ['supplier_id', 'validateSupplier'],
            ['game_id', 'validateGame'],
            ['price', 'number']
        ];
    }

    public function validateSupplier($attribute, $params = [])
    {
        $supplier = $this->getSupplier();
        if (!$supplier) {
            return $this->addError($attribute, 'Nhà cung cấp không tồn tại');
        }
        if (!$supplier->isEnabled()) {
            return $this->addError($attribute, 'Nhà cung cấp chưa được kích hoạt');
        }
    }

    public function getSupplier() 
    {
        if (!$this->_supplier) {
            $this->_supplier = Supplier::findOne(['user_id' => $this->supplier_id]);
        }
        return $this->_supplier;
    }

    public function validateGame($attribute, $params = [])
    {
        $supplierGame = $this->getSupplierGame();
        if (!$supplierGame) {
            return $this->addError($attribute, 'Nhà cung cấp chưa đăng ký game này');
        }
        if ($supplierGame->isAutoDispatcher()) {
            return $this->addError($attribute, 'Không thể cập nhật giá vì đang ở chế độ tự động nhận đơn');
        } 

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

    public function getGame() 
    {
        if (!$this->_game) {
            $supplierGame = $this->getSupplierGame();
            $this->_game = Game::findOne(['id' => $supplierGame->game_id]);
        }
        return $this->_game;
    }

    public function update() 
    {
        if (!$this->validate()) return false;
        $supplierGame = $this->getSupplierGame();
        $oldPrice = $supplierGame->price;
        $newPrice = $this->price;
        if ($oldPrice === $newPrice) {
            return true;
        }
        $supplierGame->old_price = $oldPrice;
        $supplierGame->price = $newPrice;
        $supplierGame->last_updated_price_at = $supplierGame->updated_price_at;
        $supplierGame->updated_price_at = date('Y-m-d H:i:s');
        return $supplierGame->save();
    }
}
