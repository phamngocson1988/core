<?php
namespace frontend\forms;

use yii\base\Model;
use frontend\models\Category;
use Yii;

class FetchCategoryForm extends Model
{
    public $type;

    private $_command;

    public function rules()
    {
        return [
            ['type', 'required'],
        ];
    }
    
    public function fetch()
    {
        if ($this->validate()) {
            $command = $this->getCommand();
            return $command->all();
        }
        return null;        
    }

    protected function createCommand()
    {
        $command = Category::find();
        $command->andWhere(['type' => $this->type]);
        $command->andWhere(['visible' => Category::VISIBLE]);
        return $command;
    }

    public function getCommand()
    {
        if (!$this->_command) {
            $this->_command = $this->createCommand();
        }
        return $this->_command;
    }
}