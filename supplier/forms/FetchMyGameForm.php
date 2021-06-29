<?php
namespace supplier\forms;

use Yii;
use yii\base\Model;
use supplier\models\SupplierGame;

class FetchMyGameForm extends Model
{
    public $supplier_id;
    private $_command;
    
    protected function createCommand()
    {
        $command = SupplierGame::find()->where([
            'supplier_id' => $this->supplier_id,
        ]);

        $this->_command = $command;
    }

    public function getCommand()
    {
        if (!$this->_command) {
            $this->createCommand();
        }
        return $this->_command;
    }
}
