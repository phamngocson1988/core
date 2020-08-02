<?php
namespace backend\forms;

use Yii;
use yii\base\Model;
use backend\models\GameMethod;

class CreateGameMethodForm extends Model
{
    public $title;
    public $description;
    public $speed;
    public $price;
    public $safe;

    public function rules()
    {
        return [
            [['title'], 'required'],
            [['description', 'speed', 'price', 'safe'], 'safe'],
        ];
    }

    public function save()
    {
        $method = new GameMethod();
        $method->title = $this->title;
        $method->description = $this->description;
        $method->speed = $this->speed;
        $method->price = $this->price;
        $method->safe = $this->safe;
        return $method->save() ? $method : null;
    }
}
