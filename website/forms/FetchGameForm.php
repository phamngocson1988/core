<?php
namespace website\forms;

use Yii;
use yii\base\Model;
use website\models\Game;

class FetchGameForm extends Model
{
    public $q;
    public $hot_deal;
    public $top_grossing;
    public $new_trending;

    private $_command;
    
    protected function createCommand()
    {
        $q = $this->q;
        $command = Game::find();
        if ($q) {
            $command->andWhere(['like', 'title', $q]);
        }
        if (is_numeric($this->hot_deal)) {
            $command->andWhere(['hot_deal' => $this->hot_deal]);
        }
        if (is_numeric($this->top_grossing)) {
            $command->andWhere(['top_grossing' => $this->top_grossing]);
        }
        if (is_numeric($this->new_trending)) {
            $command->andWhere(['new_trending' => $this->new_trending]);
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

}
