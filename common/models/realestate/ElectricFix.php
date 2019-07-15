<?php
namespace common\models\realestate;

use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;

class ElectricFix extends ElectricAbstract implements ElectricInterface
{
    public $price; //per month

    public function scenarios()
    {
        return [
            self::SCENARIO_DEFAULT => ['price'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'price' => 'Giá tiền cố định',
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
        return $this->price;
    }
}