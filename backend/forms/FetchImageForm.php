<?php
namespace backend\forms;

use yii\base\Model;
use common\models\Image;
use Yii;

class FetchImageForm extends Model
{
    /**
     * @var user_id
     */
    public $user_id;

    /**
     * @var offset
     */
    public $offset = 0;

    /**
     * @var limit
     */
    public $limit = 10;

    protected $_list;

    protected $_total;
    
    public function fetch()
    {
        $command = $this->createCommand();
        $command->limit($this->getLimit());
        $command->offset($this->getOffset());
        $this->_list = $command->all();
        return $this->getList();
    }

    public function count()
    {
        $command = $this->createCommand();
        $this->_total = $command->count();
        return $this->getTotal();
    }

    protected function createCommand()
    {
        $command = Image::find();
        if ($this->user_id) {
            $command->where(['created_by' => $this->user_id]);
        }
        $command->orderBy('id desc');
        return $command;
    }

    public function getUserId()
    {
        return $this->user_id;
    }

    public function getLimit()
    {
        return (int)$this->limit;
    }

    public function getOffset()
    {
        return (int)$this->offset;
    }

    public function getList()
    {
        return $this->_list;
    }

    public function getTotal()
    {
        return $this->_total;
    }
}