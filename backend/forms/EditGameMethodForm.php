<?php
namespace backend\forms;

use Yii;
use yii\base\Model;
use backend\models\GameMethod;

class EditGameMethodForm extends Model
{
    public $id;
    public $title;
    public $description;
    public $speed;
    public $price;
    public $safe;

    protected $_method;

    public function rules()
    {
        return [
            [['id', 'title'], 'required'],
            [['description', 'speed', 'price', 'safe'], 'safe'],
        ];
    }

    public function save()
    {
        $method = $this->getMethod();
        $method->title = $this->title;
        $method->description = $this->description;
        $method->speed = $this->speed;
        $method->price = $this->price;
        $method->safe = $this->safe;
        return $method->save() ? $method : null;
    }

    public function getMethod()
    {
        if (!$this->_method) {
            $this->_method = GameMethod::findOne($this->id);
        }
        return $this->_method;
    }

    public function loadData()
    {
        $method = $this->getMethod();
        $this->title = $method->title;
        $this->description = $method->description;
        $this->speed = $method->speed;
        $this->price = $method->price;
        $this->safe = $method->safe;
    }
}
