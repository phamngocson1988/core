<?php

namespace backend\forms;

use Yii;
use yii\base\Model;

class TermsConditionForm extends Model
{
    public $member;
    public $risk;
    public $affiliate;

    public function rules()
    {
        return [
            [['member', 'risk', 'affiliate'], 'trim'],
        ];
    }
}