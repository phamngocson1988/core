<?php

namespace backend\forms;

use Yii;
use yii\base\Model;

class TermsConditionForm extends Model
{
    public $member;

    public function rules()
    {
        return [
            [['member'], 'trim'],
        ];
    }
}