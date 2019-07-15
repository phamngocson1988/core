<?php
namespace common\models\realestate;

use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;

class ElectricPerPeople extends ElectricAbstract implements ElectricInterface
{
    public $people = 1;
    public $price;

    public function scenarios()
    {
        return [
            self::SCENARIO_DEFAULT => ['price'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'price' => 'Giá tiền cố định trên 1 người',
        ];
    }

    public function render($form, $attr, $options = [])
    {
        // if (!$this->isSafeAttribute($attr)) return '';
        switch ($attr) {
            case 'price':
                return $form->field($this, $attr, $options)->textInput();
            default:
                return '';
        }
    }

    public function calculate($from, $to)
    {
        return $this->people * $this->price;
    }
}