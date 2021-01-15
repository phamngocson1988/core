<?php
namespace frontend\forms;

use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use frontend\models\Game;
use frontend\models\GameCategory;
use frontend\models\GameCategoryItem;

class FetchGameForm extends Model
{
    public $q;
    public $hot_deal;
    public $top_grossing;
    public $new_trending;
    public $category_id;

    private $_command;
    
    protected function createCommand()
    {
        $q = $this->q;
        $command = Game::find();
        $gameTable = Game::tableName();
        $categoryItemTable = GameCategoryItem::tableName();
        $command->select(["$gameTable.*"]);
        if ($q) {
            $command->andWhere(["like", "$gameTable.title", $q]);
        }
        if (is_numeric($this->hot_deal)) {
            $command->andWhere(["$gameTable.hot_deal" => $this->hot_deal]);
        }
        if (is_numeric($this->top_grossing)) {
            $command->andWhere(["$gameTable.top_grossing" => $this->top_grossing]);
        }
        if (is_numeric($this->new_trending)) {
            $command->andWhere(["$gameTable.new_trending" => $this->new_trending]);
        }
        if ($this->category_id) {
            $command->innerJoin($categoryItemTable, "{$gameTable}.id = {$categoryItemTable}.game_id");
            $command->andWhere(["{$categoryItemTable}.category_id" => $this->category_id]);
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

    public function fetchCategory()
    {
        $categories = GameCategory::find()->all();
        return ArrayHelper::map($categories, 'id', 'name');
    }

}
