<?php

namespace backend\forms;

use Yii;
use yii\base\Model;
use backend\models\FlashSale;

class CreateFlashSaleForm extends Model
{
    public $title;
    public $start_from;
    public $start_to;

    public function rules()
    {
        return [
            [['title', 'start_from', 'start_to'], 'required'],
            ['start_from', 'validateStartFrom'],
        ];
    }

    public function validateStartFrom($attribute, $params) 
    {   
        $flash = $command = FlashSale::find()
        ->where(['<', 'start_from', $this->start_to])
        ->andWhere(['>', 'start_to', $this->start_from])->exists();
        if ($flash) {
            $this->addError($attribute, 'Thời gian không hợp lệ vì có sự trùng lặp với một chương trình flashsale khác');
        }
    }

    public function save()
    {
        $flashsale = new FlashSale();
        $flashsale->title = $this->title;
        $flashsale->start_from = $this->start_from;
        $flashsale->start_to = $this->start_to;
        $flashsale->save();
        return $flashsale->id;
    }
}
