<?php

namespace backend\forms;

use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use backend\models\FlashSale;
use backend\models\FlashSaleGame;
use backend\models\Game;

class EditFlashSaleForm extends Model
{
    public $id;
    public $title;
    public $start_from;
    public $start_to;

    protected $_flashsale;

    public function rules()
    {
        return [
            [['id', 'title', 'start_from', 'start_to'], 'required'],
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
        $flashsale = $this->getFlashSale();
        $flashsale->title = $this->title;
        $flashsale->start_from = $this->start_from;
        $flashsale->start_to = $this->start_to;
        return $flashsale->save();
    }

    public function loadData()
    {
        $flashsale = $this->getFlashSale();
        $this->title = $flashsale->title;
        $this->start_from = $flashsale->start_from;
        $this->start_to = $flashsale->start_to;
    }

    public function getFlashSale()
    {
        if (!$this->_flashsale) {
            $this->_flashsale = FlashSale::findOne($this->id);
        }
        return $this->_flashsale;
    }
}
