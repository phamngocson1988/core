<?php

namespace backend\forms;

use Yii;
use yii\base\Model;
use backend\models\ComplainReason;

class CreateComplainReasonForm extends Model
{
    public $title;

    public function rules()
    {
        return [
            [['title'], 'required'],
        ];
    }

    public function create()
    {
        $reason = new ComplainReason();
        $reason->title = $this->title;
        return $reason->save();
    }
}
