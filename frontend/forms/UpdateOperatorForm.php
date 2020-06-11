<?php
namespace frontend\forms;

use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use frontend\models\Operator;
use common\models\Country;

class UpdateOperatorForm extends Model
{
    public $id;
    public $name;
    public $main_url;

    protected $_operator;
    
    public function rules()
    {
        return [
            ['name', 'trim'],
            [['name', 'main_url'], 'string', 'max' => 255],
        ];
    }

    public function save()
    {
        $operator = $this->getOperator();
        $operator->name = $this->name;
        return $operator->save();
    }

    public function getOperator()
    {
        if (!$this->_operator) {
            $this->_operator = Operator::findOne($this->id);
        }
        return $this->_operator;
    }

    public function loadData()
    {
        $operator = $this->getOperator();
        $this->name = $operator->name;
    }

}
